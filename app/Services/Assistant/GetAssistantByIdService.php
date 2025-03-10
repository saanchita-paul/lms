<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;

class GetAssistantByIdService
{
    public function getAssistantById($assistantId,$apiKey)
    {
        if (empty($assistantId)) {
            return 'Assistant ID is required';
        }
        $client = new Client();

        try {
            $response = $client->get("https://api.openai.com/v1/assistants/{$assistantId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2',
                ],
                'verify' => false, // Disable SSL verification for this specific request
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
