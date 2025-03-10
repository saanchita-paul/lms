<?php

namespace App\Http\Controllers;

use App\Services\Assistant\Chatbot\HelpChatbotService;
use App\Services\Assistant\GetHelpAssistantByIdService;
use Illuminate\Http\Request;

class HelpAssistantController extends Controller
{
    public function getHelpAssistantById()
    {
        $getHelpAssistantIdService = new GetHelpAssistantByIdService();
        $result = $getHelpAssistantIdService->getHelpAssistantById();
        return response()->json($result);
    }

    public function askHelpAssistant(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
        ]);

        $question = $request->input('question');
        $threadId = $request->input('thread_id');

        $assistantId = env('HELP_CHAT_ASSISTANT_ID');
        if (empty($assistantId)) {
            return response()->json(['error' => 'Assistant ID is not configured'], 400);
        }

        $helpChatbotService = new HelpChatbotService();
        $response = $helpChatbotService->executeAIResponse($question, $assistantId, $request, $threadId);

        return response()->json($response);
    }



}
