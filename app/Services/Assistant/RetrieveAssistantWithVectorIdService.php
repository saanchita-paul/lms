<?php

namespace App\Services\Assistant;



use App\Models\Clinic;
use App\Services\Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RetrieveAssistantWithVectorIdService
{
    public function getAssistant($assistantId,$apiKey)
    {
        try {
            $client = new Client();
           // $apiKey = env('OPENAI_API_KEY');

            $response = $client->get('https://api.openai.com/v1/assistants/'.$assistantId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2'
                ],
                'verify' => false, // Disable SSL verification for this specific request
                'timeout' => 10,
            ]);

            $assistant = json_decode($response->getBody(), true);
            if(count($assistant['tool_resources']['file_search']['vector_store_ids']) > 0){
                return  $assistant['tool_resources']['file_search']['vector_store_ids'][0];
            }
            return null;
        }catch (Exception $e){
            return null;
        }

    }
}
