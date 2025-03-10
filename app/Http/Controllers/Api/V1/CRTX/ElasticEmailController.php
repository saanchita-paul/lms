<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Services\ElasticEmailService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateElasticEmailAccountRequest;

class ElasticEmailController extends Controller
{
    protected $elasticEmailService;

    public function __construct(ElasticEmailService $elasticEmailService)
    {
        $this->elasticEmailService = $elasticEmailService;
    }

    public function createAccountWithSMTP(CreateElasticEmailAccountRequest $request)
    {
        try {
            $result = $this->elasticEmailService->createAccountWithSMTP($request->validated());
            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
