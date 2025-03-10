<?php

namespace App\Services\Assistant;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteVectorStoreFileService
{
    public function deleteSingleFile($vectorStoreId, $fileId, $fileName, $clinicId,$apiKey)
    {
        $client = new Client();
        //$apiKey = env('OPENAI_API_KEY');


        $response = $client->delete("https://api.openai.com/v1/vector_stores/{$vectorStoreId}/files/{$fileId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'verify' => false, // Disable SSL verification for this specific request
        ]);


        $responseData = json_decode($response->getBody(), true);

        if (isset($responseData['error'])) {
            return ['error' => $responseData['error']['message']];
        }

        $filePath = "public/crtxagent/{$clinicId}/{$fileName}";
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        return ['success' => true, 'message' => 'File deleted successfully'];
    }


    public function deleteAllVectorStoreFiles($vectorStoreId, $clinicId)
    {
        $client = new Client();

        // Retrieve clinic data
        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        // Get the API key from the clinic
        $apiKey = $clinic->chat_api_key;
       

        $response = $client->get("https://api.openai.com/v1/vector_stores/{$vectorStoreId}/files?filter=completed", [
            'headers' => [
                'Authorization' => "Bearer $apiKey",
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

        $files = $responseData['data'] ?? [];

        if (empty($files)) {
            return ['success' => true, 'message' => 'No files to delete'];
        }


        foreach ($files as $file) {
            $fileId = $file['id'];
            $fileName = $file['file_name'];
            $this->deleteSingleFile($vectorStoreId, $fileId,  $fileName, $clinicId);

            $filePath = "crtxagent/{$clinicId}/{$fileName}";

            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        return ['success' => true, 'message' => 'All files deleted successfully'];
    }
}
