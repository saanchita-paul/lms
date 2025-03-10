<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Services\AwsS3Service;
use App\Services\AwsSesService;
use App\Services\AwsSnsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailConfigController extends Controller
{
    private AwsSesService $awsSesService;
    private AwsS3Service $awsS3Service;

    public function __construct(AwsSesService $awsSesService, AwsS3Service $awsS3Service)
    {
        $this->awsSesService = $awsSesService;
        $this->awsS3Service = $awsS3Service;
    }

    public function configureEmailReceiving(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'clinic_id' => 'required|string'
            ]);

            $clinic = Clinic::find($request->get('clinic_id'));
            $bucketName = $clinic->inbox_id;

            // Configure S3 bucket and SES rule
            $this->awsS3Service->configureS3BucketForSES($bucketName);
            $this->awsSesService->createEmailReceivingRule($clinic->email_id, $bucketName);

            // 3. Wait for SES setup file to be created
            sleep(5);
            // 4. Now try to delete the SES notification file
            try {
                $this->awsS3Service->deleteSetupNotification($bucketName);
            } catch (\Exception $e) {
                Log::error('Failed to delete setup notification', [
                    'error' => $e->getMessage()
                ]);
                // Continue execution even if deletion fails
            }
            // Configure SNS
            $awsSnsService = app(AwsSnsService::class);
            $topicArn = $awsSnsService->createTopicIfNotExists(env('AWS_TOPIC'));

            // Update SNS policy and add notifications
            $awsSnsService->updateTopicPolicyForS3($topicArn, $bucketName);
            $this->awsS3Service->addSnsNotification($bucketName, $topicArn);

            // Create subscription
            $awsSnsService->subscribe(
                $topicArn,
                'https',
                env('AWS_NOTIFICATION_ENDPOINT')
            );

            return response()->json([
                'message' => 'Email receiving configured successfully',
                'bucket_name' => $bucketName
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to configure email receiving',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
