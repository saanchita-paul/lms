<?php

namespace App\Services\Assistant;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CreateVectorStoreFileService
{
    public function createVectorStoreFile($fileId, $vectorStoreId,$apiKey)
    {
        $client = new Client();
       // $apiKey = env('OPENAI_API_KEY');



        $response = $client->post('https://api.openai.com/v1/vector_stores/' . $vectorStoreId . '/files', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'json' => ['file_id' => $fileId],
            'verify' => false, // Disable SSL verification for this specific request
            'timeout' => 10,
        ]);

       
        $vectorStoreFileData = json_decode($response->getBody(), true);
        return $vectorStoreFileData;
    }
}
