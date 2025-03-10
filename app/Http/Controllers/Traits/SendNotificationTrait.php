<?php

namespace App\Http\Controllers\Traits;

use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AppointmentScheduled;
use App\Notifications\GreenLeadCreated;
use App\Notifications\LeadMovedToFollowUp;
use App\Notifications\LeadReconnecting;
use App\Notifications\WherebyKnockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use DB;
use Log;

trait SendNotificationTrait
{

    private function sendLeadMovedToFollowUpNotification($clinic_id, $lead_id)
    {
        $clinic = Clinic::find($clinic_id);

        if(!empty($clinic)){

            $users = $clinic->managers()->whereHas('roles', function($q){
                $q->where('id', 5);
            })->get();


            $lead = CrmCustomer::find($lead_id);

            Notification::send($users, new LeadMovedToFollowUp($lead));
        }
    }

    private function sendLeadReconnectingNotification($clinic_id, $lead_id)
    {
        $clinic = Clinic::find($clinic_id);

        if(!empty($clinic)){

            $users = $clinic->managers()->whereHas('roles', function($q){
                $q->where('id', 5);
            })->get();


            $lead = CrmCustomer::find($lead_id);

//            Notification::send($users, new LeadReconnecting($lead));
        }
    }

    private function sendAppointmentScheduledNotification($clinic_id, $lead_id, $appointmentdata)
    {
        $clinic = Clinic::find($clinic_id);

        if(!empty($clinic)){
            $users = $clinic->managers()->whereHas('roles', function($q){
                $q->where('id', 5);
            })->get();

            $lead = CrmCustomer::find($lead_id);

            Notification::send($users, new AppointmentScheduled($lead,$appointmentdata));
        }
    }


    public function sendKnockNotification($roomName, $displayName)
    {
        $clinic = Clinic::where('whereby_link', 'like', '%' . $roomName . '%')->first();


        if(!empty($clinic)){
            $users = $clinic->managers()->whereHas('roles', function($q){
                $q->where('id', 5);
            })->get();

            $notificationData = [
                'clinicName' => $clinic->name,
                'roomName' => $roomName,
                'clinicId' => $clinic->id,
                'displayName' => $displayName,
            ];
            Notification::send($users, new WherebyKnockNotification($notificationData));
        }
    }

}
