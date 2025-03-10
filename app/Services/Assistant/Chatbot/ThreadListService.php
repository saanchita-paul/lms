<?php

namespace App\Services\Assistant\Chatbot;

use App\Models\ChatHistory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Clinic;

class ThreadListService
{
    protected $client;
    protected $headers;
    protected $apiKey;


    public function __construct($clinicId)
    {
        // Fetch the chat_api_key for the given clinic_id
        $this->apiKey = Clinic::where('id', $clinicId)
                              ->whereNotNull('chat_api_key')
                              ->value('chat_api_key');

        if (!$this->apiKey) {
            Log::warning("No valid chat_api_key found for clinic_id: {$clinicId}");
            throw new \Exception("No valid API key found for the specified clinic.");
        }

        $this->client = new Client();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2',
        ];
    }

    public function listThreads($assistantId)
    {
        try {
            $threads = ChatHistory::select('thread_id')
                ->where('assistant_id', $assistantId)
                ->distinct()
                ->orderBy('id', 'desc')
                ->paginate(20);

            $formattedThreads = $threads->getCollection()->map(function ($thread) {
                return $this->getThreadDetails($thread->thread_id);
            });

            return [
                'data' => $formattedThreads,
                'pagination' => [
                    'current_page' => $threads->currentPage(),
                    'last_page' => $threads->lastPage(),
                    'per_page' => $threads->perPage(),
                    'total' => $threads->total(),
                ],
            ];
        } catch (\Exception $e) {
            Log::error("Failed to fetch threads from the database: " . $e->getMessage());
            throw new \Exception('Failed to fetch threads from the database');
        }
    }

    private function getThreadDetails($threadId)
    {
        $url = "https://api.openai.com/v1/threads/{$threadId}/messages";

        try {
            $response = $this->client->get($url, ['headers' => $this->headers]);
            $data = json_decode($response->getBody(), true);

            $latestMessage = !empty($data['data']) ? $data['data'][0] : null;

            return [
                'thread_id' => $threadId,
                'last_message' => $latestMessage['content'][0]['text']['value'] ?? 'No messages yet',
                'last_message_time' => $latestMessage['created_at'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error("Failed to fetch thread messages from API for thread {$threadId}: " . $e->getMessage());
            return [
                'thread_id' => $threadId,
                'last_message' => 'Error fetching message',
                'last_message_time' => null,
            ];
        }
    }
}
