<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\WherebyData;

class WherebyService
{
    public function processWebhook(array $whereby)
    {
        $roomName = $whereby['data']['roomName'] ?? null;

        $wherebyDataJson = json_encode($whereby);

        if ($roomName) {
            $clinic = Clinic::where('whereby_link', 'like', '%' . $roomName . '%')->first();

            if ($clinic) {
//                $wherebyData = WherebyData::where('clinic_id', $clinic->id)->first();

//                if (!$wherebyData) {
                    $wherebyData = WherebyData::create([
                        'json_data' => $wherebyDataJson,
                        'clinic_id' => $clinic->id,
                        'status' => 1,
                    ]);
//                }

                return $wherebyData;
            }
        }

        return null;
    }
}
