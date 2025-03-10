<?php

namespace App\Services\Assistant\Chatbot;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ThreadChatService
{
    protected $client;
    protected $apiUrl;
    protected $headers;


    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = 'https://api.openai.com/v1';
        $this->headers = [
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2'
        ];
    }


    public function createThread($assistantId, $message)
    {
        try {
            $response = $this->client->post("{$this->apiUrl}/threads/runs", [
                'headers' => $this->headers,
                'json' => [
                    'assistant_id' => $assistantId,
                    'thread' => [
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    ['type' => 'text', 'text' => $message]
                                ]
                            ]
                        ]
                    ]

                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function postMessageToThread($threadId, $message)
    {
        try {
            $data = [
                "role" => "user",
                "content" => $message
            ];
            $response = $this->client->post("{$this->apiUrl}/threads/{$threadId}/messages", [
                'headers' => $this->headers,
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;

        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function runThread($threadId, $assistantId)
    {
        try {
            $response = $this->client->post("{$this->apiUrl}/threads/{$threadId}/runs", [
                'headers' => $this->headers,
                'json' => [
                    'assistant_id' => $assistantId
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            if (strpos($e->getMessage(), 'already has an active run') !== false) {
                // Thread already has an active run; proceed to retrieve messages
                return ['active' => true];
            }
            return ['error' => $e->getMessage()];
        }
    }

    public function getMessages($threadId)
    {
        try {
            $response = $this->client->get("{$this->apiUrl}/threads/{$threadId}/messages", [
                'headers' => $this->headers,
            ]);
            $messages = json_decode($response->getBody()->getContents(), true);
            $threadMessages = [];

            if (isset($messages['data']) && is_array($messages['data'])) {
                foreach ($messages['data'] as $message) {
                    if (isset($message['role']) && isset($message['content'][0]['text']['value'])) {
                        $threadMessages[] = [
                            'role' => $message['role'],
                            'message' => $message['content'][0]['text']['value']
                        ];
                    }
                }
            }
            return !empty($threadMessages) ? $threadMessages : "Sorry, I can't help with this query right now. Do you have any other query?";

        } catch (GuzzleException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function askAssistant($assistantId, $message, $existingThreadId = null)
    {
        if ($existingThreadId) {
            $this->postMessageToThread($existingThreadId, $message);
            $threadId = $existingThreadId;
        } else {
            $thread = $this->createThread($assistantId, $message);
            if (isset($thread['error'])) return $thread;
            $threadId = $thread['thread_id'];
        }

        $run = $this->runThread($threadId, $assistantId);
        if (isset($run['error'])) return $run;

        $m = $this->getMessages($threadId);
        return ['threadMessages' => $m[0], 'threadId' => $threadId, 'allMessages' => $m];
    }
}
