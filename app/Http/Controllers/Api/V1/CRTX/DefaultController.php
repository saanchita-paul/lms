<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\getSourcesResource;
use App\Models\Source;
use Illuminate\Http\Request;
use App\Models\ErrorLog;
use App\Traits\ExceptionLog;

class DefaultController extends Controller
{
    use ExceptionLog;

    public function getSources(Request $request)
    {
        try {
            $getSources = getSourcesResource::collection(Source::get());
            return response()->json(['success' => true,'getSources' => $getSources]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }
}
