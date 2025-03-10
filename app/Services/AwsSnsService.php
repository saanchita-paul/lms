<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Exception;
use Illuminate\Support\Facades\Log;

class AwsSnsService
{
    private SnsClient $snsClient;

    public function __construct()
    {
        $this->snsClient = new SnsClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-2'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    public function createTopicIfNotExists(string $topicName)
    {
        try {
            $result = $this->snsClient->createTopic([
                'Name' => $topicName
            ]);

            Log::info('SNS Topic created or retrieved', [
                'topicArn' => $result['TopicArn']
            ]);

            return $result['TopicArn'];
        } catch (Exception $e) {
            Log::error('Failed to create SNS topic', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function updateTopicPolicyForS3(string $topicArn, string $bucketName): bool
    {
        try {
            // Get existing policy
            $attributes = $this->snsClient->getTopicAttributes([
                'TopicArn' => $topicArn
            ])->get('Attributes');

            // Parse existing policy or create new one
            $existingPolicy = !empty($attributes['Policy'])
                ? json_decode($attributes['Policy'], true)
                : [
                    'Version' => '2012-10-17',
                    'Statement' => []
                ];

            // Get AWS account ID from the topic ARN
            preg_match('/arn:aws:sns:[^:]+:([^:]+):/', $topicArn, $matches);
            $accountId = $matches[1];

            // New statement to add
            $newStatement = [
                'Sid' => 'AllowS3ToPublishEvents_' . time(),
                'Effect' => 'Allow',
                'Principal' => [
                    'Service' => 's3.amazonaws.com'
                ],
                'Action' => 'SNS:Publish',
                'Resource' => $topicArn,
                'Condition' => [
                    'StringEquals' => [
                        'AWS:SourceAccount' => $accountId
                    ],
                    'ArnLike' => [
                        'aws:SourceArn' => "arn:aws:s3:::$bucketName"
                    ]
                ]
            ];

            // Add new statement to existing ones
            $existingPolicy['Statement'][] = $newStatement;

            // Update topic policy
            $this->snsClient->setTopicAttributes([
                'TopicArn' => $topicArn,
                'AttributeName' => 'Policy',
                'AttributeValue' => json_encode($existingPolicy)
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to update SNS topic policy', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function subscribe(string $topicArn, string $protocol, string $endpoint)
    {
        try {
            $result = $this->snsClient->subscribe([
                'TopicArn' => $topicArn,
                'Protocol' => $protocol,
                'Endpoint' => $endpoint
            ]);

            Log::info('SNS Subscription created', [
                'subscriptionArn' => $result['SubscriptionArn']
            ]);

            return $result['SubscriptionArn'];
        } catch (Exception $e) {
            Log::error('Failed to create SNS subscription', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
