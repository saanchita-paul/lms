<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SendNotificationTrait;
use App\Http\Resources\CRTX\ClinicResource;
use App\Mail\AppointmentEmail;
use App\Mail\WelcomeEmail;
use App\Models\Appointment;
use App\Models\AppointmentAvailability;
use App\Models\AppointmentUnavailability;
use App\Models\Clinic;
use App\Models\CrmCustomer;
use App\Models\FixedAppointmentAvailability;
use App\Models\GlobalData;
use App\Models\Services;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Psy\Readline\Hoa\Console;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Models\Tag;
use App\Models\TagLeadMapping;
use App\Notifications\AppointmentScheduled;
use App\Models\Setting;
use App\Services\TemplateService;

class AppointmentController extends Controller
{
    use HasMediaTrait;
    use SendNotificationTrait;





    public function index($id)
    {
        return view('pages.appointment',$id);
    }
    public function get_env_data()
    {
        return response()->json([
            'env_data' => [
                'GOOGLE_CALENDAR_LINK' => env('GOOGLE_CALENDAR_LINK'),
                'SCHEDUAL_APPOINTMENT_LINK' => env('SCHEDUAL_APPOINTMENT_LINK'),
            ],


        ]);
    }
    public function getNextHealthAppointmentTypes($id=''){
        if($id){
            $clinics = Clinic::find($id);

            // get local appointment type if nexthealth operatory id is null
            if(empty($clinics->operatory_id)){

                $appointment_availability = $clinics->appointment_availability;

                $patient_types = [];
                if(!empty($appointment_availability))
                {
                    $patient_types = explode(',', $appointment_availability->patient_types);
                }

                $responseArray = [];
                foreach ($patient_types as $index=>$type){
                    $responseArray[] = ['id'=>$index+1, 'name' => $type];
                }

                return response()->json(
                    [
                        'success' => true,
                        'data' => $responseArray
                    ],200
                );
            }

            $global_data = GlobalData::where(['key'=>'nexhealth_token'])->first();
            if($global_data){
                $token = $global_data['value'];
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'data' => 'No Access key found!'
                    ],200
                );
            }
            $headers = [];
            $responseArray = [];
            $headers[] = 'Accept:application/vnd.Nexhealth+json;version=2';
            $headers[] = "Authorization: ".$token;

            $global_data = GlobalData::where(['key'=>'nexhealth_token'])->first();
            if($global_data){
                $token = $global_data['value'];
            }else{
                return response()->json(
                    [
                        'success' => true,
                        'data' => 'No Access key found!'
                    ],200
                );
            }

            $head = [];
            $head[] = 'Accept:application/vnd.Nexhealth+json;version=2';
            $head[] = 'content-Type:application/json';
            $head[] = "Authorization:Bearer  ".$token;

            $curl = curl_init();

            $options = array(
                CURLOPT_URL => "https://nexhealth.info/appointment_types?subdomain=".$clinics['subdomain']."&location_id=".$clinics['location'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => $head
            );


            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);

            $responseData = json_decode($response,true);

            if($responseData['code']){
                $responseArray = $responseData['data'];
            }
            return response()->json(
                [
                    'success' => true,
                    'data' => $responseArray
                ],200
            );
        }else{
            return response()->json(
                [
                    'success' => true,
                    'data' => 'Id not found!'
                ],200
            );
        }
    }

    public function getServicesList(Request $request){

        // get local service type if nexthealth operatory id is null
        $clinic = Clinic::find($request->clinic_id);
        $selectedClinic = Clinic::find($request->selected_clinic_id);
        if($request->has('clinic_id') && empty($clinic->operatory_id)){

            $selected_services = [];
            if(!empty($clinic->appointment_availability)){
                $selected_services = explode(',', $clinic->appointment_availability->service_types);
            }

            $services = [];
            foreach ($selected_services as $service){
                $services[] = Services::find($service);
            }
        }elseif ($request->has('clinic_id') && $clinic->operatory_id != ''){
                $selected_services = [];
            if(!empty($clinic->appointment_availability)){
                $selected_services = explode(',', $clinic->appointment_availability->service_types);
            }
            $services = [];
            foreach ($selected_services as $service){
                $services[] = Services::find($service);
            }
        }else {
            $clinicServices = Services::where('clinic_id', $selectedClinic->id)->get();
            $commonServices = Services::whereNull('clinic_id')->get();
            $services = $clinicServices->merge($commonServices);
        }

        if ($services) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $services
                ], 200
            );
        } else {
            return response()->json(
                [
                    'success' => true,
                    'data' => 'No Data'
                ], 200
            );
        }
    }

    public function getAvailableTimes($id='',$date=''){

        $clinics = Clinic::select('id','subdomain','location','nexhealthkey','provider_ids','appointment_duration','timezone','operatory_id')->find($id);

        // Return local appointment availability if nexthealth opratory id is null
        if(empty($clinics->operatory_id)){
            return $this->getSelfSchedulingAvailabilities($id, $date, request()->aid, request()->has('days') ? request()->days : 6);
        }

        $timezone='America/New_York';
        if($clinics['timezone'] != null){
            $timezone = $clinics['timezone'];
        }

        $global_data = GlobalData::where(['key'=>'nexhealth_token'])->first();
        if($global_data){
            $token = $global_data['value'];
        }else{
            return response()->json(
                [
                    'success' => true,
                    'data' => 'No Access key found!'
                ],200
            );
        }
        $carbonDate = Carbon::createFromFormat('m-d-Y', $date);
        $today_date = $carbonDate->format('Y-m-d');

        $head = [];
        $head[] = 'Accept:application/vnd.Nexhealth+json;version=2';
        $head[] = 'content-Type:application/json';
        $head[] = "Authorization:Bearer  ".$token;

        $curl = curl_init();

        $options = array(
            CURLOPT_URL => "https://nexhealth.info/appointment_slots?subdomain=".$clinics['subdomain']."&start_date=".$today_date."&days=6&lids[]=".$clinics['location']."&pids[]=".$clinics['provider_ids']."&slot_length=".$clinics['appointment_duration']."&operatory_ids[]=".$clinics['operatory_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $head
        );

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $responseData = json_decode($response,true);
        $responseArray = [];
        if($responseData['code']){
            $responseArray = $responseData['data'][0]['slots'];
        }
        $resultArray = [];
        $startDate = Carbon::parse($today_date);
        $resultArray = [
            $startDate->format('Y-m-d') => [],
        ];
        for ($i = 1; $i <= 5; $i++) {
            $nextDate = $startDate->copy()->addDays($i);
            $nextKey = $nextDate->format('Y-m-d');

            if (!isset($resultArray[$nextKey])) {
                $resultArray[$nextKey] = [];
            }
        }
        foreach ($responseArray as $rows){
            if($timezone){
                $startDate = Carbon::parse($rows['time'])->tz($timezone);
            }else{
                $startDate = Carbon::parse($rows['time']);
            }
            $key = $startDate->format('Y-m-d');

            //Log::info($key.'-'.$startDate->format('g:i A'));
            //error_log('Some message here.');
            $resultArray[$key][] = [$startDate->format('g:i A'),$rows['time']];

        }

        return response()->json(
            [
                'success' => true,
                'data' => $resultArray,
                'availability' => ['duration'=>$clinics->appointment_duration]
            ],200
        );
    }

    public function getPractiseInfo(Request $request){

        $clinic = Clinic::where('id',$request->get('id'))->first();

        $fields = ['','',''];

        // Retrieve appointment availability and duration (default to 30 if null)
        $appointment_availability = AppointmentAvailability::where('clinic_id', $clinic->id)->first();
        $appointment_duration = $appointment_availability->duration ?? 30;


        if (isset($clinic->town_state_zip)) {
            if (substr_count($clinic->town_state_zip, ',') == 2) {
                $fields = explode(',', $clinic->town_state_zip);
            }elseif (substr_count($clinic->town_state_zip, ',') == 1) {
                $fields = explode(',', $clinic->town_state_zip);
                $fields_next = explode(' ', $fields[1]);
                $fields[1] = $fields_next[1];
                $fields[2] = $fields_next[2];
            }
        }

        $town = ($fields[0]) ? $fields[0] : '';
        $state = ($fields[1]) ? $fields[1] : '';
        $zip_code = ($fields[2]) ? $fields[2] : '';

        $appointment_availability = $clinic->appointment_availability;

        $responseData = [
            'id' => $clinic->id,
            'dr_name' => $clinic->dr_name,
            'degree_abbreviation' => $clinic->dr_abbreviations,
            'practice_name' => $clinic->clinic_name,
            'practice_legal_name' => $clinic->clinic_legal_name,
            'practice_email' => $clinic->email,
            'town' => $town,  // Assuming 'town', 'state', and 'zip_code' are attributes of the clinic
            'state' => $state,
            'zip_code' => $zip_code,
            'office_hours' => $clinic->office_hours,
            'clinic_logo' => $clinic->getFirstMedia('clinic_logo')!=null ? $clinic->getFirstMedia('clinic_logo')->getUrl() : '',
            'address' => $clinic->address,
            'calendar_type' => $appointment_availability->calendar_type,
            'duration' => $appointment_duration
        ];

        if($clinic){
            return response()->json(
                [
                    'success' => true,
                    'data' => $responseData
                ],200
            );
        }else{
            return response()->json(
                [
                    'success' => false,
                    'data' => "No Data Found"
                ],200
            );
        }
    }

    public function createAppointment($id = '', $date_time = '', $patient_id = ''){
        if($id === ''){
            return response()->json(
                [
                    'success' => true,
                    'data' => "No Clinic ID Found"
                ],200
            );
        }
        $clinics = Clinic::select('id','subdomain','location','nexhealthkey','provider_ids','operatory_id','appointment_duration')->find($id);

        $global_data = GlobalData::where(['key'=>'nexhealth_token'])->first();
        if($global_data){
            $token = $global_data['value'];
        }else{
            return response()->json(
                [
                    'success' => true,
                    'data' => 'No Access key found!'
                ],200
            );
        }

        $head = [];
        $head[] = 'Accept:application/vnd.Nexhealth+json;version=2';
        $head[] = 'content-Type:application/json';
        $head[] = "Authorization:Bearer  ".$token;

        $curl = curl_init();

        $url = "https://nexhealth.info/appointments?subdomain=".$clinics['subdomain']."&location_id=".$clinics['location']."&notify_patient=false";

        $originalTime = Carbon::parse($date_time);
        $newTime = $originalTime->addMinutes(60);
        $end_time = $newTime->format('Y-m-d\TH:i:s.vP');

        $postData = array(
            'appt' => array(
                'provider_id' => $clinics['provider_ids'],
                'start_time' => $date_time,
                'end_time' => $end_time,
                'operatory_id' => $clinics['operatory_id'],
                'patient_id' => $patient_id
            ),
        );
        $jsonData = json_encode($postData);

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $head
        );

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $responseData = json_decode($response,true);

        return response()->json(
            [
                'success' => true,
                'data' => $responseData
            ],200
        );
    }

    public function createPatient(Request $request){

        if($request->id === ''){
            return response()->json(
                [
                    'success' => true,
                    'data' => "No Clinic ID Found"
                ],200
            );
        }

        $clinics = Clinic::select('id', 'email', 'dr_name', 'subdomain','location','nexhealthkey','provider_ids', 'operatory_id','emails_for_scheduling','timezone', 'clinic_name', 'email_id')->find($request->id);



        //Book local appointment if nexthealth operatory id is null
        if(empty($clinics->operatory_id)){
            return $this->bookSelfScheduleAppointment($request);
        }

        $timezone='America/New_York';
        if($clinics['timezone'] != null){
            $timezone = $clinics['timezone'];
        }



        $global_data = GlobalData::where(['key'=>'nexhealth_token'])->first();
        if($global_data){
            $token = $global_data['value'];
        }else{
            return response()->json(
                [
                    'success' => true,
                    'data' => 'No Access key found!'
                ],200
            );
        }

        $head = [];
        $head[] = 'Accept:application/vnd.Nexhealth+json;version=2';
        $head[] = 'content-Type:application/json';
        $head[] = "Authorization:Bearer  ".$token;

        $curl = curl_init();

        $url = "https://nexhealth.info/patients?subdomain=".$clinics['subdomain']."&location_id=".$clinics['location'];

        $postData = array(
            "provider" => array(
                "provider_id" =>$clinics['provider_ids']
            ),
            "patient"=> array(
                "bio"=>array(
                    "date_of_birth"=>Carbon::parse($request->dob)->format('Y-m-d'),
                    "phone_number"=>$request->phone_number
                ),
                    "first_name"=>$request->first_name,
                    "last_name"=>$request->last_name,
                    "email"=>$request->email_address
                )
        );

        $jsonData = json_encode($postData);

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $head
        );

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $responseData = json_decode($response,true);
        $patient_id = 0;
        if(isset($responseData['data']['user']['id'])){
            $patient_id = $responseData['data']['user']['id'];
        }else{
            preg_match('/id=(\d+)/', $responseData['error'][0], $matches);

            if (isset($matches[1])) {
                $patient_id = $matches[1];
            }
        }

        $phoneNumber = $request->phone_number;

        // Remove special characters from the phone number
        $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if(Str::length($cleanedPhoneNumber) == 11 && Str::startsWith($cleanedPhoneNumber, '1')){
            $cleanedPhoneNumber = '+' . $cleanedPhoneNumber;
        }else if(Str::length($cleanedPhoneNumber) == 10) {
            $cleanedPhoneNumber = '+1' . $cleanedPhoneNumber;
        }

        //Create or Update lead status S
        $crm_customer = CrmCustomer::where('clinic_id', $request->id)
            ->where(function ($query) use ($request,$cleanedPhoneNumber) {
                $query->where('phone', $cleanedPhoneNumber)
                    ->orWhere('email', $request->email_address);
            })
            ->first();



        if($request->date_time){
            $startDate = Carbon::parse($request->date_time)->tz($timezone);
            $bookedDate = $startDate->format('m/d/Y H:i:s');
        }


        if($crm_customer){
            $crm_customer->status_id = '12';
            $crm_customer->consultation_booked_date = $bookedDate;
            $crm_customer->convert_to_deal = 1;
            $crm_customer->convert_deal_date = Carbon::now()->format('m/d/Y H:i:s');
            $crm_customer->save();

            // Check if the 'Self-Scheduled' tag exists
            $tag = Tag::where('tagName', 'Self-Scheduled')->where('clinic_id', $crm_customer->clinic_id)->first();



            // If the tag does not exist, create it
            if (!$tag) {
                $tag = new Tag();
                $tag->tagName = 'Self-Scheduled';
                $tag->clinic_id = $crm_customer->clinic_id;
                $tag->save();
            }

            // Create a new TagLeadMapping entry
            $tagLeadMapping = new TagLeadMapping();
            $tagLeadMapping->tag_id = $tag->id;
            $tagLeadMapping->lead_id = $crm_customer->id;
            $tagLeadMapping->save();

            // Find and delete the 'No Schedule' tag and its mappings
            $noScheduleTag = Tag::where('tagName', 'No Schedule')
            ->where('clinic_id', $crm_customer->clinic_id)
            ->whereHas('tagLeadMappings', function ($query) use ($crm_customer) {
                $query->where('lead_id', $crm_customer->id);
            })
            ->first();

            if ($noScheduleTag) {
                // Delete the tag lead mappings associated with the 'No Schedule' tag
                TagLeadMapping::where('tag_id', $noScheduleTag->id)
                ->where('lead_id', $crm_customer->id)
                ->delete();
            }

        }else{
            if($request->email_address){

                $crm_customer = CrmCustomer::create([
                    'first_name' =>$request->first_name,
                    'last_name' =>$request->last_name,
                    'email' =>$request->email_address,
                    'phone' =>$cleanedPhoneNumber,
                    'clinic_id' =>$request->id,
                    'status_id' => 12,
                    'consultation_booked_date' => $bookedDate,
                    'ccapture' => 0,
                    'has_sms' => 0,
                    'automation' => 0,
                    'email_sequence' => 0,
                    'sms_sequence' => 0,
                    'patient_journey_automation' => 0,
                    'patient_journey_email_sequence' => 0,
                    'patient_journey_sms_sequence' => 0,
                    'automationNurture' => 0,
                    'nurture_sequence' => 0,
                    'nutureSms_sequence' => 0,
                    'phone_verified' => 0,
                    'email_verified' => 0,
                    'source_id' => 11,
                    'convert_to_deal' => 1,
                    'convert_deal_date' => Carbon::now()->format('m/d/Y H:i:s')
                ]);

                // Check if the 'Self-Scheduled' tag exists
                $tag = Tag::where('tagName', 'Self-Scheduled')->where('clinic_id', $crm_customer->clinic_id)->first();



                // If the tag does not exist, create it
                if (!$tag) {
                    $tag = new Tag();
                    $tag->tagName = 'Self-Scheduled';
                    $tag->clinic_id = $crm_customer->clinic_id;
                    $tag->save();
                }

                // Create a new TagLeadMapping entry
                $tagLeadMapping = new TagLeadMapping();
                $tagLeadMapping->tag_id = $tag->id;
                $tagLeadMapping->lead_id = $crm_customer->id;
                $tagLeadMapping->save();

                // Find and delete the 'No Schedule' tag and its mappings
                $noScheduleTag = Tag::where('tagName', 'No Schedule')
                ->where('clinic_id', $crm_customer->clinic_id)
                ->whereHas('tagLeadMappings', function ($query) use ($crm_customer) {
                    $query->where('lead_id', $crm_customer->id);
                })
                ->first();

                if ($noScheduleTag) {
                    // Delete the tag lead mappings associated with the 'No Schedule' tag
                    TagLeadMapping::where('tag_id', $noScheduleTag->id)
                    ->where('lead_id', $crm_customer->id)
                    ->delete();
                }


            }
        }
        //Create or Update lead status E



        $appointment_results = $this->createAppointment($request->id, $request->date_time, $patient_id);

        // Create the $data array
        $appointment_arr = json_decode($appointment_results->content());

        $appointment = (object) ['date' => $startDate->format('m/d/Y'),
            'time'=> $startDate->format('g:i A'),
            'services'=>(object) ['name'=>Services::where('name', $request->service_type)->first()->name],
            'patient_type' => $request->patient_type, 'comments'=>$request->comments
            ];




        // Initialize the $to array with the request and clinic email addresses
        $to = [$request->email_address];

        // Check if $clinics->emails_for_scheduling is not empty
        if (!empty($clinics->emails_for_scheduling)) {
            // Split the comma-separated emails into an array
            $additionalEmails = explode(',', $clinics->emails_for_scheduling);

            // Trim whitespace from each email address
            $additionalEmails = array_map('trim', $additionalEmails);
        }

        // Find the lead (CrmCustomer)
        $lead = CrmCustomer::find($crm_customer->id);




        $templateService = new TemplateService();

        $template = $templateService->getTemplateByClinicAndType($clinics->id, 'appointment');

        // Placeholder data
        $data = [
            'lead_first_name' => $lead->first_name,
            'lead_last_name' => $lead->last_name,
            'lead_email' => $lead->email,
            'lead_phone' => $lead->phone,
            'clinic_dr_name' => $clinics->dr_name,
            'appointment_date' => $appointment->date,
            'appointment_time' => $appointment->time,
            'appointment_patient_type' => !empty($appointment->patient_type)
                ? 'Patient - ' . $appointment->patient_type
                : '',
            'appointment_service_name' => !empty($appointment->services)
                ? 'Service - ' . $appointment->services->name
                : '',
            'appointment_comments' => !empty($appointment->comments)
                ? 'Comments/Requests - ' . $appointment->comments
                : '',
            'appointment_change_link' => !empty($appointment->id)
                ? '<a href="' . url('/crtx/schedule-appointment/' . $clinics->id . '/sa-step-1?id=' . $appointment->id) . '">Change appointment details</a>'
                : '<a href="' . url('/crtx/schedule-appointment/' . $clinics->id . '/sa-step-1') . '">Change appointment details</a>',
        ];


        // Replace placeholders
        if ($template) {
            // Case when a template is found
            $emailBody = $templateService->appointmentreplacePlaceholders($template->body, $data);
            Mail::to($to)
            ->cc($additionalEmails ?? [])
            ->send(new AppointmentEmail($emailBody, $template->subject, $clinics));
        } else {
            // Case when no template is found
            $appointmentData = [
                'appointment' => $appointment,
                'lead' => $crm_customer,
                'clinic' => $clinics,
            ];
            Mail::to($to)
            ->cc($additionalEmails ?? [])
            ->send(new AppointmentEmail($appointmentData));
        }

        if($request->date_time){
            $startDate = Carbon::parse($request->date_time)->tz($timezone);
            $date = $startDate->format('m/d/Y');
            $time = $startDate->format('g:i A');
        }

        $appointmentdata = [
            'date' => $date,
            'time' => $time,
            'service_type' => Services::where('name', $request->service_type)->first()->name,
            'patient_type' => $request->patient_type,
            'payment_type' => '',
            'comments' => $request->comments,
            'consent' => '',
            'dob' => Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d'),
            'email_address' => $request->email_address,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number
        ];

        $this->sendAppointmentScheduledNotification($clinics->id, $crm_customer->id,$appointmentdata);

        if (!empty($clinics)) {
            // Retrieve users with role id 5
            $users = $clinics->managers()->whereHas('roles', function ($q) {
                $q->where('id', 5);
            })->get();

            // Find the lead (CrmCustomer)
            $lead = CrmCustomer::find($crm_customer->id);

            if (!empty($lead)) {
                foreach ($users as $user) {

                    $setting = Setting::where('user_id', $user->id)->first();
                    if ($setting) {
                        if ($setting->do_not_disturb == 0 && $setting->appointment_text_notification == 1) {
                            // Send notification to each user
            $notification = new AppointmentScheduled($crm_customer,$appointmentdata);
                            $notification->toTwilio($user,$crm_customer);
                        }
                    } else {
                            // User does not have settings saved, send notifications by default
                            $notification = new AppointmentScheduled($crm_customer,$appointmentdata);
                            $notification->toTwilio($user,$crm_customer);
                    }
                }
            }
        }

        return response()->json(
            [
                'success' => true,
                'data' => ['patient_data'=>$responseData,'appointment_data'=>$appointment_results]
            ],200
        );
    }

    private function getSelfSchedulingAvailabilities($clinic_id, $date, $appointment_id = null, $days = 6)
    {
        $clinic = Clinic::find($clinic_id);

        $appointment_availability = $clinic->appointment_availability;

        if(!empty($appointment_availability)){
            $appointments = $appointment_availability->appointments;

            $data = [];
            for ($i=0; $i<$days; $i++){
                $current_date = Carbon::createFromFormat('m-d-Y', $date)->addDays($i);
                $booked_appointments = $appointments->where('date', $current_date->format('Y-m-d'));
                $booked_appointment_times = $booked_appointments->pluck('time')->toArray();

                // Remove time for currently editing appointment schedule
                if(!empty($appointment_id)){
                    $appointment = Appointment::find($appointment_id);
                    if($current_date->format('Y-m-d') == Carbon::parse($appointment->date)->format('Y-m-d')){
                        foreach($booked_appointment_times as $index=>$time){
                            if($time === $appointment->time){
                               array_splice($booked_appointment_times, $index, 1);
                            }
                        }
                    }
                }

                $fixed_appointment_availability = $appointment_availability->fixed_appointment_availabilities()->whereDate('date', $current_date)->first();

                if(!empty($fixed_appointment_availability)){
                    $times_arr = explode(',', $fixed_appointment_availability->times);
                    $minutes = [];
                    foreach ($times_arr as $ta){
                        $time_range = explode('-', $ta);
                        $period = CarbonPeriod::create(Carbon::parse($time_range[0]), $appointment_availability->duration.' minutes', Carbon::parse($time_range[1]));
                        foreach ($period as $index=>$time) {
                            if(!in_array($time->format('H:i:s'), $booked_appointment_times) && $index != count($period)-1){
                                $minutes[] = [0 => $time->format('h:i A'), 1 => $current_date->format('m/d/Y') . ' ' . $time->format('h:i A')];
                            }
                        }
                    }
                    $data = array_merge($data, [$current_date->format('Y-m-d')=> $minutes]);
                }

                $repeating_appointment_availability = $appointment_availability->repeating_appointment_availability;

                if(!empty($repeating_appointment_availability) && empty($fixed_appointment_availability)){
                    $appointment_unavailabilities = $appointment_availability->appointment_unavailabilities;
                    $isUnavailableDate = false;
                    foreach ($appointment_unavailabilities as $appointment_unavailability){
                        if($current_date->isSameDay($appointment_unavailability->date)){
                            $isUnavailableDate = true;
                        }
                    }

                    $current_day = $current_date->shortDayName;
                    $day_times = $repeating_appointment_availability[strtolower($current_day)];
                    $minutes = [];
                    if(!$isUnavailableDate && !empty($day_times)){
                        $times_arr = explode(',', $day_times);
                        foreach ($times_arr as $ta){
                            $time_range = explode('-', $ta);
                            $period = CarbonPeriod::create(Carbon::parse($time_range[0]), $appointment_availability->duration.' minutes', Carbon::parse($time_range[1]));
                            foreach ($period as $index=>$time) {
                                if(!in_array($time->format('H:i:s'), $booked_appointment_times) && $index != count($period)-1){
                                    $minutes[] = [0 => $time->format('h:i A'), 1 => $current_date->format('m/d/Y') . ' ' . $time->format('h:i A')];
                                }
                            }
                        }
                    }

                    $data = array_merge_recursive($data, [$current_date->format('Y-m-d')=> $minutes]);
                }

            }

            // Unique Sort availabilities in ascending order.
            $data_sorted = [];
            foreach ($data as $k=>$d){
                $data_sorted[$k] = collect($d)-> sortBy(function ($obj, $key) {
                    return Carbon::createFromFormat('h:i A', $obj[0])->timestamp;
                })->unique()->values();
            }

            // Remove less than duration availabilities (Not Required)
            $data_filtered = [];
            foreach ($data_sorted as $j=>$date){
                $last_time = null;
                $item_to_delete = [];
                foreach ($date as $i=>$time) {
                    $item_omitted = false;
                    if($i!=0){
                        $diff_in_minutes = Carbon::parse($time[0])->diffInMinutes(Carbon::parse($last_time[0]));
                        if($diff_in_minutes<$appointment_availability->duration){
                            $item_to_delete[$i] = $time;
                            $item_omitted = true;
                        }
                    }
                    if(!$item_omitted){
                        $last_time = $time;
                    }
                }

                $date = array_values(array_diff_key($date->toArray(), $item_to_delete));
                $data_filtered[$j] = $date;
            }

            return response()->json(
                [
                    'success' => true,
                    'data' => $data_filtered,
                    'availability' => $appointment_availability->setRelations([])
                ],200
            );
        }

        return response()->json(
            [
                'success' => false,
                'data' => "No Appointment data found for this clinic!"
            ],200
        );
    }

    private function bookSelfScheduleAppointment(Request $request)
    {
        $clinic = Clinic::select('id','subdomain', 'dr_name', 'email', 'location','nexhealthkey','provider_ids', 'operatory_id','emails_for_scheduling', 'clinic_name', 'email_id')->find($request->id);

        $appointment_availability = $clinic->appointment_availability;

        $timezone='America/New_York';

        if(!empty($appointment_availability->timezone)){
            $timezone = $appointment_availability->timezone;
        }

        $phoneNumber = $request->phone_number;

        // Remove special characters from the phone number
        $cleanedPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if(Str::length($cleanedPhoneNumber) == 11 && Str::startsWith($cleanedPhoneNumber, '1')){
            $cleanedPhoneNumber = '+' . $cleanedPhoneNumber;
        }else if(Str::length($cleanedPhoneNumber) == 10) {
            $cleanedPhoneNumber = '+1' . $cleanedPhoneNumber;
        }

        //Create or Update lead status
        $crm_customer = CrmCustomer::where('clinic_id', $request->id)
            ->where(function ($query) use ($request,$cleanedPhoneNumber) {
                $query->where('phone', $cleanedPhoneNumber)
                    ->orWhere('email', $request->email_address);
            })
            ->first();

        if($request->date_time){
            // TODO : Apply timezone here...
            $startDate = Carbon::createFromFormat('m/d/Y h:i A', $request->date_time);
            $bookedDate = $startDate->format('m/d/Y H:i:s');
        }

        if($crm_customer){
            $crm_customer->status_id = '12';
            $crm_customer->consultation_booked_date = $bookedDate;
            $crm_customer->convert_to_deal = 1;
            $crm_customer->convert_deal_date = Carbon::now()->format('m/d/Y H:i:s');
            $crm_customer->save();
            // Check if the 'Self-Scheduled' tag exists
            $tag = Tag::where('tagName', 'Self-Scheduled')->where('clinic_id', $crm_customer->clinic_id)->first();

            // If the tag does not exist, create it
            if (!$tag) {
                $tag = new Tag();
                $tag->tagName = 'Self-Scheduled';
                $tag->clinic_id = $crm_customer->clinic_id;
                $tag->save();
            }

            // Create a new TagLeadMapping entry
            $tagLeadMapping = new TagLeadMapping();
            $tagLeadMapping->tag_id = $tag->id;
            $tagLeadMapping->lead_id = $crm_customer->id;
            $tagLeadMapping->save();

            // Find and delete the 'No Schedule' tag and its mappings
            $noScheduleTag = Tag::where('tagName', 'No Schedule')
            ->where('clinic_id', $crm_customer->clinic_id)
            ->whereHas('tagLeadMappings', function ($query) use ($crm_customer) {
                $query->where('lead_id', $crm_customer->id);
            })
            ->first();

            if ($noScheduleTag) {
                // Delete the tag lead mappings associated with the 'No Schedule' tag
                TagLeadMapping::where('tag_id', $noScheduleTag->id)
                ->where('lead_id', $crm_customer->id)
                ->delete();
            }

        }else{
            if($request->email_address){

                $crm_customer = CrmCustomer::create([
                    'first_name' =>$request->first_name,
                    'last_name' =>$request->last_name,
                    'email' =>$request->email_address,
                    'phone' =>$cleanedPhoneNumber,
                    'dob' => $request->dob,
                    'clinic_id' =>$request->id,
                    'status_id' => 12,
                    'consultation_booked_date' => $bookedDate,
                    'ccapture' => 0,
                    'has_sms' => 0,
                    'automation' => 0,
                    'email_sequence' => 0,
                    'sms_sequence' => 0,
                    'patient_journey_automation' => 0,
                    'patient_journey_email_sequence' => 0,
                    'patient_journey_sms_sequence' => 0,
                    'automationNurture' => 0,
                    'nurture_sequence' => 0,
                    'nutureSms_sequence' => 0,
                    'phone_verified' => 0,
                    'email_verified' => 0,
                    'source_id' => 11,
                    'phone_form' => !empty($request->phone_form) ? $request->phone_form : 'Web Form',
                    'convert_to_deal' => 1,
                    'convert_deal_date' => Carbon::now()->format('m/d/Y H:i:s')
                ]);

                 // Check if the 'Self-Scheduled' tag exists
                $tag = Tag::where('tagName', 'Self-Scheduled')->where('clinic_id', $crm_customer->clinic_id)->first();

                // If the tag does not exist, create it
                if (!$tag) {
                    $tag = new Tag();
                    $tag->tagName = 'Self-Scheduled';
                    $tag->clinic_id = $crm_customer->clinic_id;
                    $tag->save();
                }

                // Create a new TagLeadMapping entry
                $tagLeadMapping = new TagLeadMapping();
                $tagLeadMapping->tag_id = $tag->id;
                $tagLeadMapping->lead_id = $crm_customer->id;
                $tagLeadMapping->save();

                // Find and delete the 'No Schedule' tag and its mappings
                $noScheduleTag = Tag::where('tagName', 'No Schedule')
                ->where('clinic_id', $crm_customer->clinic_id)
                ->whereHas('tagLeadMappings', function ($query) use ($crm_customer) {
                    $query->where('lead_id', $crm_customer->id);
                })
                ->first();

                if ($noScheduleTag) {
                    // Delete the tag lead mappings associated with the 'No Schedule' tag
                    TagLeadMapping::where('tag_id', $noScheduleTag->id)
                    ->where('lead_id', $crm_customer->id)
                    ->delete();
                }
            }
        }

        if(!empty($crm_customer)){
            $appointment = Appointment::updateOrCreate(['id'=>$request->appointment_id], [
                'crm_customer_id' => $crm_customer->id,
                'appointment_availability_id' => $appointment_availability->id,
                'date' => Carbon::createFromFormat('m/d/Y H:i:s', $bookedDate)->format('Y-m-d'),
                'time' => Carbon::createFromFormat('m/d/Y H:i:s', $bookedDate)->format('H:i:s'),
                'service_type' => !empty($request->service_type)? Services::where('name', $request->service_type)->first()->id : null,
                'patient_type' => $request->patient_type,
                'payment_type' => $request->payment_type,
                'comments' => $request->comments,
                'consent' => $request->consent
            ]);
            // Extract date and time from date_time field
            $dateTime = Carbon::createFromFormat('m/d/Y h:i A', $request->date_time);





            $date = Carbon::parse($request->date_time)->format('m/d/Y');
            $time = Carbon::parse($request->date_time)->format('g:i A');




            // Create variables for each field
            $serviceType = !empty($request->service_type) ? Services::where('name', $request->service_type)->first()->id : null;
            $patientType = $request->patient_type;
            $paymentType = $request->payment_type;
            $comments = $request->comments;
            $consent = $request->consent;
            $dob = Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d');
            $emailAddress = $request->email_address;
            $firstName = $request->first_name;
            $lastName = $request->last_name;
            $phoneNumber = $request->phone_number;

            // Create the $data array
            $appointmentdata = [
                'id' => $appointment->id,
                'date' => $date,
                'time' => $time,
                'service_type' => $serviceType,
                'patient_type' => $patientType,
                'payment_type' => $paymentType,
                'comments' => $comments,
                'consent' => $consent,
                'dob' => $dob,
                'email_address' => $emailAddress,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $phoneNumber
            ];

            $this->sendAppointmentScheduledNotification($clinic->id, $crm_customer->id,$appointmentdata);

            if (!empty($clinic)) {
                // Retrieve users with role id 5
                $users = $clinic->managers()->whereHas('roles', function ($q) {
                    $q->where('id', 5);
                })->get();

                // Find the lead (CrmCustomer)
                $lead = CrmCustomer::find($crm_customer->id);

                if (!empty($lead)) {
                    foreach ($users as $user) {

                        $setting = Setting::where('user_id', $user->id)->first();
                        if ($setting) {
                            if ($setting->do_not_disturb == 0 && $setting->appointment_text_notification == 1) {
                                // Send notification to each user
                $notification = new AppointmentScheduled($crm_customer,$appointmentdata);
                                $notification->toTwilio($user,$crm_customer);
                            }
                        } else {
                                // User does not have settings saved, send notifications by default
                                $notification = new AppointmentScheduled($crm_customer,$appointmentdata);
                                $notification->toTwilio($user,$crm_customer);
                        }
                    }
                }
            }

            // Initialize the $to array with the request and clinic email addresses
            $to = [$request->email_address];

            // Check if $clinic->emails_for_scheduling is not empty
            if (!empty($clinic->emails_for_scheduling)) {
                // Split the comma-separated emails into an array
                $additionalEmails = explode(',', $clinic->emails_for_scheduling);

                // Trim whitespace from each email address
                $additionalEmails = array_map('trim', $additionalEmails);
            }

            $templateService = new TemplateService();

            $template = $templateService->getTemplateByClinicAndType($clinic->id, 'appointment');


            $data = [
                'lead_first_name' => $lead->first_name,
                'lead_last_name' => $lead->last_name,
                'lead_email' => $lead->email,
                'lead_phone' => $lead->phone,
                'clinic_dr_name' => $clinic->dr_name,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'appointment_patient_type' => !empty($appointment->patient_type)
                    ? $appointment->patient_type
                    : '',
                'appointment_service_name' => !empty($appointment->services)
                    ? $appointment->services->name
                    : '',
                'appointment_comments' => !empty($appointment->comments)
                    ?  $appointment->comments
                    : '',
                'appointment_change_link' => !empty($appointment->id)
                    ? '<a href="' . url('/crtx/schedule-appointment/' . $clinic->id . '/sa-step-1?id=' . $appointment->id) . '">Change appointment details</a>'
                    : '<a href="' . url('/crtx/schedule-appointment/' . $clinic->id . '/sa-step-1') . '">Change appointment details</a>',
            ];

            // Replace placeholders
            if ($template) {
                // Case when a template is found
                $emailBody = $templateService->appointmentreplacePlaceholders($template->body, $data);
                Mail::to($to)
                ->cc($additionalEmails ?? [])
                ->send(new AppointmentEmail($emailBody, $template->subject, $clinic));
            } else {
                // Case when no template is found

                $dataobject = (object) $appointmentdata;

                $appointmentData = [
                    'appointment' => $dataobject,
                    'lead' => $crm_customer,
                    'clinic' => $clinic,
                ];

                Mail::to($to)
                ->cc($additionalEmails ?? [])
                ->send(new AppointmentEmail($appointmentData));
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Appointment booked successfully!'
                ],200
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'There was an error booking appointment!'
            ],200
        );
    }

    public function getById(Request $request)
    {
        if($request->has('appointment_id')){
            $appointment = Appointment::with('services', 'crm_customer', 'appointment_availability')->find($request->appointment_id);

            $appointment_availability = $appointment->appointment_availability;

            if($appointment_availability->clinic_id == $request->clinic_id){
                return response()->json(
                    [
                        'success' => true,
                        'data' => $appointment
                    ],200
                );
            }else{
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'You do not have access to this appointment!'
                    ],200
                );
            }
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'There was an error fetching appointment data!'
            ],200
        );
    }

    public function checkAppointmentAvailability(Request $request)
    {
        $clinic_id = $request->clinic_id;

        $toolCallId = $request->message['toolCalls'][0]['id'];

        $dateTime = $request->message['toolCalls'][0]['function']['arguments']['dateTime'];

        $date = Carbon::parse($dateTime)->format('Y-m-d');

        $time = Carbon::parse($dateTime)->format('H:i:00');

        $appointment_availability = AppointmentAvailability::where('clinic_id', $clinic_id)->first();

        $appointment_duration = $appointment_availability->duration;

        $repeating_availability = $appointment_availability->repeating_appointment_availability;

        $fixed_availability = $appointment_availability->fixed_appointment_availabilities->first();

        $repeating_times = [];

        $repeating_time_set = explode(',', $repeating_availability[strtolower(Carbon::parse($dateTime)->shortEnglishDayOfWeek)]);

        $appointment_unavailabilities = $appointment_availability->appointment_unavailabilities;

        $isUnavailableDate = false;
        foreach ($appointment_unavailabilities as $appointment_unavailability){
            if(Carbon::parse($dateTime)->isSameDay($appointment_unavailability->date)){
                $isUnavailableDate = true;
            }
        }

        if($isUnavailableDate || empty($repeating_time_set[0])){
            $message = 'No time slot available on '. Carbon::parse($dateTime)->format('m/d/Y') . '. Please choose another day.';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }

        foreach($repeating_time_set as $set){
            $start = explode('-', $set)[0];
            $end = explode('-', $set)[1];

            $repeating_times[] = $start;

            $diffInMinutes = Carbon::parse($repeating_times[sizeof($repeating_times)-1])->diffInMinutes(Carbon::parse($end));

            if($diffInMinutes > $appointment_duration){
                $slots = floor($diffInMinutes/$appointment_duration);

                for($i = 0; $i < $slots-1; $i++){
                    $repeating_times[] = Carbon::parse($repeating_times[sizeof($repeating_times) - 1])->addMinutes($appointment_duration)->format('h:ia');
                }
            }
        }

        $available_times = [];

        $differed_times = [];

        foreach ($repeating_times as $repeating_time) {
            $app = Appointment::where('appointment_availability_id', $appointment_availability->id)->where('date', $date)->where('time', Carbon::parse($repeating_time)->format('H:i:00'))->first();
            if(empty($app)){
                $available_times[] = $repeating_time;
                $differed_times[] = Carbon::parse($repeating_time)->diffInMinutes(Carbon::parse($time));
            }
        }

        $formatted_time = Carbon::parse($time)->format('h:ia');

        $appointment = Appointment::where('appointment_availability_id', $appointment_availability->id)->where('date', $date)->where('time', $time)->first();

        if(sizeof($available_times) == 0){
            $message = 'No time slot available on '. Carbon::parse($dateTime)->format('m/d/Y') . '. Please choose another day.';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }

        if(!empty($appointment) || !in_array($formatted_time, $available_times)){

            $min_index = array_search(min($differed_times), $differed_times);

            $slot = $available_times[$min_index];

            $before_slot = $min_index != 0 ? $available_times[$min_index-1] : '';

            $after_slot = sizeof($available_times) > $min_index+1 ? $available_times[$min_index+1] : '';

            $message = 'Sorry, provided time slot is not available for '.Carbon::parse($dateTime)->format('m/d/Y').'. Available time slots are: '.$before_slot.', '.$slot.', '.$after_slot;

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }else{
            $message = 'Provided time slot is available, would you like to book an appointment.';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }
    }

    public function checkAppointmentAvailabilityVoiceflow(Request $request)
    {
        $clinic_id = $request->clinic_id;

        $dateTime = $request->dateTime;

        $date = Carbon::parse($dateTime)->format('Y-m-d');

        $time = Carbon::parse($dateTime)->format('H:i:00');

        $appointment_availability = AppointmentAvailability::where('clinic_id', $clinic_id)->first();

        $appointment_duration = $appointment_availability->duration;

        $repeating_availability = $appointment_availability->repeating_appointment_availability;

        $fixed_availability = $appointment_availability->fixed_appointment_availabilities->first();

        $repeating_times = [];

        $repeating_time_set = explode(',', $repeating_availability[strtolower(Carbon::parse($dateTime)->shortEnglishDayOfWeek)]);

        $appointment_unavailabilities = $appointment_availability->appointment_unavailabilities;

        $isUnavailableDate = false;
        foreach ($appointment_unavailabilities as $appointment_unavailability){
            if(Carbon::parse($dateTime)->isSameDay($appointment_unavailability->date)){
                $isUnavailableDate = true;
            }
        }

        if($isUnavailableDate || empty($repeating_time_set[0])){
            $message = 'No time slot available on '. Carbon::parse($dateTime)->format('m/d/Y') . '. Please choose another day.';

            return json_encode(['results'=>$message]);
        }

        foreach($repeating_time_set as $set){
            $start = explode('-', $set)[0];
            $end = explode('-', $set)[1];

            $repeating_times[] = $start;

            $diffInMinutes = Carbon::parse($repeating_times[sizeof($repeating_times)-1])->diffInMinutes(Carbon::parse($end));

            if($diffInMinutes > $appointment_duration){
                $slots = floor($diffInMinutes/$appointment_duration);

                for($i = 0; $i < $slots-1; $i++){
                    $repeating_times[] = Carbon::parse($repeating_times[sizeof($repeating_times) - 1])->addMinutes($appointment_duration)->format('h:ia');
                }
            }
        }

        $available_times = [];

        $differed_times = [];

        foreach ($repeating_times as $repeating_time) {
            $app = Appointment::where('appointment_availability_id', $appointment_availability->id)->where('date', $date)->where('time', Carbon::parse($repeating_time)->format('H:i:00'))->first();
            if(empty($app)){
                $available_times[] = $repeating_time;
                $differed_times[] = Carbon::parse($repeating_time)->diffInMinutes(Carbon::parse($time));
            }
        }

        $formatted_time = Carbon::parse($time)->format('h:ia');

        $appointment = Appointment::where('appointment_availability_id', $appointment_availability->id)->where('date', $date)->where('time', $time)->first();

        if(sizeof($available_times) == 0){
            $message = 'No time slot available on '. Carbon::parse($dateTime)->format('m/d/Y') . '. Please choose another day.';

            return json_encode(['results'=>$message]);
        }

        if(!empty($appointment) || !in_array($formatted_time, $available_times)){

            $min_index = array_search(min($differed_times), $differed_times);

            $slot = $available_times[$min_index];

            $before_slot = $min_index != 0 ? $available_times[$min_index-1] : '';

            $after_slot = sizeof($available_times) > $min_index+1 ? $available_times[$min_index+1] : '';

            $message = 'Sorry, provided time slot is not available for '.Carbon::parse($dateTime)->format('m/d/Y').'. Available time slots are: '.$before_slot.', '.$slot.', '.$after_slot;

            return json_encode(['results'=>$message]);
        }else{
            $message = 'Provided time slot is available, would you like to book an appointment.';

            return json_encode(['results'=>$message]);
        }
    }

    public function bookAppointment(Request $request)
    {
        $clinic_id = $request->clinic_id;

        $toolCallId = $request->message['toolCalls'][0]['id'];

        $dateTime = $request->message['toolCalls'][0]['function']['arguments']['dateTime'];

        $fullName = $request->message['toolCalls'][0]['function']['arguments']['fullName'];

        $phone = $request->message['toolCalls'][0]['function']['arguments']['phone'];



        if(empty($dateTime)){
            $message = 'Sorry, could you please tell me at what date & time you would like to book an appointment again?';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }

        if(empty($fullName)){
            $message = 'Sorry, could you please tell me your full name again?';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }

        if(empty($phone)){
            $message = 'Sorry, could you please tell me your phone number again?';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }

        $email = $request->message['toolCalls'][0]['function']['arguments']['email'] ?? $phone.'@noreply.com';

        $email = str_replace(' ', '', $email);

        $request->id = $clinic_id;

        $request->first_name = explode(' ', $fullName, 2)[0];

        $request->last_name = explode(' ', $fullName, 2)[1];

        $request->phone_number = $phone;

        $request->email_address = $email;

        $request->date_time = Carbon::parse($dateTime)->format('m/d/Y h:i A');

        $request->dob = "01/01/2000";

        $request->service_type = 'Other';

        $request->patient_type = 'New Patient';

        $request->payment_type = 'insurance';

        $request->comments = 'Phone call booking';

        $request->consent = 1;

        $request->phone_form = 'Phone Call';

        $response = $this->bookSelfScheduleAppointment($request);

        if(json_decode($response->content())->success){
            $message = 'Congratulations, your appointment has been successfully booked for '.$request->date_time;

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }else{
            $message = 'Sorry, there is an error booking your appointment. Please try again later.';

            return json_encode(['results'=>[['toolCallId' => $toolCallId, 'result' => $message]]]);
        }
    }

    public function bookAppointmentVoiceflow(Request $request)
    {
        $clinic_id = $request->clinic_id;

        $dateTime = $request->dateTime;

        $fullName = $request->name;

        $phone = $request->phone;

        if(empty($dateTime)){
            $message = 'Sorry, could you please tell me at what date & time you would like to book an appointment again?';

            return json_encode(['results'=>$message]);
        }

        if(empty($fullName)){
            $message = 'Sorry, could you please tell me your full name again?';

            return json_encode(['results'=>$message]);
        }

        if(empty($phone)){
            $message = 'Sorry, could you please tell me your phone number again?';

            return json_encode(['results'=>$message]);
        }

        $email = $request->email ?? $phone.'@noreply.com';

        $email = str_replace(' ', '', $email);

        $request->id = $clinic_id;

        $request->first_name = explode(' ', $fullName, 2)[0];

        $request->last_name = explode(' ', $fullName, 2)[1];

        $request->phone_number = $phone;

        $request->email_address = $email;

        $request->date_time = Carbon::parse($dateTime)->format('m/d/Y h:i A');

        $request->dob = "01/01/2000";

        $request->service_type = 'Other';

        $request->patient_type = 'New Patient';

        $request->payment_type = 'insurance';

        $request->comments = 'Phone call booking';

        $request->consent = 1;

        $request->phone_form = 'Phone Call';

        $response = $this->bookSelfScheduleAppointment($request);

        if(json_decode($response->content())->success){
            $message = 'Congratulations, your appointment has been successfully booked for '.$request->date_time;

            return json_encode(['results'=>$message]);
        }else{
            $message = 'Sorry, there is an error booking your appointment. Please try again later.';

            return json_encode(['results'=>$message]);
        }
    }
}
