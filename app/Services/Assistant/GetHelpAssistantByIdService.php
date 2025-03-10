<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;

class GetHelpAssistantByIdService
{
    public function getHelpAssistantById()
    {
        $assistantId = env('HELP_CHAT_ASSISTANT_ID');

        if (empty($assistantId)) {
            return 'Assistant ID is required';
        }
        $client = new Client();
        $apiKey = env('OPENAI_API_KEY');

        try {
            $response = $client->get("https://api.openai.com/v1/assistants/{$assistantId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2',
                ],
                'verify' => false,
                'timeout' => 10,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['error'])) {
                return ['error' => $responseData['error']['message']];
            }

            return $responseData;
        } catch (\Exception $e) {
            return ['error' => 'Error retrieving assistant details: ' . $e->getMessage()];
        }
    }
}
