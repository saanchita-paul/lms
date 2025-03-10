<?php

namespace App\Services\Assistant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GetInstructionService
{
    public function getAssistantIds($clinicId)
    {
        return DB::table('clinics')
            ->where('id', $clinicId)
            ->select('sms_assistant', 'email_assistant','chat_api_key')
            ->first();
    }

    public function fetchAssistantDetails($assistantId,$apiKey)
    {
       $endpoint = "https://api.openai.com/v1/assistants/{$assistantId}";

       $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$apiKey}",
            'OpenAI-Beta' => 'assistants=v2',
        ])->timeout(10) // Set the timeout in seconds (e.g., 10 seconds)
        ->get($endpoint);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch assistant details from OpenAI.');
        }

        return $response->json();
    }

    public function getInstructions($clinicId)
    {
        $assistantIds = $this->getAssistantIds($clinicId);

        if (!$assistantIds) {
            throw new \Exception('Clinic not found.');
        }

        $emailInstructions = null;
        $smsInstructions = null;
        $apiKey = $assistantIds->chat_api_key;

        if (!$apiKey) {
            throw new \Exception('API key not found for this clinic.');
        }

        if ($assistantIds->email_assistant) {
            $emailResponse = $this->fetchAssistantDetails($assistantIds->email_assistant,$apiKey);
            $emailInstructions = $emailResponse['instructions'] ?? null;
        }

        if ($assistantIds->sms_assistant) {
            $smsResponse = $this->fetchAssistantDetails($assistantIds->sms_assistant,$apiKey);
            $smsInstructions = $smsResponse['instructions'] ?? null;
        }

        return [
            'email_instructions' => $emailInstructions,
            'sms_instructions' => $smsInstructions,
        ];
    }
}
