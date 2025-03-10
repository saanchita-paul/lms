<?php

namespace App\Services\Assistant\Chatbot;

use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HelpChatbotService
{
    protected $gptApiKey;
    protected $assistantId;

    public function __construct()
    {
        $this->gptApiKey = env('OPENAI_API_KEY');
        $this->assistantId = env('HELP_CHAT_ASSISTANT_ID');
    }

    public function createThread($assistantId, $message)
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
                    'Authorization' => 'Bearer ' . $this->gptApiKey,
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

    public function postMessageToThread($threadId, $message)
    {
        $runCompleted = $this->waitForRunCompletion($threadId);

        if (!$runCompleted) {
            return 'Error: The run is still active. Please try again later.';
        }

        $url = "https://api.openai.com/v1/threads/{$threadId}/messages";

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->gptApiKey,
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

    public function waitForRunCompletion($threadId)
    {
        $maxRetries = 10;
        $retryInterval = 3;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            $runStatusResponse = $this->getRunStatus($threadId);

            if ($runStatusResponse === 'completed') {
                return true;
            }

            sleep($retryInterval);
        }

        return false;
    }

    public function getRunStatus($threadId)
    {
        try {
            $url = "https://api.openai.com/v1/threads/{$threadId}/runs";
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->gptApiKey,
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

    public function runThread($threadId, $assistantId)
    {
        $data = [
            'assistant_id' => $assistantId,
            'stream' => false,
            'tool_choice' => null,
        ];

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'text/event-stream',
                'Authorization' => 'Bearer ' . $this->gptApiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            return 'Error: ' . $response->body();
        }
    }

    public function getThreadMessages($threadId, $assistantId, $clinicId, $userMessage = null)
    {
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->gptApiKey,
                'OpenAI-Beta' => 'assistants=v2',
            ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

        if ($response->successful()) {
            $messages = $response->json();
            $assistantMessages = [];
            if (isset($messages['data']) && is_array($messages['data'])) {
                foreach ($messages['data'] as $message) {
                    if ($message['role'] === 'assistant' && isset($message['content'][0]['text']['value'])) {
                        $cleanedAnswer = preg_replace('/【.*?】/', '', $message['content'][0]['text']['value']);
                        $assistantMessages[] = [
                            'question' => $userMessage,
                            'answer' =>  $cleanedAnswer
                        ];
                    }
                }
            }

            ChatHistory::updateOrCreate([
                'assistant_id' => $assistantId,
                'thread_id' => $threadId
            ],[
                'clinic_id' => $clinicId,
//                'messages' => json_encode($assistantMessages)
            ]);
            $allAnswers = array_column($assistantMessages, 'answer');
            return !empty($allAnswers)
                ? [
                    'allAnswers' => $allAnswers,
                    'threadId' => $threadId
                ]
                : "Sorry, I can't help with this query right now. Do you have any other query?";
        } else {
            return 'Error: ' . $response->body();
        }
    }

    public function getExistingThreadId($assistantId)
    {
        $chatHistory = ChatHistory::where('assistant_id', $assistantId)->first();
        return $chatHistory ? $chatHistory->thread_id : null;
    }

    public function executeAIResponse($userMessage, $assistantId, Request $request, $threadId)
    {
        $clinicId = $request->get('clinic_id');
//        $assistantId = $this->assistantId;

        if (!$threadId) {
            $runResponse = $this->createThread($assistantId, $userMessage);

            if (!$runResponse) {
                return 'Error: Could not run the thread.';
            }

            $threadId = $runResponse['thread_id'];
        } else {
            $createMessage = $this->postMessageToThread($threadId, $userMessage);

            if (!$createMessage) {
                return 'Error: Could not send message to the thread.';
            }
        }

        $runThread = $this->runThread($threadId, $assistantId);

        if (!$runThread) {
            return 'Error: Could not process the thread.';
        }

        $runCompleted = $this->waitForRunCompletion($threadId);

        if (!$runCompleted) {
            return 'Error: The run is still active. Please try again later.';
        }

        return $this->getThreadMessages($threadId, $assistantId, $clinicId, $userMessage);
    }

}
