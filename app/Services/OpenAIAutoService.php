<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Clinic; // Import the Clinic model


class OpenAIAutoService
{
    public function runThread($assistantId, $message, $apiKey)
    {
        $runPayload = [
            'assistant_id' => $assistantId,
            'thread' => [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $message ?: 'default message here',
                            ]
                        ],
                    ],
                ],
            ],
        ];

       

        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'OpenAI-Beta' => 'assistants=v2',
                ])->post("https://api.openai.com/v1/threads/runs", $runPayload);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Failed to run the thread: ' . $response->body());
                return null;
            }
           
            
        } catch (\Exception $e) {
            Log::error('Error running the thread: ' . $e->getMessage());
            return null;
        }
    }

    public function createMessage($threadId, $message, $apiKey)
    {
        $runCompleted = $this->waitForRunCompletion($threadId,$apiKey);

        if (!$runCompleted) {
            return 'Error: The run is still active. Please try again later.';
        }

        $url = "https://api.openai.com/v1/threads/{$threadId}/messages";

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
                'OpenAI-Beta' => 'assistants=v2',
            ])->post($url, [
                'role' => 'user',
                'content' => $message,
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return 'Error: ' . $response->body();
        }
    }

    private function waitForRunCompletion($threadId,$apiKey)
    {
        $maxRetries = 10;
        $retryInterval = 3;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            $runStatusResponse = $this->getRunStatus($threadId,$apiKey);

            if ($runStatusResponse === 'completed') {
                return true;
            }

            sleep($retryInterval);
        }

        return false;
    }

    private function getRunStatus($threadId,$apiKey)
    {
        try {
            $url = "https://api.openai.com/v1/threads/{$threadId}/runs";
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'OpenAI-Beta' => 'assistants=v2',
                ])->get($url);

            if ($response->successful()) {
                $runData = $response->json();
                return $runData['data'][0]['status'] ?? null;
            } else {
                Log::error('Failed to retrieve run status: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving run status: ' . $e->getMessage());
            return null;
        }
    }

    public function step3($threadId, $assistantId, $apiKey)
    {
        $data = [
            'assistant_id' => $assistantId,
            'stream' => false,
            'tool_choice' => null,
        ];

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'text/event-stream',
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            return 'Error: ' . $response->body();
        }
    }

    public function getThreadMessages($threadId,$apiKey)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
                'OpenAI-Beta' => 'assistants=v2',
            ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

        if ($response->successful()) {
            $data = $response->json();
           
            foreach ($data['data'] as $message) {
                if ($message['role'] === 'assistant') {
                    $assistantMessage =  $message['content'][0]['text']['value'];

                    return $assistantMessage;
                }
            }

            return 'No assistant message found.';
        } else {
            return 'Error: ' . $response->body();
        }
    }

    public function generateAIResponseWithThread($userMessage, $assistantId,$apiKey)
    {
        $runResponse = $this->runThread($assistantId, $userMessage, $apiKey);


        if (!$runResponse) {
            return 'Error: Could not run the thread.';
        }

        $threadRunId = $runResponse['thread_id'];
        $assistantId = $runResponse['assistant_id'];

        $createMessage = $this->createMessage($threadRunId, $userMessage,$apiKey);

        if (!$createMessage) {
            return 'Error: Could not send message to the thread.';
        }

        

        $step3Run = $this->step3($threadRunId, $assistantId,$apiKey);

        if (!$step3Run) {
            return 'Error: Could not process the thread.';
        }

        return $this->getThreadMessages($threadRunId,$apiKey);
    }
}