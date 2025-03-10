<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Services\Assistant\CreateAssistantService;
use App\Services\Assistant\CreateVectorStoreFileService;
use App\Services\Assistant\DeleteVectorStoreFileService;
use App\Services\Assistant\DownloadVectorStoreFileService;
use App\Services\Assistant\GetAssistantByIdService;
use App\Services\Assistant\GetInstructionService;
use App\Services\Assistant\ListVectorStoreFilesService;
use App\Services\Assistant\RetrieveAssistantWithVectorIdService;
use App\Services\Assistant\UpdateAssistantInstructionService;
use App\Services\Assistant\UpdateAssistantService;
use App\Services\Assistant\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;

class AssistantFileController extends Controller
{

    protected CreateAssistantService $CreateAssistantService;
    protected GetAssistantByIdService $getAssistantByIdService;
    protected UploadFileService $uploadFileService;
    protected CreateVectorStoreFileService $createVectorStoreFileService;
    protected DeleteVectorStoreFileService $deleteVectorStoreFileService;
    protected UpdateAssistantService $updateAssistantService;

    public function __construct(
        CreateAssistantService $CreateAssistantService,
        GetAssistantByIdService $getAssistantByIdService,
        UploadFileService $uploadFileService,
        CreateVectorStoreFileService $createVectorStoreFileService,
        DeleteVectorStoreFileService $deleteVectorStoreFileService,
        UpdateAssistantService $updateAssistantService

    )
    {
        $this->CreateAssistantService = $CreateAssistantService;
        $this->getAssistantByIdService = $getAssistantByIdService;
        $this->uploadFileService = $uploadFileService;
        $this->createVectorStoreFileService = $createVectorStoreFileService;
        $this->deleteVectorStoreFileService = $deleteVectorStoreFileService;
        $this->updateAssistantService = $updateAssistantService;
    }


    public function createAssistant(Request $request)
    {
        $assistantId = $this->CreateAssistantService->createAssistant($request);
        return response($assistantId, 201);
    }

    public function getAssistantById($assistantId)
    {
       // Retrieve the clinic record associated with this assistant
        $clinic = Clinic::find($assistantId);

       

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found for the provided assistant ID'], 404);
        }

        // Get the chat_api_key from the clinic
        $apiKey = $clinic->chat_api_key;

        $assistantId = $clinic->assistant_id;

        if (!$assistantId) {
            return response()->json(['error' => 'No assistant ID associated with this clinic'], 404);
        }

        $result = $this->getAssistantByIdService->getAssistantById($assistantId,$apiKey);
        return response()->json($result);
    }

    public function uploadFile(Request $request)
    {


         
         $request->validate([
            'clinicId' => 'required|exists:clinics,id',            
        ]);

        // Retrieve clinic data
        $clinic = Clinic::find($request->clinicId);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        // Get the API key from the clinic
        $apiKey = $clinic->chat_api_key;



        $assistantId = $request->assistantId;
        $fileId = $this->uploadFileService->uploadFile($request);
        


        $assistant = new RetrieveAssistantWithVectorIdService();
        $vectorStoreId = $assistant->getAssistant($assistantId,$apiKey);
        $this->createVectorStoreFileService->createVectorStoreFile($fileId, $vectorStoreId,$apiKey);
        $this->uploadFileService->saveFileToLocalStorage($request);

        return response()->json([
            'data' => $fileId,
            'message' => 'File uploaded successfully to assistant and saved locally.',
        ], 200);

    }


    public function getFiles($assistantId)
    {

        // Retrieve the clinic record associated with this assistant
        $clinic = Clinic::where('assistant_id', $assistantId)->first();

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found for the provided assistant ID'], 404);
        }

        // Get the chat_api_key from the clinic
        $apiKey = $clinic->chat_api_key;

        if (empty($apiKey)) {
            return response()->json(['error' => 'API key is missing for this clinic'], 400);
        }

        $assistant = new RetrieveAssistantWithVectorIdService();
        $vectorStoreId = $assistant->getAssistant($assistantId,$apiKey);





        $getFilesService = new ListVectorStoreFilesService();
        $result = $getFilesService->listVectorStoreFiles($vectorStoreId,$apiKey);


        return response()->json($result);
    }

    public function deleteFile($clinicId, $assistantId, $fileId, $fileName)
    {
        // Retrieve the clinic record associated with this assistant
        $clinic = Clinic::where('assistant_id', $assistantId)->first();

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found for the provided assistant ID'], 404);
        }

        // Get the chat_api_key from the clinic
        $apiKey = $clinic->chat_api_key;

        if (empty($apiKey)) {
            return response()->json(['error' => 'API key is missing for this clinic'], 400);
        }
        $assistant = new RetrieveAssistantWithVectorIdService();
        $vectorStoreId = $assistant->getAssistant($assistantId,$apiKey);
        $response = $this->deleteVectorStoreFileService->deleteSingleFile($vectorStoreId, $fileId, $fileName, $clinicId,$apiKey);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json(['success' => true, 'message' => $response['message']]);
    }

    public function deleteAllVectorStoreFiles($clinicId, $assistantId)
    {
        // Retrieve clinic data
        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        // Get the API key from the clinic
        $apiKey = $clinic->chat_api_key;

        $assistant = new RetrieveAssistantWithVectorIdService();
        $vectorStoreId = $assistant->getAssistant($assistantId,$apiKey);
        $result = $this->deleteVectorStoreFileService->deleteAllVectorStoreFiles($vectorStoreId, $clinicId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }
        return response()->json(['success' => true, 'message' => $result['message']]);
    }

    public function downloadFile($clinicId, $assistantId, $fileName)
    {
        $filePath = "public/crtxagent/{$clinicId}/{$fileName}";

        if (Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function updateAssistant(Request $request, $assistantId)
    {
        $request->validate([
            'instructions' => 'nullable|string',
            'name' => 'nullable|string',
        ]);
        $data = $request->only(['instructions','name']);
        // Retrieve the clinic record associated with this assistant
        $clinic = Clinic::where('assistant_id', $assistantId)->first();

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found for the provided assistant ID'], 404);
        }

        // Get the chat_api_key from the clinic
        $apiKey = $clinic->chat_api_key;

        if (empty($apiKey)) {
            return response()->json(['error' => 'API key is missing for this clinic'], 400);
        }
        $result = $this->updateAssistantService->updateAssistantById($assistantId, $data, $apiKey);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json([
            'message' => 'Assistant updated successfully',
            'data' => $result
        ]);
    }

    public function updateEmailSmsInstructions($clinicId, Request $request)
    {
        try {
            $updateInstructions = new UpdateAssistantInstructionService();
             $result = $updateInstructions->updateInstructions($clinicId, $request);
            return response()->json([
                'message' => 'Instructions updated successfully.',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function fetchInstructions($clinicId)
    {
        try {
            $getInstruction = new GetInstructionService();
            $instructions = $getInstruction->getInstructions($clinicId);

            return response()->json(['data' => $instructions], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
