<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Services\Assistant\Chatbot\ThreadListService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\Assistant\Chatbot\SaveThreadIdService;
use App\Models\Clinic;

class ThreadController extends Controller
{

    public function listThreads(Request $request, $assistantId)
    {
        try {
            // Get the clinic_id from the request (or any other source)
            $clinicId = $request->input('clinic_id'); // Assuming clinic_id is passed in the request body

            // Instantiate the service with the clinic_id
            $threadListService = new ThreadListService($clinicId);

            $result = $threadListService->listThreads($assistantId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getThreadHistory($threadId, Request $request)
    {
        $clinicId = $request->input('clinic_id'); // Get clinic_id from the request
        $chatApiKey = Clinic::where('id', $clinicId)->whereNotNull('chat_api_key')->value('chat_api_key'); // Fetch chat_api_key

        if (!$chatApiKey) {
            return response()->json(['error' => 'API key not found for the specified clinic'], 400);
        }

        $client = new Client();
        $url = "https://api.openai.com/v1/threads/{$threadId}/messages";

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $chatApiKey, // Use the fetched API key
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2'
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            foreach ($data['data'] as &$message) {
                if (isset($message['content'][0]['text']['value'])) {
                    $message['content'][0]['text']['value'] = preg_replace('/【\d+:\d+†source】/', '', $message['content'][0]['text']['value']);
                }
            }
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Failed to fetch thread: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch thread history'], 500);
        }
    }


    public function handleThreadId(Request $request)
    {
        $request->validate([
            'assistant_id' => 'required|string',
            'thread_id' => 'required|string'
        ]);

        $assistantId = $request->assistant_id;
        $threadId = $request->thread_id;

        $SaveThreadIdService = new SaveThreadIdService();
        $result = $SaveThreadIdService->saveChatHistory($assistantId, $threadId);

        if ($result) {
            return response()->json(['message' => 'Thread Id & Assisatnt Id saved successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to save chat history'], 400);
        }
    }
}
