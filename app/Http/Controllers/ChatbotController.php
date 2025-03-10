<?php

namespace App\Http\Controllers;

use App\Services\Assistant\Chatbot\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function askAssistant(Request $request, $assistantId)
    {
        $request->validate([
            'question' => 'required|string',
        ]);

        $question = $request->input('question');
        $threadId = $request->input('thread_id');

        // Set the API key based on clinic_id
        $clinicId = $request->input('clinic_id');  // Assuming clinic_id is sent with the request
        $this->chatbotService->setApiKey($clinicId);


        $response = $this->chatbotService->executeAIResponse($question, $assistantId, $request, $threadId);

        return response()->json($response);
    }
}
