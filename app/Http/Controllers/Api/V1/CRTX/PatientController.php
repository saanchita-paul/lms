<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SendNotificationTrait;
use App\Http\Resources\CRTX\GetPatientResource;
use App\Models\Appointment;
use App\Models\CrmCustomer;
use App\Models\CrmNote;
use App\Models\Clinic;
use App\Models\ErrorLog;
use App\Models\Setting;
use App\Notifications\LeadMovedToFollowUp;
use App\Traits\ExceptionLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;
use App\Models\AuditLog;
use App\Models\CrmStatus;
use App\Models\Source;


class PatientController extends Controller
{
    use ExceptionLog, SendNotificationTrait;

    public function getPatientProfile(Request $request)
    {
        $customerId = $request->customer_id;

         // Get clinic Id
         $user = Auth::user();
         $userRole = auth()->user()->roles;
        if($userRole[0]['title'] == 'Admin'){
            $clinicId = Clinic::pluck('id')->toArray();
        }else{
            $clinicId = $user->managerClinics()->pluck('id')->toArray();
        }

        try {
            $getPatientProfile = GetPatientResource::collection(
                CrmCustomer::where('id',$customerId)
                ->whereIn('clinic_id', $clinicId) // Add this condition
                ->with(['crmNotes', 'call_details'])->get());

            if ($getPatientProfile->isEmpty()) {
                return response()->json(['message' => 'This Customer Id is not exist'], 200);
            }

            return response()->json(['success' => true,'getPatientProfile' => $getPatientProfile]);
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'GetPatientList');
            return response()->json(['success' => false, 'message' => $ex->getMessage()],500);
        }
    }

    public function updatePatientProfile(Request $request)
    {
        // If it is quick update request, call quickUpdate function instead.
        if($request->has('quick_update_type')){
            return $this->quickUpdate($request);
        }

        $Id = $request->id;
        $updatePatientProfile = CrmCustomer::find($Id);

        $existingEmail = $updatePatientProfile->email;
        $updatePatientProfile->first_name = $request->first_name;
        $updatePatientProfile->last_name = $request->last_name;
        $updatePatientProfile->email = $request->email;
        $updatePatientProfile->dob = $request->dob;
        $updatePatientProfile->address = $request->address;
        $updatePatientProfile->description = $request->description;
        $updatePatientProfile->badge = $request->badge;
        $updatePatientProfile->phone_form = $request->phone_form;
        $updatePatientProfile->three_plus_attempts = $request->three_plus_attempts;
        $updatePatientProfile->city = $request->city;
        $updatePatientProfile->state = $request->state;
        $updatePatientProfile->form_data = $request->form_data;
        $updatePatientProfile->form_id = $request->form_id;
        $updatePatientProfile->convert_to_deal = $request->convert_to_deal;
        $updatePatientProfile->reason = $request->reason;
        $updatePatientProfile->value = $request->value;
        $updatePatientProfile->lifetimevalue = $request->lifetimevalue;
        $updatePatientProfile->patientID = $request->patientID;
        $updatePatientProfile->lastFetchDate = $request->lastFetchDate;
        $updatePatientProfile->ccapture = $request->ccapture;
        $updatePatientProfile->budget = $request->budget;
        $updatePatientProfile->verbal_confirmation = $request->verbal_confirmation;
        $updatePatientProfile->informed_consult_fee = $request->informed_consult_fee;
        $updatePatientProfile->deal_status = $request->deal_status;
        $updatePatientProfile->won_lost = $request->won_lost;
        $updatePatientProfile->lead_type = $request->lead_type;
        $updatePatientProfile->has_sms = $request->has_sms;
        $updatePatientProfile->automation = $request->automation;
        $updatePatientProfile->email_sequence = $request->email_sequence;
        $updatePatientProfile->sms_sequence = $request->sms_sequence;
        $updatePatientProfile->patient_journey_automation = $request->patient_journey_automation;
        $updatePatientProfile->patient_journey_email_sequence = $request->patient_journey_email_sequence;
        $updatePatientProfile->patient_journey_sms_sequence = $request->patient_journey_sms_sequence;
        $updatePatientProfile->next_mail_date = $request->next_mail_date;
        $updatePatientProfile->source_other = $request->source_other;
        $updatePatientProfile->source_id = $request->source_id;
//        $updatePatientProfile->clinic_id = $request->clinic_id;
        $updatePatientProfile->source_id = $request->quicksource;

        $userid = $updatePatientProfile->user;

        $consultation_booked_date = Null;
        $datetime = Null;
        $convert_to_deal = 0;
        $current_consulatation_booked_date =   $updatePatientProfile->consultation_booked_date;
        $current_status = $updatePatientProfile->status_id;

        $status_id = $request->status_id;

        if($request->input('consultation_booked_date')){
            $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y H:i:s A', $request->input('consultation_booked_date'));
            if($request->input('consultation_booked_date') != '' && ($mysqlTimeMeeting->format('m/d/Y H:i:s') != $current_consulatation_booked_date)){
                $status_id = 12;
                $consultation_booked_date = $mysqlTimeMeeting->format('m/d/Y H:i:s');
                $datetime = date('m/d/Y H:i:s');
                $convert_to_deal = 1;
                $updatePatientProfile->status_id = $status_id;
                $updatePatientProfile->consultation_booked_date = $consultation_booked_date;
                $updatePatientProfile->convert_deal_date = $datetime;
                $updatePatientProfile->convert_to_deal = $convert_to_deal;
                $updatePatientProfile->user = $userid;

                if($request->has('availability') && isset($request->availability['id'])){
                    $appointment = Appointment::where('crm_customer_id', $request->id)->whereDate('date', '>=', Carbon::now())->first();

                    if(empty($appointment)){
                        Appointment::create([
                            'crm_customer_id' => $request->id,
                            'appointment_availability_id' => $request->availability['id'],
                            'date' => Carbon::parse($consultation_booked_date)->format('Y-m-d'),
                            'time' => Carbon::parse($consultation_booked_date)->format('H:i:s'),
                            'consent' => 1
                        ]);
                    }else{
                        $appointment->appointment_availability_id = $request->availability['id'];
                        $appointment->date = Carbon::parse($consultation_booked_date)->format('Y-m-d');
                        $appointment->time = Carbon::parse($consultation_booked_date)->format('H:i:s');
                        $appointment->consent = 1;
                        $appointment->save();
                    }
                }
            }
        }
        if($status_id != NUll){
            $updatePatientProfile->status_id = $status_id;
            if($status_id == 12){
                $datetime = date('m/d/Y H:i:s');
                $convert_to_deal = 1;
                $updatePatientProfile->convert_deal_date = $datetime;
                $updatePatientProfile->convert_to_deal = $convert_to_deal;
                $updatePatientProfile->user = $userid;
            }
        }
        if($request->input('status_id') == 13 && $current_status != 13){
            $datetime = date('m/d/Y H:i:s');
            $updatePatientProfile->no_showed_date  = $datetime;
        }

        $updatePatientProfile->won_lost = $request->won_lost;

        if($updatePatientProfile->won_lost == 'Won'){
            $datetime = $request->won_lost_date.' 12:00:00';
            $updatePatientProfile->won_lost_date = $datetime;
        }else{
            $updatePatientProfile->won_lost_date = Null;
        }

        $phoneNumber = $request->phone;

        // Remove hyphens from the phone number
        $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the phone number starts with "+1"
        if (!Str::startsWith($cleanedPhoneNumber, '+1')) {
            $cleanedPhoneNumber = '+1' . $cleanedPhoneNumber;
        }

        $updatePatientProfile->phone = $cleanedPhoneNumber;

        $automationRule = $updatePatientProfile->automation_rule;

        if (!empty($automationRule)) {
            $newAutomationRule = json_decode($automationRule, true);

            foreach ($newAutomationRule as $key => $value) {
                $newAutomationRule[$key] = ($key == $status_id) ? true : false;
            }
            // Encode the updated associative array back to a JSON string
             $updatePatientProfile->automation_rule = json_encode($newAutomationRule);

        }

        //-----------Update Lead Score Logic Start---------------------

        if($updatePatientProfile->phone_form == 'Phone Call'){

            // Get the lead score for patient
            $leadScore = $updatePatientProfile->lead_score;

            // If lead score is null set the lead score to 499
            if(empty($leadScore)){
                $updatePatientProfile->lead_score = random_int(480, 499);
            }

            // For Credit Score

            if($updatePatientProfile->credit_score_above_650 != 1 && $request->credit_score_above_650==1){
                // If credit score is above 650, add random no. between 45-49 to lead score
                $updatePatientProfile->lead_score += random_int(45, 49);
            }

            $updatePatientProfile->credit_score_above_650  = $request->credit_score_above_650;

            // For Email

            $enteredEmail = $request->email;

            if(str_contains($existingEmail, 'noemail.com') && !str_contains($enteredEmail, 'noemail.com')){
                // If email contains 'noemail.com' and entered email does not, add random no. between 45-49 to lead score
                $updatePatientProfile->lead_score += random_int(45, 49);
            }

        }

        if (in_array($request->input('status_id'), [1, 5, 6, 17])) {
            $updatePatientProfile->consultation_booked_date = null;
            $updatePatientProfile->convert_to_deal = null;
            $updatePatientProfile->convert_deal_date = null;
            $updatePatientProfile->won_lost = null;
            $updatePatientProfile->won_lost_date = null;
            $updatePatientProfile->no_showed_date = null;
        }

        $updatePatientProfile->save();

        if($updatePatientProfile->status_id == 6){
            $this->sendLeadMovedToFollowUpNotification($updatePatientProfile->clinic_id, $updatePatientProfile->id);

            if (!empty($updatePatientProfile->clinic_id)) {
                $clinic = Clinic::find($updatePatientProfile->clinic_id);
                // Retrieve users with role id 5
                $users = $clinic->managers()->whereHas('roles', function ($q) {
                    $q->where('id', 5);
                })->get();

                // Find the lead (CrmCustomer)
                $lead = $updatePatientProfile;

                if (!empty($lead)) {
                    foreach ($users as $user) {

                        $setting = Setting::where('user_id', $user->id)->first();
                        if ($setting) {
                            if ($setting->do_not_disturb == 0 && $setting->lead_text_notification == 1) {
                                // Send notification to each user
                                $notification = new LeadMovedToFollowUp($lead);
                                $notification->toTwilio($user,$lead);
                            }
                        } else {
                            // User does not have settings saved, send notifications by default
                            $notification = new LeadMovedToFollowUp($lead);
                            $notification->toTwilio($user,$lead);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Patient profile updated successfully!'
        ], 200);
    }

    private function quickUpdate(Request $request)
    {
        $crm_customer = CrmCustomer::find($request->id);


        $crm_customer->first_name = $request->input('first_name');
        $crm_customer->last_name = $request->input('last_name');



        if($request->quick_update_type == 'consultation'){
            $crm_customer->deal_status = $request->deal_status;
            $crm_customer->value = $request->value;

            $crm_customer->won_lost = $request->won_lost;

            if($crm_customer->won_lost == 'Won'){
                $datetime = $request->won_lost_date.' 12:00:00';
                $crm_customer->won_lost_date = $datetime;
            }else{
                $crm_customer->won_lost_date = Null;
            }
        }

        $userid = $crm_customer->user;

        $consultation_booked_date = Null;
        $datetime = Null;
        $convert_to_deal = 0;
        $current_consulatation_booked_date =   $crm_customer->consultation_booked_date;
        $current_status = $crm_customer->status_id;

        $status_id = $request->status_id;



        if($request->input('consultation_booked_date')){
            $mysqlTimeMeeting = Carbon::createFromFormat('m/d/Y H:i:s A', $request->input('consultation_booked_date'));
            if($request->input('consultation_booked_date') != '' && ($mysqlTimeMeeting->format('m/d/Y H:i:s') != $current_consulatation_booked_date)){
                if (in_array($request->input('status_id'), [1, 5, 6, 17])) {
                    $crm_customer->consultation_booked_date = null;
                    $crm_customer->convert_to_deal = null;
                    $crm_customer->convert_deal_date = null;
                    $crm_customer->won_lost = null;
                    $crm_customer->won_lost_date = null;
                    $crm_customer->no_showed_date = null;
                }
                else
                {
                    $status_id = 12;
                    $consultation_booked_date = $mysqlTimeMeeting->format('m/d/Y H:i:s');
                    $datetime = date('m/d/Y H:i:s');
                    $convert_to_deal = 1;
                    $crm_customer->status_id = $status_id;
                    $crm_customer->consultation_booked_date = $consultation_booked_date;
                    $crm_customer->convert_deal_date = $datetime;
                    $crm_customer->convert_to_deal = $convert_to_deal;
                    $crm_customer->user = $userid;

                    if($request->has('availability') && !empty($request->availability)){
                        $appointment = Appointment::where('crm_customer_id', $request->id)->whereDate('date', '>=', Carbon::now())->first();

                        if(empty($appointment)){
                            Appointment::create([
                                'crm_customer_id' => $request->id,
                                'appointment_availability_id' => $request->availability['id'],
                                'date' => Carbon::parse($consultation_booked_date)->format('Y-m-d'),
                                'time' => Carbon::parse($consultation_booked_date)->format('H:i:s'),
                                'consent' => 1
                            ]);
                        }else{
                            $appointment->appointment_availability_id = $request->availability['id'];
                            $appointment->date = Carbon::parse($consultation_booked_date)->format('Y-m-d');
                            $appointment->time = Carbon::parse($consultation_booked_date)->format('H:i:s');
                            $appointment->consent = 1;
                            $appointment->save();
                        }
                    }
                }

            }
        }

        if($status_id != NUll){
            $crm_customer->status_id = $status_id;
            if($status_id == 12){
                $datetime = date('m/d/Y H:i:s');
                $convert_to_deal = 1;
                $crm_customer->convert_deal_date = $datetime;
                $crm_customer->convert_to_deal = $convert_to_deal;
                $crm_customer->user = $userid;
            }else if ($status_id == 9){
                $crm_customer->reason = $request->input('reason');
            }
        }
        if($request->input('status_id') == 13 && $current_status != 13){
            $datetime = date('m/d/Y H:i:s');
            $crm_customer->no_showed_date  = $datetime;
        }

        if (in_array($request->input('status_id'), [1, 5, 6, 17])) {
            $crm_customer->consultation_booked_date = null;
            $crm_customer->convert_to_deal = null;
            $crm_customer->convert_deal_date = null;
            $crm_customer->won_lost = null;
            $crm_customer->won_lost_date = null;
            $crm_customer->no_showed_date = null;
        }

        $crm_customer->save();

        if($crm_customer->status_id == 6){
            $this->sendLeadMovedToFollowUpNotification($crm_customer->clinic_id, $crm_customer->id);

            if (!empty($crm_customer->clinic_id)) {
                $clinic = Clinic::find($crm_customer->clinic_id);
                // Retrieve users with role id 5
                $users = $clinic->managers()->whereHas('roles', function ($q) {
                    $q->where('id', 5);
                })->get();

                // Find the lead (CrmCustomer)
                $lead = $crm_customer;

                if (!empty($lead)) {
                    foreach ($users as $user) {

                        $setting = Setting::where('user_id', $user->id)->first();
                        if ($setting) {
                            if ($setting->do_not_disturb == 0 && $setting->follow_up_text_notification == 1) {
                                // Send notification to each user
                                $notification = new LeadMovedToFollowUp($lead);
                                $notification->toTwilio($user,$lead);
                            }
                        } else {
                            // User does not have settings saved, send notifications by default
                            $notification = new LeadMovedToFollowUp($lead);
                            $notification->toTwilio($user,$lead);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully!'
        ], 200);

    }
    public function getFieldUpdates($subjectId)
    {

        // Define a mapping for field names to display names
    $fieldDisplayNames = [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'dob' => 'Date of Birth',
        'address' => 'Address',
        'zipcode' => 'Zip Code',
        'description' => 'Description',
        'badge' => 'Badge',
        'phone_form' => 'Phone Form',
        'three_plus_attempts' => 'Attempts',
        'city' => 'City',
        'state' => 'State',
        'consultation_booked_date' => 'Consultation Booked Date',
        'no_showed_date' => 'No Showed Date',
        'convert_to_deal' => 'Convert to Deal',
        'convert_deal_date' => 'Convert Deal Date',
        'reason' => 'Reason',
        'value' => 'Treatment Cost',
        'budget' => 'Budget',
        'verbal_confirmation' => 'Verbal Confirmation',
        'informed_consult_fee' => 'Informed Consult Fee',
        'source_other' => 'Source Other',
        'status_id' => 'Status',
        'source_id' => 'Source',
        'clinic_id' => 'Clinic',
        'phone_verified' => 'Phone Verified',
        'email_verified' => 'Email Verified',
        'credit_score_above_650' => 'Credit Score Above 650',
        'deal_status' => 'Consultation Follow Up',
        'won_lost' => 'Treatment Sold',
        'won_lost_date' => 'Sold On Date',
    ];

    // Fetch status descriptions from the crm_statuses table
    $statusDescriptions = CrmStatus::whereNull('deleted_at')
                                    ->pluck('name', 'id')
                                    ->toArray();

    // Fetch source descriptions from the sources table
    $sourceDescriptions = Source::whereNull('deleted_at')
                                 ->pluck('source_name', 'id')
                                 ->toArray();

    /*$auditLogs = AuditLog::where('subject_id', $subjectId)
                         ->where('description', 'updated')
                         ->orderBy('created_at', 'asc')
                         ->with('user') // Eager load the user relationship
                         ->get();*/
    $auditLogs = [];
    $latestChanges = [];
    $differences = [];

    // Iterate through the records in pairs
    for ($i = 0; $i < count($auditLogs) - 1; $i++) {
        $currentLog = $auditLogs[$i];
        $nextLog = $auditLogs[$i + 1];

        $currentProperties = json_decode($currentLog->properties, true);
        $nextProperties = json_decode($nextLog->properties, true);

        foreach ($currentProperties as $field => $value) {
            // Skip the created_at and updated_at fields
            // if (in_array($field, ['created_at', 'updated_at','convert_to_deal'])) {
            //     continue;
            // }

            // Allow only specific fields
            $allowedFields = [
                'first_name',
                'last_name',
                'email',
                'phone',
                'address',
                'dob',
                'city',
                'state',
                'zipcode',
                'description',
                'badge',
                'phone_form',
                'consultation_booked_date',
                'no_showed_date',
                'three_plus_attempts',
                'won_lost',
                'won_lost_date',
                'reason',
                'value',
                'budget',
                'source_id',
                'status_id',
                'verbal_confirmation',
                'informed_consult_fee',
                'credit_score_above_650',
                // Add more allowed fields as needed
            ];



            if (!in_array($field, $allowedFields)) {
                continue;
            }

            // Convert status_id to its description
            if ($field === 'status_id') {
                $value = $statusDescriptions[$value] ?? $value;
                $nextProperties[$field] = $statusDescriptions[$nextProperties[$field]] ?? $nextProperties[$field];
            }

            // Convert source_id to its description
            if ($field === 'source_id') {
                $value = $sourceDescriptions[$value] ?? $value;
                $nextProperties[$field] = $sourceDescriptions[$nextProperties[$field]] ?? $nextProperties[$field];
            }

            // Check for changes in the field's value
            if (array_key_exists($field, $nextProperties) && $nextProperties[$field] !== $value) {
                // Skip displaying "Attempts" if the new value is 0
                if ($field === 'three_plus_attempts' && $nextProperties[$field] == 0) {
                    continue;
                }

                // Handle the credit_score_above_650 field
                if ($field === 'credit_score_above_650') {

                    $value = is_null($value) ? 'Not Available' : ($value == 1 ? 'Yes' : 'No');
$nextProperties[$field] = is_null($nextProperties[$field]) ? 'Not Available' : ($nextProperties[$field] == 1 ? 'Yes' : 'No');
                }

                $differences[] = [
                    'field' => $fieldDisplayNames[$field] ?? $field,
                    'old_value' => $value,
                    'new_value' => $nextProperties[$field],
                    'updated_at' => $nextLog->created_at,
                    'updated_by' => $nextLog->user ? $nextLog->user->name : 'Unknown', // Include the user name
                ];

                // Track the latest change for each field
                $latestChanges[$field] = [
                    'old_value' => $value,
                    'new_value' => $nextProperties[$field],
                    'updated_at' => $nextLog->created_at,
                    'updated_by' => $nextLog->user ? $nextLog->user->name : 'Unknown', // Include the user name
                ];
            }
        }
    }

    // Get unique and latest changes
    $uniqueLatestChanges = [];
    foreach ($latestChanges as $field => $change) {
        $uniqueLatestChanges[] = [
            'field' => $fieldDisplayNames[$field] ?? $field,
            'old_value' => $change['old_value'],
            'new_value' => $change['new_value'],
            'updated_at' => $change['updated_at'],
            'updated_by' => $change['updated_by'], // Include the user name
        ];
    }

    return response()->json($uniqueLatestChanges);
    }



}
