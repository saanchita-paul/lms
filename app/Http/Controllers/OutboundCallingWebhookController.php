<?php

namespace App\Http\Controllers;

use App\Models\CrmCustomer;
use App\Services\OutboundCallingService;
use Illuminate\Http\Request;


class OutboundCallingWebhookController extends Controller
{

    public function handleOutboundCallingWebhook(Request $request)
    {
        $getOutboundCallData = new OutboundCallingService();
        $response = $getOutboundCallData->handleOutboundCallingWebhook($request);

        return response()->json([
            'data' => $response['data'] ?? null,
            'message' => $response['message'],
        ], $response['status']);
    }
}
