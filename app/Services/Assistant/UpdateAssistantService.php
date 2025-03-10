<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;

class UpdateAssistantService
{
    public function updateAssistantById($assistantId, $data, $apiKey)
    {
        $client = new Client();

        try {
            $response = $client->post("https://api.openai.com/v1/assistants/{$assistantId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2',
                ],
                'json' => [
                    'instructions' => $data['instructions'],
                    'name' => $data['name'],
                ],
                'verify' => false, // Disable SSL verification for this specific request
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
