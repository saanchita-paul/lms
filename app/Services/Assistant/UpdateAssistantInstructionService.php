<?php

namespace App\Services\Assistant;

use App\Models\Clinic;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateAssistantInstructionService
{
    public function updateInstructions($clinicId, Request $request)
    {

        $validatedData = $request->validate([
            'sms_instructions' => 'required|string',
            'email_instructions' => 'required|string',
        ]);

        $clinic = Clinic::findOrFail($clinicId);

        $smsAssistantId = $clinic->sms_assistant;
        $emailAssistantId = $clinic->email_assistant;

        $apiKey = $clinic->chat_api_key;

        $smsInstructions = $validatedData['sms_instructions'];
        $emailInstructions = $validatedData['email_instructions'];

        if ($smsAssistantId) {
            $smsResponse = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->timeout(10) // Set the timeout in seconds (e.g., 10 seconds)
            ->post("https://api.openai.com/v1/assistants/{$smsAssistantId}", [
                'instructions' => $smsInstructions,
            ]);
            if ($smsResponse->failed()) {
                Log::error("Failed to update SMS assistant instructions", [
                    'assistant_id' => $smsAssistantId,
                    'response' => $smsResponse->json(),
                ]);
                throw new \Exception("Failed to update SMS assistant instructions");
            }
        }

        if ($emailAssistantId) {
            $emailResponse = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2',
            ])
            ->timeout(10) // Set the timeout in seconds (e.g., 10 seconds)
            ->post("https://api.openai.com/v1/assistants/{$emailAssistantId}", [
                'instructions' => $emailInstructions,
            ]);
            if ($emailResponse->failed()) {
                Log::error("Failed to update Email assistant instructions", [
                    'assistant_id' => $emailAssistantId,
                    'response' => $emailResponse->json(),
                ]);
                throw new \Exception("Failed to update Email assistant instructions");
            }
        }

        return ['sms_assistant' => $smsAssistantId, 'email_assistant' => $emailAssistantId];
    }
}
