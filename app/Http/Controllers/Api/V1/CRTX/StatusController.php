<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\StatusDropDownResource;
use App\Models\CrmStatus;
use App\Models\ErrorLog;
use App\Traits\ExceptionLog;

class StatusController extends Controller
{
    use ExceptionLog;
    public function getAllStatus()
    {
        try {
            $statuses = CrmStatus::get();
            $customOrder = [
                'New Lead' => 1,
                'In Discussion' => 2,
                'Attempt One' => 3,
                'Attempt Two' => 4,
                'Attempt Three Plus' => 5,
                'Nurturing (Only FORMS)' => 6,
                'Practice Follow-Up' => 7,
                'Nurturing' => 8,
            ];

            $statuses = $statuses->sortBy(function ($status) use ($customOrder) {
                return $customOrder[$status->name] ?? PHP_INT_MAX;
            });

            return StatusDropDownResource::collection($statuses)->additional(['success' => true]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'getAllStatusList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }
}
