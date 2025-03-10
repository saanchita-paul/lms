<?php

namespace App\Services;

use Aws\Exception\AwsException;
use Aws\Ses\SesClient;
use Exception;
use Illuminate\Support\Facades\Log;

class AwsSesService
{
    private SesClient $sesClient;

    public function __construct()
    {
        $this->sesClient = new SesClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-2'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Create a new receipt rule set
     */
    public function createReceiptRuleSet(string $ruleSetName): \Aws\Result
    {
        try {
            $result = $this->sesClient->createReceiptRuleSet([
                'RuleSetName' => $ruleSetName,
            ]);

            Log::info('Receipt rule set created successfully', ['result' => $result]);
            return $result;

        } catch (AwsException $e) {
            Log::error('Failed to create receipt rule set', [
                'error' => $e->getMessage(),
                'ruleSetName' => $ruleSetName
            ]);
            throw $e;
        }
    }

    /**
     * Create a new receipt rule
     */
    public function createReceiptRule(
        string $ruleSetName,
        string $ruleName,
        string $domainName,
        array $actions,
        bool $enabled = true,
        string $tlsPolicy = 'Optional'
    ): \Aws\Result
    {
        try {
            $result = $this->sesClient->createReceiptRule([
                'RuleSetName' => $ruleSetName,
                'Rule' => [
                    'Name' => $ruleName,
                    'Enabled' => $enabled,
                    'TlsPolicy' => $tlsPolicy,
                    'Recipients' => [$domainName],
                    'Actions' => $actions,
                    'ScanEnabled' => true
                ]
            ]);

            Log::info('Receipt rule created successfully', [
                'ruleName' => $ruleName,
                'domain' => $domainName
            ]);
            return $result;

        } catch (AwsException $e) {
            Log::error('Failed to create receipt rule', [
                'error' => $e->getMessage(),
                'ruleName' => $ruleName,
                'domain' => $domainName
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing receipt rule
     */
    public function updateReceiptRule(
        string $ruleSetName,
        string $ruleName,
        string $domainName,
        array $actions,
        bool $enabled = true
    ): \Aws\Result
    {
        try {
            $result = $this->sesClient->updateReceiptRule([
                'RuleSetName' => $ruleSetName,
                'Rule' => [
                    'Name' => $ruleName,
                    'Enabled' => $enabled,
                    'TlsPolicy' => 'Optional',
                    'Recipients' => [$domainName],
                    'Actions' => $actions,
                    'ScanEnabled' => true
                ]
            ]);

            Log::info('Receipt rule updated successfully', [
                'ruleName' => $ruleName,
                'domain' => $domainName
            ]);
            return $result;

        } catch (AwsException $e) {
            Log::error('Failed to update receipt rule', [
                'error' => $e->getMessage(),
                'ruleName' => $ruleName,
                'domain' => $domainName
            ]);
            throw $e;
        }
    }

    /**
     * Delete a receipt rule
     */
    public function deleteReceiptRule(string $ruleSetName, string $ruleName): \Aws\Result
    {
        try {
            $result = $this->sesClient->deleteReceiptRule([
                'RuleSetName' => $ruleSetName,
                'RuleName' => $ruleName
            ]);

            Log::info('Receipt rule deleted successfully', ['ruleName' => $ruleName]);
            return $result;

        } catch (AwsException $e) {
            Log::error('Failed to delete receipt rule', [
                'error' => $e->getMessage(),
                'ruleName' => $ruleName
            ]);
            throw $e;
        }
    }

    /**
     * Helper method to create S3 action configuration
     */
    public function createS3Action(string $bucketName, string $prefix = '', string $topicArn = null): array
    {
        $action = [
            'S3Action' => [
                'BucketName' => $bucketName,
                'ObjectKeyPrefix' => $prefix,
            ]
        ];

        if ($topicArn) {
            $action['S3Action']['TopicArn'] = $topicArn;
        }

        return $action;
    }

    public function verifyDomain(string $domain)
    {
        try {
            $result = $this->sesClient->verifyDomainIdentity([
                'Domain' => $domain
            ]);

            Log::info('Domain verification initiated', [
                'domain' => $domain,
                'verificationToken' => $result['VerificationToken']
            ]);

            return $result['VerificationToken'];
        } catch (AwsException $e) {
            Log::error('Failed to verify domain', [
                'error' => $e->getMessage(),
                'domain' => $domain
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function createEmailReceivingRule(string $email, string $inboxId): \Aws\Result
    {
        try {
            $ruleName = str_replace(['@', '.'], '-', $email);
            $ruleSetName = env('AWS_RULE_SET_NAME');

            try {
                $this->createReceiptRuleSet($ruleSetName);
            } catch (\Aws\Ses\Exception\SesException $e) {
                if ($e->getAwsErrorCode() !== 'AlreadyExists') {
                    throw $e;
                }
            }

            try {
                $s3Action = $this->createS3Action($inboxId);
                return $this->createReceiptRule($ruleSetName, $ruleName, $email, [$s3Action]);
            } catch (\Aws\Ses\Exception\SesException $e) {
                if ($e->getAwsErrorCode() === 'AlreadyExists') {
                    // If rule exists, try to update it instead
                    return $this->updateReceiptRule($ruleSetName, $ruleName, $email, [$s3Action]);
                }
                throw $e;
            }
        } catch (Exception $e) {
            Log::error('Failed to create/update email receiving rule', [
                'error' => $e->getMessage(),
                'email' => $email
            ]);
            throw $e;
        }
    }
}
