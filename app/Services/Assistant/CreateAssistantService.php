<?php

namespace App\Services\Assistant;


use App\Models\Clinic;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Make sure to import the Clinic model

class CreateAssistantService
{

    public function createAssistant(Request $request)
    {
        // Validate that clinic_id is provided in the request
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id'
        ]);

        // Retrieve clinic data
        $clinic = Clinic::find($request->clinic_id);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        // Extract clinic_name and website from the retrieved clinic
        $clinicName = $clinic->clinic_name;
        $website = $clinic->website;
        $apiKey = $clinic->chat_api_key;        

        // Create the vector store using the project ID if needed
        $client = new Client();
        $apiKey = $clinic->chat_api_key;
        $vectorStoreService = new CreateVectorStoreService();
        $vectorStoreId = $vectorStoreService->createVectorStore($apiKey);

        // Normal Assistant
        $normalAssistantId = $this->createSpecificAssistant($client, $apiKey, $vectorStoreId, $clinicName, $website, 'normal');

        // SMS Assistant
        $smsAssistantId = $this->createSpecificAssistant($client, $apiKey, $vectorStoreId, $clinicName, $website, 'sms');

        // Email Assistant
        $emailAssistantId = $this->createSpecificAssistant($client, $apiKey, $vectorStoreId, $clinicName, $website, 'email');

        // Save the assistants to the database
        $clinic->assistant_id = $normalAssistantId;
        $clinic->sms_assistant = $smsAssistantId;
        $clinic->email_assistant = $emailAssistantId;
        $clinic->save();

        return $normalAssistantId;
    }


    private function createSpecificAssistant($client, $apiKey, $vectorStoreId, $clinicName, $website, $type)
    {
        // Define specific instructions for each assistant type
        $instructions = '';
        switch ($type) {
            case 'sms':
                $instructions = "You are a SMS assistant designed to handle inquiries sent via SMS for " . $website . ". Your primary goal is to assist users by providing short and precise responses to questions. Keep maximum 160 characters limit. Do not add file reference at end the end of response.Temperature is always 0.5";               
                break;
            case 'email':
                $instructions = "You are a Email assistant designed to handle inquiries sent via mail for " . $website . ". Provide professional and detailed responses, ensuring that the information is concise and helpful. Do not add file reference at end the end of response.Temperature is always 0.5";
                break;
            default:
                $instructions =  'You are a specialized chatbot designed exclusively for marketing websites, focusing on providing detailed information about a specific services from '.$website.' Your primary goal is to assist website visitors by answering their queries related to  the  services, and general knowledge based on the documents provided for training and content.Capabilities and Limitations:
                    Knowledge Base: You are to reference only the documents uploaded for your training and the content available on specific documents. This includes information about services offered, the qualifications and expertise, office hours, and other inquiries.
                    Privacy Protection: Under no circumstances should you collect personal information from the users. Your interactions should maintain a high level of privacy and security.
                    Try to keep the answers short. Do not write references. Temperature is always 0.5 dont add file reference at end the end of response.';
                break;
        }

       
        $client = new Client();

        $name = $type === "normal" ? $clinicName . " Assistant" : $clinicName . " " . ucfirst($type) . " Assistant";

        $response = $client->post('https://api.openai.com/v1/assistants', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
                'OpenAI-Beta' => 'assistants=v2'
            ],
            'json' => [
                'instructions' => $instructions,
                'tools' => [['type' => 'file_search']],
                'tool_resources' => ['file_search' => ['vector_store_ids' => [$vectorStoreId]]],
                'model' => 'gpt-4o-mini',
                'name' => $name,
                'temperature' => 0.5
            ],
            'verify' => false, // Disable SSL verification for this specific request
            'timeout' => 10,
        ]);

        $assistantData = json_decode($response->getBody(), true);
        $assistantId = $assistantData['id'];       

        return $assistantId;
    }

}
