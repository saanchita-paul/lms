<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentServiceRequest;
use App\Models\Services;
use Illuminate\Http\Request;

class AppointmentServiceController extends Controller
{
    public function create(CreateAppointmentServiceRequest $request)
    {
        $service = Services::create([
            'name' => $request->name,
            'clinic_id' => $request->clinic_id,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service created successfully!',
        ]);
    }
}
