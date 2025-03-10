<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmCustomer;
use App\Models\Clinic;
use App\Models\AutomationLog;
use DB;

class AutomationRuleController extends Controller
{
    public function updateAutomationRules()
    {
        // Get the IDs of the active clinics with nurture automation and version 2.0
        $clinicIds = Clinic::where('status', 'Active')
                            ->where('nurture_automation', 'Yes')
                            ->where('version', '2.0')
                            ->pluck('id');

        // Update the automation_campaign field in the clinics table before processing customers
        $clinics = Clinic::whereIn('id', $clinicIds)->get();

        foreach ($clinics as $clinic) {
            if (is_null($clinic->automation_campaign)) {
                $clinic->automation_campaign = json_encode([
                    "1" => "false", "2" => "false", "3" => "false", "4" => "false",
                    "5" => "true", "6" => "false", "7" => "false", "8" => "false",
                    "9" => "false", "10" => "false", "11" => "false", "12" => "false",
                    "13" => "false", "14" => "false", "15" => "false", "16" => "false",
                    "17" => "true"
                ]);
            } else {
                $automation_campaign = json_decode($clinic->automation_campaign, true);
                $automation_campaign['17'] = "true";
                $clinic->automation_campaign = json_encode($automation_campaign);
            }
            $clinic->save();
        }

                            
        // Get the customers based on the specified criteria
        $customers = CrmCustomer::where('automation', 0)
                                ->where('status_id', 17)
                                ->whereIn('clinic_id', $clinicIds)
                                ->orderBy('automation_rule', 'DESC')
                                ->get();

        foreach ($customers as $customer) {           

            $automation_rule = json_decode($customer->automation_rule, true);

            if (is_null($automation_rule)) {
                $automation_rule = [
                    "1" => false, "2" => false, "3" => false, "4" => false,
                    "5" => false, "6" => false, "7" => false, "8" => false,
                    "9" => false, "10" => false, "11" => false, "12" => false,
                    "13" => false, "14" => false, "15" => false, "16" => false,
                    "17" => true
                ];
            } else {
                if (!isset($automation_rule['17']) || $automation_rule['17'] === false) {
                    $automation_rule['17'] = true;
                }
            }

            $customer->automation_rule = json_encode($automation_rule);

                if ($customer->save()) {
                    echo "Record with ID {$customer->id} updated successfully 123.\n";

                     // Insert new entry into automation_logs table for email_sequence
                     if ($customer->email_sequence > 0) {
                        AutomationLog::create([
                            'clinic_id' => $customer->clinic_id,
                            'lead_id' => $customer->id,
                            'status_id' => $customer->status_id,
                            'dayinterval' => $customer->email_sequence, // Set the appropriate dayinterval value
                            'type' => 'email',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                     }

                    // Insert new entry into automation_logs table for sms_sequence
                    if ($customer->sms_sequence > 0) {
                        AutomationLog::create([
                            'clinic_id' => $customer->clinic_id,
                            'lead_id' => $customer->id,
                            'status_id' => $customer->status_id,
                            'dayinterval' => $customer->sms_sequence, // Set the appropriate dayinterval value
                            'type' => 'sms',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }  
                } else {
                    echo "Error updating record with ID {$customer->id}.\n";
                }            
        }
        echo "Update process completed.";
    }
}