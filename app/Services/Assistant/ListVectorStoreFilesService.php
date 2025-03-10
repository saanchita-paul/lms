<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;

class ListVectorStoreFilesService
{
    public function listVectorStoreFiles($vectorStoreId,$apiKey)
    {
        $client = new Client();
        //$apiKey = env('OPENAI_API_KEY');


        $response = $client->get("https://api.openai.com/v1/vector_stores/{$vectorStoreId}/files?filter=completed", [
            'headers' => [
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'verify' => false, // Disable SSL verification for this specific request
            'timeout' => 10,
        ]);



        $responseData = json_decode($response->getBody(), true);

       

        if (isset($responseData['error'])) {
            return ['error' => $responseData['error']['message']];
        }

        $files = $responseData['data'] ?? [];


        foreach ($files as &$file) {
            $file['file_name'] = $this->getFileName($file['id'],$apiKey);
        }

       

        return $files;
    }

    public function getFileName($fileId,$apiKey)
    {
        $client = new Client();

        $response = $client->get("https://api.openai.com/v1/files/{$fileId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'verify' => false, // Disable SSL verification for this specific request
        ]);

        $fileData = json_decode($response->getBody(), true);

        if (isset($fileData['error'])) {
            return null;
        }

        return $fileData['filename'] ?? null;
    }

}
