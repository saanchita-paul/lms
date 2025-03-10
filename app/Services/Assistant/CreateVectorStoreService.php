<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;

class CreateVectorStoreService
{
    public function createVectorStore($apiKey)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/vector_stores', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'json' => ['name' => 'Microsite'],
            'verify' => false, // Disable SSL verification for this specific request
            'timeout' => 10,
        ]);
        $vectorStoreData = json_decode($response->getBody(), true);
        return $vectorStoreData['id'];
    }
}
