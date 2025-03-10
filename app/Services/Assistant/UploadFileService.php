<?php

namespace App\Services\Assistant;



use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;

class UploadFileService
{
    public function uploadFile(Request $request)
    {

        
        $request->validate([
            'file' => 'required|file|mimes:txt,pdf,docx,json',
        ], [
            'file.mimes' => 'The file must be a file of type: txt, pdf, docx, json.',
            'file.max' => 'The file may not be greater than 2MB.'
        ]);

       

        $client = new Client([
            'verify' => false, // Disable SSL certificate verification
        ]);

         // Retrieve clinic data
        $clinic = Clinic::find($request->clinicId);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

       $apiKey = $clinic->chat_api_key;

        
    
       

        $response = $client->post('https://api.openai.com/v1/files', [
            'headers' => ['Authorization' => 'Bearer ' . $apiKey],  
            'timeout' => 10, // Set the timeout in seconds         
            'multipart' => [
                [
                    'name'     => 'purpose',
                    'contents' => 'assistants'
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($request->file('file')->getRealPath(), 'r'),
                    'filename' => $request->file('file')->getClientOriginalName()
                ]
            ]            
        ]);



        

        $fileData = json_decode($response->getBody(), true);
        return $fileData['id'];
    }

    public function saveFileToLocalStorage(Request $request)
    {
        $clinicId = $request->input('clinicId');
        $fileName = $request->file('file')->getClientOriginalName();
        $filePath = "public/crtxagent/{$clinicId}";
        $path = Storage::putFileAs($filePath, $request->file('file'), $fileName);

        return $path;
    }
}
