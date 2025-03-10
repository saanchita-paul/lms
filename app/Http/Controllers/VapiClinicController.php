<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VapiClinicController extends Controller
{
    // Fetch Vapi Columns by Clinic ID
    public function show($clinic_id) {
        $clinic = Clinic::where('id', $clinic_id)->first();

        if (!$clinic) {
            return response()->json(['message' => 'Clinic not found'], 404);
        }

        return response()->json([
            'vapi_assistant_id' => $clinic->vapi_assistant_id,
            'vapi_phone_number_id' => $clinic->vapi_phone_number_id,
        ]);
    }

    // Save or Update Vapi Columns
    public function storeOrUpdate(Request $request, $clinic_id) {
        $request->validate([
            'vapi_assistant_id' => 'required|string',
            'vapi_phone_number_id' => 'required|string',
        ]);

        $clinic = Clinic::where('id', $clinic_id)->first();

        if (!$clinic) {
            return response()->json(['message' => 'Clinic not found'], 404);
        }

        $clinic->update([
            'vapi_assistant_id' => $request->vapi_assistant_id,
            'vapi_phone_number_id' => $request->vapi_phone_number_id,
        ]);

        return response()->json(['message' => 'Saved successfully!']);
    }
}
