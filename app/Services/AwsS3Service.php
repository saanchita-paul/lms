<?php

namespace App\Services;

use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Log;

class AwsS3Service
{
    private S3Client $s3Client;

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-2'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function createBucket(string $bucketName): bool
    {
        try {
            // Check if bucket exists
            if ($this->s3Client->doesBucketExist($bucketName)) {
                return true;
            }

            $this->s3Client->createBucket([
                'Bucket' => $bucketName,
                'CreateBucketConfiguration' => [
                    'LocationConstraint' => env('AWS_DEFAULT_REGION', 'us-east-2')
                ]
            ]);

            $this->s3Client->waitUntil('BucketExists', ['Bucket' => $bucketName]);

            // Set bucket policy for SES
            $this->setBucketPolicyForSES($bucketName);

            // Delete the default SES notification file
            try {
                $this->s3Client->deleteObject([
                    'Bucket' => $bucketName,
                    'Key' => 'AMAZON_SES_SETUP_NOTIFICATION'
                ]);
            } catch (Exception $e) {
                // Ignore if file doesn't exist
                Log::info('No setup notification file found to delete');
            }
            return true;
        } catch (Exception $e) {
            throw new Exception("Error creating S3 bucket: " . $e->getMessage());
        }
    }

    public function setBucketPolicyForSES(string $bucketName): bool
    {
        try {
            $policy = [
                'Version' => '2012-10-17',
                'Statement' => [
                    [
                        'Sid' => 'AllowSESPuts',
                        'Effect' => 'Allow',
                        'Principal' => [
                            'Service' => 'ses.amazonaws.com'
                        ],
                        'Action' => 's3:PutObject',
                        'Resource' => "arn:aws:s3:::{$bucketName}/*",
                        'Condition' => [
                            'StringEquals' => [
                                'AWS:SourceAccount' => env('AWS_ACCOUNT_ID'),
                                'AWS:SourceArn' => "arn:aws:ses:" . env('AWS_DEFAULT_REGION', 'us-east-2') . ":" . env('AWS_ACCOUNT_ID') . ":*"
                            ]
                        ]
                    ]
                ]
            ];

            $this->s3Client->putBucketPolicy([
                'Bucket' => $bucketName,
                'Policy' => json_encode($policy)
            ]);

            Log::info('S3 bucket policy updated for SES', [
                'bucketName' => $bucketName
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to update S3 bucket policy', [
                'error' => $e->getMessage(),
                'bucketName' => $bucketName
            ]);
            throw $e;
        }
    }

    public function doesBucketExist(string $bucketName): bool
    {
        try {
            $this->s3Client->headBucket([
                'Bucket' => $bucketName
            ]);
            return true;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            if ($e->getAwsErrorCode() === 'NotFound') {
                return false;
            }
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function addSnsNotification(string $bucketName, string $snsTopicArn): bool
    {
        try {
            $this->s3Client->putBucketNotificationConfiguration([
                'Bucket' => $bucketName,
                'NotificationConfiguration' => [
                    'TopicConfigurations' => [
                        [
                            'TopicArn' => $snsTopicArn,
                            'Events' => ['s3:ObjectCreated:*'],
                            'Id' => 'Email Notification' // Unique identifier for this configuration
                        ]
                    ]
                ]
            ]);

            Log::info('Added SNS notification to bucket', [
                'bucket' => $bucketName,
                'topicArn' => $snsTopicArn
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to add SNS notification to bucket', [
                'error' => $e->getMessage(),
                'bucket' => $bucketName
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function configureS3BucketForSES(string $bucketName): bool
    {
        try {
            // First check if bucket exists
            if (!$this->doesBucketExist($bucketName)) {
                // Create bucket if it doesn't exist
                $this->createBucket($bucketName);

                // Wait briefly for bucket creation to propagate
                sleep(2);
            }

            // Use simple bucket policy instead of ACL
            $policy = [
                'Version' => '2012-10-17',
                'Statement' => [
                    [
                        'Sid' => 'AllowSESPuts',
                        'Effect' => 'Allow',
                        'Principal' => [
                            'Service' => 'ses.amazonaws.com'
                        ],
                        'Action' => 's3:PutObject',
                        'Resource' => "arn:aws:s3:::{$bucketName}/*"
                    ]
                ]
            ];

            $this->s3Client->putBucketPolicy([
                'Bucket' => $bucketName,
                'Policy' => json_encode($policy)
            ]);

            // Enable versioning
            $this->s3Client->putBucketVersioning([
                'Bucket' => $bucketName,
                'VersioningConfiguration' => [
                    'Status' => 'Enabled'
                ]
            ]);

            Log::info('S3 bucket configured for SES', [
                'bucketName' => $bucketName
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to configure S3 bucket for SES', [
                'error' => $e->getMessage(),
                'bucketName' => $bucketName
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function deleteSetupNotification(string $bucketName): bool
    {
        try {
            $objects = $this->s3Client->listObjectsV2([
                'Bucket' => $bucketName
            ]);

            if (isset($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    Log::info('Found object', ['key' => $object['Key']]);
                    if ($object['Key'] === 'AMAZON_SES_SETUP_NOTIFICATION') {
                        $this->s3Client->deleteObject([
                            'Bucket' => $bucketName,
                            'Key' => 'AMAZON_SES_SETUP_NOTIFICATION'
                        ]);
                        Log::info('SES setup notification file deleted', ['bucket' => $bucketName]);
                        return true;
                    }
                }
            }
            Log::info('Setup notification file not found', ['bucket' => $bucketName]);
            return false;
        } catch (Exception $e) {
            Log::error('Error deleting setup notification', [
                'error' => $e->getMessage(),
                'bucket' => $bucketName
            ]);
            throw $e;
        }
    }
}
