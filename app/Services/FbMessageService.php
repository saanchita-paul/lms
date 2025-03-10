<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\FbMessage;

class FbMessageService
{
    public function saveData($clinicId, $fbData)
    {

        $webhookData = new FbMessage();
        $webhookData->fb_data = json_encode($fbData);
        $webhookData->save();

        $savedData = json_decode($webhookData->fb_data, true);
        $this->saveLead($clinicId , $fbData , $webhookData);
        return [
            'status_code' => 200,
            'status' => 'success',
            'fb_data' => $savedData,
            'message' => 'Facebook Messages Webhook handled successfully'
        ];
    }

    public function saveLead($clinicId , $fbData , $webhookData)
    {
        $leadId = null;
        $converted = 0;

        if (isset($fbData['first_name']) && isset($fbData['last_name'])) {

            $crmCustomer = CrmCustomer::where('first_name', $fbData['first_name'])
                ->where('last_name', $fbData['last_name'])
                ->where('clinic_id', $clinicId)
                ->first();
            if ($crmCustomer) {

                $leadId = $crmCustomer->clinic_id;
                $converted = 1;
            }
        }

        FbMessage::where('id', $webhookData->id)->update([
            'lead_id' => $leadId,
            'converted' => $converted,
        ]);
    }
}
