<?php

namespace App\Http\Controllers;

use App\Models\FbMessage;
use App\Services\FbMessageService;
use Illuminate\Http\Request;

class FacebookMessagesController extends Controller
{
    public function handleFbMessageWebhook(Request $request)
    {


        $clinicId = $request->query('crtx_clinic_id');

        $fbDataJson = file_get_contents('php://input');
        $fbData = json_decode($fbDataJson, true);

        $fbMessageService = new FbMessageService();
        $response = $fbMessageService->saveData($clinicId, $fbData);

        return response()->json($response, $response['status_code']);
    }
}
