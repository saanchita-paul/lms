<?php

namespace App\Services\Assistant\Chatbot;

use App\Models\Clinic;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\Log;

class SaveThreadIdService
{
    public function saveChatHistory($assistantId, $threadId)
    {

       

        $clinic = Clinic::where('assistant_id', $assistantId)->first();

        if (!$clinic) {
            Log::error("No clinic found for assistant ID: $assistantId");
            return false;
        }

        $existingChatHistory = ChatHistory::where('assistant_id', $assistantId)
        ->where('thread_id', $threadId)
        ->first();

        // If a record exists, return a response indicating it already exists
        if ($existingChatHistory) {
            return response()->json(['message' => 'Record already exists'], 409);
        }

        try {
            ChatHistory::create([
                'assistant_id' => $assistantId,
                'thread_id' => $threadId,
                'clinic_id' => $clinic->id,
                'messages' => null,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Error saving chat history: " . $e->getMessage());
            return false;
        }
    }
}
