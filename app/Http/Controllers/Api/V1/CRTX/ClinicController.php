<?php

namespace App\Http\Controllers\Api\V1\CRTX;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRTX\ClinicResource;
use App\Http\Services\AwsEmailService;
use App\Mail\WelcomeEmail;
use App\Models\Clinic;
use App\Models\ErrorLog;
use App\Models\RoleUser;
use App\Models\ClinicUser;
use App\Models\User;
use App\Services\DnsEmailVerificationService;
use App\Services\GetDnsVerificationService;
use App\Services\TemplateService;
use App\Traits\ExceptionLog;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use Str;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Exception;
use App\Models\ManageTemplate;
use App\Mail\ClinicUpdated;
use App\Models\CrmStatus;
use Illuminate\Support\Facades\Storage;
use App\Models\CrmCustomer;
use App\Models\CrmNote;
use App\Services\OpenAIAutoService;
use App\Models\EmailSent;
use Illuminate\Support\Facades\Http;


class ClinicController extends Controller
{
    use ExceptionLog;
    private $openAIAutoService;

    public function __construct()
    {
        $this->awsEmailService = new AwsEmailService();
        $this->openAIAutoService = new OpenAIAutoService();
    }
    public function store(Request $request)
    {
        // Account Data
        if($request->clinic_id){
            $clinicstore = Clinic::find($request->clinic_id);
        }else{
            $request['staff_emails_arr'] = explode(',', $request->staff_emails);

            $validate = Validator::make($request->all(), empty($request->user_id) ? [
                'practice_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'first_name' => 'required',
                'last_name' => 'required',
            ] : [ 'degree_abbreviation' => 'required',
                'practice_name' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);

            if ($validate->fails()) {
                $error = $this->errorMessages($validate);
                foreach ($error as $i=>$e) {
                    if(stripos($i, 'staff_emails_arr')!==false){
                        $index = explode('.', $i)[1];
                        $error[$i] = 'Staff Email entered at position '.($index+1).' is either invalid or has already been registered.';
                    }
                }
                return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
            }
            $townziparray =[
                "town" => $request->town,
                "state" => $request->state,
                "zip_code" => $request->zip_code,
            ];

            $clinicstore = new Clinic();
            $clinicstore->dr_name = 'Dr. '.$request->last_name;
            $clinicstore->dr_fullname = $request->dr_fullname;
            $clinicstore->dr_abbreviations = ($request->degree_abbreviation === 'Other') ? $request->degree_abbreviation_other: $request->degree_abbreviation;
            $clinicstore->type = $request->industry;
            $clinicstore->website = $request->website_url;
            $clinicstore->clinic_name = $request->practice_name;
            $clinicstore->email = $request->practice_email;
            $clinicstore->address = $request->address;
            $clinicstore->town_state_zip = $this->array_implode($townziparray);
            $clinicstore->office_name = $request->office_name;
            $clinicstore->office_number = $request->phone;
            $clinicstore->office_hours = $request->office_hours;
            $clinicstore->multiple_locations = $request->multiple_locations;
            $clinicstore->multiple_localtion_details  = $request->multiple_location_details ? json_encode($request->multiple_location_details) : null;
            $clinicstore->holidays = $request->holidays;
            $clinicstore->practice_management_system = $request->practice_management_system;
            $clinicstore->practice_different = $request->practice_different;
            $clinicstore->current_website_accurate = $request->website_information_detail;
            $clinicstore->trustfull_api = 1;
            $clinicstore->call_transcript_api = 1;
        }

        // AI Learning Data
        $clinicstore->multiple_doctors = $request->multiple_doctors_details;
        $clinicstore->practice_specialty = $this->array_implode($request->practice_speciality);
        $clinicstore->practice_specialty_other = $request->practice_specialty_other;
        $clinicstore->primary_services = $this->array_implode($request->primary_services);
        $clinicstore->primary_services_other = $request->primary_services_other;
        $clinicstore->website_type  = $request->website_type;
        $clinicstore->microsite_website = $request->ai_website_url;
        $clinicstore->professional_video = $request->professional_video == 'Yes' ? 1 : 0;
        $clinicstore->patient_photos = $request->quality_patient == 'Yes' ? 1 : 0;
        $clinicstore->testimonials  = $request->testimonial_links;
        $clinicstore->usertestimonials   = $request->testimonial_video_urls;
        $clinicstore->google_map  = $request->google_business_page == 'Yes' ? 1 : 0;
        $clinicstore->another_company  = $request->marketing_company;
        $clinicstore->paid_media  = $request->paid_media ? $this->array_implode($request->paid_media) : null;
        $clinicstore->media_budget  = $request->media_budget;
        $clinicstore->primary_selling  = $request->primary_selling_message;
        $clinicstore->promotional_specials  = $request->pricing_promotional == 'Yes' ? 1 : 0;
        $clinicstore->promotional_specials_desc  = $request->pricing_promotional_details;
        $clinicstore->technology  = $request->technologies ? $this->array_implode($request->technologies) : null;;
        $clinicstore->financing  = $request->work_financing_company;
        $clinicstore->financing_options = $request->financing_company ? $this->array_implode($request->financing_company) : null;
        $clinicstore->financing_options_other = $request->financing_company_other;

        // Assigning values for the new fields
        $clinicstore->doctors_biography = $request->doctors_biography ?? '-';
        $clinicstore->doctors_biography_description = $request->doctors_biography_description;
        $clinicstore->doctors_biography_summary = $request->doctors_biography_summary;
        $clinicstore->doctors_biography_summary_description = $request->doctors_biography_summary_description;
        $clinicstore->marketing_message = $request->marketing_message;
        $clinicstore->marketing_message_description = $request->marketing_message_description;

        //Primary doctor info
        $clinicstore->primary_doctor_firstname = $request->primary_doctor_firstname;
        $clinicstore->primary_doctor_lastname = $request->primary_doctor_lastname;
        $clinicstore->primary_doctor_email = $request->primary_doctor_email;
        $clinicstore->primary_doctor_phone = $request->primary_doctor_phone;
        $clinicstore->tracking_phone = $request->tracking_phone;

        $clinicstore->status = "On-boarding";
        $clinicstore->ai_complete = 1;
        // Generate random UUIDs for inbox_id and license_key
        $clinicstore->inbox_id = Str::uuid()->toString(); // Generates a UUID like "2617f83d-9009-4471-b1bc-1db4aa7a52b3"
        $clinicstore->license_key = $clinicstore->inbox_id;
        $clinicstore->automation_campaign = '{"1":"false","2":"false","3":"false","4":"false","5":"false","6":"false","7":"false","8":"false","9":"false","11":"false","12":"false","13":"false","14":"false","15":"false","16":"false","17":"false"}';

        // Step 1: Create the project dynamically
        $projectApiUrl = 'https://api.openai.com/v1/organization/projects';

        $projectResponse = Http::withToken(env('OPENAI_ADMIN_KEY'))
            ->post($projectApiUrl, [
                'name' => $request->practice_name,
            ]);
        
        $clinicstore->save();


        $roleId = 2; // Replace with the desired role ID

        $userIds = User::whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->pluck('id');

        foreach ($userIds as $userId) {
            $clinicStaffRole = new ClinicUser();
            $clinicStaffRole->clinic_id = $clinicstore->id;
            $clinicStaffRole->user_id = $userId;
            $clinicStaffRole->save();
        }

        if(!empty($request->user_id)){
            $userstore = User::find($request->user_id);
        }else{
            $userstore = new User();
            $userstore->name = $request->first_name.' '.$request->last_name;
            $userstore->phone = $request->admin_phone;
            $userstore->email = $request->email;
            $userstore->password = bcrypt($request->password);
            $userstore->save();
        }

        $userStaffRole = new RoleUser();
        $userStaffRole->user_id = $userstore->id;
        $userStaffRole->role_id = 5;
        $userStaffRole->save();

        $userstore->managerClinics()->attach($clinicstore->id);

        $newStaffEmail = $request->staff_emails;

        // Check if $newStaffEmail is not empty and not null
        if (!empty($newStaffEmail)) {
            $arraystaffEmail = explode(',',$newStaffEmail);

            foreach ($arraystaffEmail as $email) {
                $userStaffEmail = new User();
                $userStaffEmail->parent_id = $userstore->id;
                $userStaffEmail->email = $email;
                $userStaffEmail->activation_token = Str::random(80);

                $userStaffEmail->save();

                $activationLink = route('activate', ['token' => $userStaffEmail->activation_token,'staff_emails' => $email]);
                $email = trim($email);
                Mail::to($email)->send(new WelcomeEmail($clinicstore->dr_name, $clinicstore->dr_fullname,$clinicstore->clinic_name, $activationLink));

                $userUnderStaffRole = new RoleUser();
                $userUnderStaffRole->user_id = $userStaffEmail->id;
                $userUnderStaffRole->role_id = 5;
                $userUnderStaffRole->save();

                $clinicStaffRole = new ClinicUser();
                $clinicStaffRole->clinic_id = $clinicstore->id;
                $clinicStaffRole->user_id = $userStaffEmail->id;
                $clinicStaffRole->save();
            }
        }

        $userParentDetailes = User::where('parent_id',$userstore->id)->whereNull('user_email_verified_at')->pluck('activation_token','id')->map(function ($activation_token, $id) {
        return ['id' => $id, 'token' => $activation_token];})->values()->all();

        $activation_Token_Json = json_encode($userParentDetailes);
        $activationToken_Save = User::find($userstore->id);
        $activationToken_Save->activation_token = $activation_Token_Json;
        $activationToken_Save->save();
        $activationTokenNull = User::where('parent_id',$userstore->id)->whereNull('user_email_verified_at')->pluck('activation_token')->toArray();
        // $activationTokenNullUpdate = User::whereIn('activation_token', $activationTokenNull)->update(['activation_token' => null]);
        return response()->json([
            'success' => true,
            'message' => 'Successfully Clinic Created!',
            'data' => ['fullname' => $userstore->name, 'ai_complete' => $clinicstore->ai_complete, 'id'=>$clinicstore->id]
        ], 200);
    }

    public function clinicAllList(Request $request)
    {
        try {
            $user = Auth::user();
            $userid = Auth()->user()->id;
            $search = '';
            if($request->input('search')){
                $search = $request->input('search');
            }
            if($user->roles()->first()['title'] === 'Admin') {
                $clinic = Clinic::where('clinic_name','like','%'.$search.'%')->orWhere('dr_name','like','%'.$search.'%')->get();
                $clinic_array = [];
                foreach ($clinic as $key => $cl){
                    $clinic_array[$key]['id'] = $cl->id;
                    $clinic_array[$key]['practice_name'] = $cl->clinic_name;
                    $clinic_array[$key]['dr_name'] = $cl->dr_name;
                    $clinic_array[$key]['inbox_id'] = $cl->inbox_id;
                    $clinic_array[$key]['ai_complete'] = $cl->ai_complete;
                    $clinic_array[$key]['whereby_link'] = $cl->whereby_link;
                    $clinic_array[$key]['assistant_id'] = $cl->assistant_id;
                }
                return response()->json(['data' => $clinic_array, 'success'=>true],200);
            }else{
                $clinics = Clinic::whereHas('managers', function ($query ) use($userid) {//add select
                    return   $query->where('user_id', '=', $userid );
                })->orderBy('clinic_name', 'ASC')->get();
                $clinic_array = [];
                foreach ($clinics as $key => $cl){
                    $clinic_array[$key]['id'] = $cl->id;
                    $clinic_array[$key]['practice_name'] = $cl->clinic_name;
                    $clinic_array[$key]['dr_name'] = $cl->dr_name;
                    $clinic_array[$key]['inbox_id'] = $cl->inbox_id;
                    $clinic_array[$key]['ai_complete'] = $cl->ai_complete;
                    $clinic_array[$key]['whereby_link'] = $cl->whereby_link;
                    $clinic_array[$key]['assistant_id'] = $cl->assistant_id;
                }
                $clinicCount = $user->managerClinics()->count();
                return response()->json(['data' => $clinic_array, 'multiple_clinic'=>$clinicCount > 1, 'success'=>true],200);
            }
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'ClinicList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function clinicList(Request $request)
    {
        try {
            if($request->get('clinic_list')){
                $clinic = Clinic::where('id',$request->get('clinic_list'))->get();
                return ClinicResource::collection($clinic)->additional(['success'=>true]);
            }else{
                $user = Auth::user();
                return ClinicResource::collection($user->managerClinics()->get())->additional(['success'=>true]);
            }
        } catch (\Exception $ex) {
            $this->exceptionHandle($ex,'ClinicList');
            return response()->json(['success' => false, 'message' => ErrorLog::ExceptionMessage],500);
        }
    }

    public function clinicUpdate(Request $request)
    {
        try{
        $user = Auth::user();
        if($request->section_name == 'staff_member_access'){
                $staff_email = explode(",",$request->new_staff_emails);
                $exist_email = [];
                $sent_email = [];
                $exist_email_flag = 0;

                foreach ($staff_email as $key => $se){
                    if($se){
                        $se = trim($se);
                        if($request->input('clinic_id')){
                            $clinic_id =$request->input('clinic_id');
                            $clinicstore = Clinic::find($clinic_id);
                        }else{
                            $clinicstore = $user->managerClinics()->first();
                        }

                        $user_model = User::where(['email'=>$se])->count();
                        $user_model_deleted_count = User::where(['email'=>$se])->withTrashed()->count();
                        if($user_model == 0){

                            if($user_model_deleted_count > 0){
                                User::where(['email'=>$se])->withTrashed()->restore();
                                $userStaffEmail = User::where(['email'=>$se])->first();
                            }else{
                                $userStaffEmail = new User();
                                $userStaffEmail->parent_id = $user->id;
                                $userStaffEmail->email = $se;
                                $userStaffEmail->save();

                                $userUnderStaffRole = new RoleUser();
                                $userUnderStaffRole->user_id = $userStaffEmail->id;
                                $userUnderStaffRole->role_id = 5;
                                $userUnderStaffRole->save();

                                $clinicStaffRole = new ClinicUser();
                                $clinicStaffRole->clinic_id = $clinicstore->id;
                                $clinicStaffRole->user_id = $userStaffEmail->id;
                                $clinicStaffRole->save();
                            }

                            $pass_token = '';
                            $token_ar_n = [];

                            if($user->activation_token == NULL){
                                $token_ar[$key]['id'] = $userStaffEmail->id;
                                $token_ar[$key]['token'] = Str::random(60);
                                $user->activation_token = json_encode($token_ar);
                                $user->save();
                                $pass_token = $token_ar[$key]['token'];
                            }else{
                                $old_activation_token = json_decode($user->activation_token);
                                $token_ar_n[$key]['id'] = $userStaffEmail->id;
                                $token_ar_n[$key]['token'] = Str::random(60);

                                if($old_activation_token){
                                    $merged_array = array_merge($old_activation_token,$token_ar_n);
                                }else{
                                    $merged_array = $token_ar_n;
                                }

                                $user->activation_token = json_encode($merged_array);
                                $user->save();
                                $pass_token = $token_ar_n[$key]['token'];
                                $userStaffEmail->activation_token = $token_ar_n[$key]['token'];
                                $userStaffEmail->save();
                            }
                            $activationLink = route('activate', ['token' => $token_ar_n[$key]['token'],'staff_emails' => $se]);
                            Mail::to($se)->send(new WelcomeEmail($clinicstore->dr_name, $clinicstore->dr_fullname,$clinicstore->clinic_name, $activationLink));
                            $sent_email[] = $se;
                        }else{
                            $exist_email[] = $se;
                            $exist_email_flag++;
                        }
                    }
                }

                $data['exist_emails'] = $exist_email;
                $data['sent_email'] = $sent_email;
            return response()->json([
                'success' => true,
                'data'=>$data,
                'message' => 'Staff Email Added!'
            ], 200);
        }

        $clinicupdate = $user->managerClinics()->pluck('id');
        $idString = $clinicupdate->implode(',');

        if($request->input('clinic_id')){
            $idString =$request->input('clinic_id');
        }

        if($request->section_name == 'general_details'){
            $validate = Validator::make($request->all(), [
                'practice_email' => 'required|email',
                /*'industry' => 'required',*/
                'website_url' => 'required',
                'practice_name' => 'required',
                //'practice_legal_name' => 'required',
                'phone' => 'required',
           /*     'primary_services' => 'required',*/
            ]);
        }
        if($request->section_name == 'office_details'){
            $validate = Validator::make($request->all(), [
                'address' => 'required',
                'town' => 'required',
                'state' => 'required',
                'zip_code' => 'required',
               /* 'office_hours' => 'required',*/
            ]);
        }

        if($request->section_name == 'general_details' || $request->section_name == 'office_details'){
            if ($validate->fails()) {
                $error = $this->errorMessages($validate);
                return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
            }
        }



        $townziparrayupdate =[
            "town" => $request->town,
            "state" => $request->state,
            "zip_code" => $request->zip_code,
        ];

        $updatedFields = [];

        $clinicupdatedata = Clinic::find($idString);

        $oldValues = $clinicupdatedata->getAttributes();



        switch ($request->section_name) {
            case 'general_details':
                $clinicupdatedata->email = $request->practice_email;
                $clinicupdatedata->type = $request->industry;
                $clinicupdatedata->website = $request->website_url;
                $clinicupdatedata->clinic_name = $request->practice_name;
                $clinicupdatedata->clinic_legal_name = $request->practice_legal_name;
                $clinicupdatedata->office_number = $request->phone;
                $primary_services_array = $request->primary_services;
                $cleaned_primary_services_array = array_filter($primary_services_array);
                $clinicupdatedata->primary_services = implode(',', $cleaned_primary_services_array);
                $clinicupdatedata->primary_services_other = $this->array_implode($request->primary_services_other);
                $clinicupdatedata->practice_management_system = $request->practice_management_system;
                $clinicupdatedata->practice_different = $request->practice_different;
                $clinicupdatedata->current_website_accurate = $request->website_information_detail;
                $clinicupdatedata->practice_specialty_other = $request->practice_specialty_other;
                $practice_specialty_array = $request->practice_specialty;
                $cleaned_practice_specialty_array = array_filter($practice_specialty_array);
                $clinicupdatedata->practice_specialty = implode(',', $cleaned_practice_specialty_array);
                $clinicupdatedata->primary_doctor_firstname = $request->primary_doctor_firstname ?? '';
                $clinicupdatedata->primary_doctor_lastname = $request->primary_doctor_lastname ?? '';
                $clinicupdatedata->primary_doctor_email = $request->primary_doctor_email ?? '';
                $clinicupdatedata->primary_doctor_phone = $request->primary_doctor_phone ?? '';
                $clinicupdatedata->tracking_phone = $request->tracking_phone ?? '';
                $clinicupdatedata->clinic_logo = $request->clinic_logo_name ?? '';
                $clinicupdatedata->nurturing_display_name = $request->nurturing_display_name;
                $clinicupdatedata->scheduling_link = $request->scheduling_link;
                $clinicupdatedata->link1 = $request->link1;
                $clinicupdatedata->link2 = $request->link2;
                $clinicupdatedata->link3 = $request->link3;
                break;

            case 'office_details':
                $clinicupdatedata->address = $request->address;
                $clinicupdatedata->town_state_zip = $this->array_implode($townziparrayupdate);
                $clinicupdatedata->office_name = $request->office_name;
                $clinicupdatedata->office_hours = $request->office_hours;
                $clinicupdatedata->multiple_locations = $request->multiple_locations;
                $clinicupdatedata->multiple_localtion_details = $request->locations;
                $clinicupdatedata->holidays = $request->holidays;
                $clinicupdatedata->multiple_doctors = $request->multiple_doctors;
                break;

            case 'media_content':
                $clinicupdatedata->microsite_website = $request->ai_website_url;
                $clinicupdatedata->google_map = $request->google_my_business_review == 'Yes' ? 1 : 0;
                $clinicupdatedata->testimonials = $request->patient_tesimonial_videos;
                $clinicupdatedata->professional_video = $request->professional_video == 'Yes' ? 1 : 0;
                $clinicupdatedata->patient_photos = $request->quality_patient_after_photos == 'Yes' ? 1 : 0;
                $clinicupdatedata->usertestimonials = $request->testimonial_link;
                $clinicupdatedata->nurture_automation = $request->nurture_automation;
                $clinicupdatedata->doctors_biography = $request->doctors_biography;
                $clinicupdatedata->doctors_biography_description = $request->doctors_biography_description;
                $clinicupdatedata->doctors_biography_summary = $request->doctors_biography_summary;
                $clinicupdatedata->doctors_biography_summary_description = $request->doctors_biography_summary_description;
                $clinicupdatedata->marketing_message = $request->marketing_message;
                $clinicupdatedata->marketing_message_description = $request->marketing_message_description;
                break;

            case 'marketing_and_budget':
                $financing_options_array = $request->financing_options;
                $cleaned_financing_options_array = array_filter($financing_options_array);
                $clinicupdatedata->financing_options = implode(',', $cleaned_financing_options_array);
                $clinicupdatedata->media_budget = str_replace(['$', ','], '', $request->monthly_media_budget);
                $paid_media_array = $request->paid_media;
                $cleaned_paid_media = array_filter($paid_media_array);
                $clinicupdatedata->paid_media = $request->paid_media ? implode(',', $cleaned_paid_media) : null;
                $clinicupdatedata->primary_selling = $request->primary_selling_message;
                $clinicupdatedata->promotional_specials = $request->promotional_specials == 'Yes' ? 1 : 0;
                $clinicupdatedata->promotional_specials_desc = $request->promotional_specials_desc;
                $clinicupdatedata->another_company = $request->separate_marketing_company;
                $technology_array = $request->technology;
                $cleaned_technology_array = array_filter($technology_array);
                $clinicupdatedata->technology = implode(',', $cleaned_technology_array);
                $clinicupdatedata->listtechnology = $request->listtechnology;
                $clinicupdatedata->financing = $request->work_with_finance_company;
                $clinicupdatedata->financing_options_other = $request->financing_company_other;
                $clinicupdatedata->doctors_biography = $request->doctors_biography;
                $clinicupdatedata->doctors_biography_description = $request->doctors_biography_description;
                $clinicupdatedata->doctors_biography_summary = $request->doctors_biography_summary;
                $clinicupdatedata->doctors_biography_summary_description = $request->doctors_biography_summary_description;
                $clinicupdatedata->marketing_message = $request->marketing_message;
                $clinicupdatedata->marketing_message_description = $request->marketing_message_description;
                break;

            case 'callrail_installtion_editing':
                $clinicupdatedata->callrail_installation_script = $request->callrail_installation_script;
                break;

            default:
                $clinicupdatedata->dr_abbreviations = ($request->degree_abbreviation === 'Other') ? $request->degree_abbreviation_other : $request->degree_abbreviation;
                $clinicupdatedata->type = $request->industry;
                $clinicupdatedata->website = $request->website_url;
                $clinicupdatedata->clinic_name = $request->practice_name;
                $clinicupdatedata->email = $request->practice_email;
                $clinicupdatedata->address = $request->address;
                $clinicupdatedata->town_state_zip = $this->array_implode($townziparrayupdate);
                $clinicupdatedata->office_number = $request->phone;
                $clinicupdatedata->office_hours = $request->office_hours;
                $clinicupdatedata->holidays = $request->holidays;
                $clinicupdatedata->multiple_doctors = $request->multiple_doctors;
                $primary_services_array = $request->primary_services;
                $cleaned_primary_services_array = array_filter($primary_services_array);
                $clinicupdatedata->primary_services = implode(',', $cleaned_primary_services_array);
                $clinicupdatedata->practice_management_system = $request->practice_management_system;
                $clinicupdatedata->current_website_accurate = $request->website_information_detail;
                $clinicupdatedata->website_type = $request->website_type;
                $clinicupdatedata->multiple_localtion_details = $request->locations;
                $clinicupdatedata->microsite_website = $request->ai_website_url;
                $clinicupdatedata->professional_video = $request->professional_video == 'Yes' ? 1 : 0;
                $clinicupdatedata->patient_photos = $request->quality_patient_after_photos == 'Yes' ? 1 : 0;
                $clinicupdatedata->testimonials = $request->patient_tesimonial_videos;
                $clinicupdatedata->usertestimonials = $request->testimonial_link;
                $clinicupdatedata->google_map = $request->google_my_business_review == 'Yes' ? 1 : 0;
                $clinicupdatedata->another_company = $request->separate_marketing_company;
                $paid_media_array = $request->paid_media;
                $cleaned_paid_media = array_filter($paid_media_array);
                $clinicupdatedata->paid_media = $request->paid_media ? implode(',', $cleaned_paid_media) : null;
                $clinicupdatedata->media_budget = str_replace(['$', ','], '', $request->monthly_media_budget);
                $clinicupdatedata->primary_selling = $request->primary_selling_message;
                $clinicupdatedata->promotional_specials = $request->promotional_specials == 'Yes' ? 1 : 0;
                $clinicupdatedata->promotional_specials_desc = $request->promotional_specials_desc;
                $technology_array = $request->technology;
                $cleaned_technology_array = array_filter($technology_array);
                $clinicupdatedata->technology = implode(',', $cleaned_technology_array);
                $clinicupdatedata->listtechnology = $request->technology_desc;
                $clinicupdatedata->financing = $request->work_with_finance_company;
                $clinicupdatedata->financing_options_other = $request->financing_company_other;
                $financing_options_array = $request->financing_options;
                $cleaned_financing_options_array = array_filter($financing_options_array);
                $clinicupdatedata->financing_options = implode(',', $cleaned_financing_options_array);
                $clinicupdatedata->status = "";
                break;
        }

        $clinicupdatedata->save();

        $newValues = $clinicupdatedata->getAttributes();

        $changedFields = [];

        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key];
            // Only consider the field if it has changed
            if ($newValue != $oldValue) {
                $changedFields[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];

            }
        }

        $clinicName = $clinicupdatedata->clinic_name;
        if ($user) {
            $userName = $user->name; // Change 'name' to your user's column
            $userEmail = $user->email;
            $userIpAddress = $request->ip();
        }

        $adminEmail = env('MAIL_ADMIN_EMAIL');

        $adminEmailsArray = explode(',', $adminEmail);

        if (!empty($changedFields)) {
            Mail::to($adminEmailsArray)->send(new ClinicUpdated($changedFields,$clinicName,$userName,$userEmail,$userIpAddress));

            return response()->json([
                'success' => true,
                'message' => 'Practice Information updated successfully!'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to update clinic data.'
            ], 500);
        }
        } catch (Excpetion $excpetion){
            return $excpetion;
        }
    }

    public function activeToken(Request $request)
    {
        $staff_emails = $request->staff_emails;
        $activateToken = $request->token;

        if (!$activateToken) {
            return response()->view('errors.404-json', [], 404);
        }
        if (!$staff_emails) {
            return response()->json(['message' => 'Invalid Email Address'], 200);
        }

        $userchild = User::where('email', $staff_emails)->first();


        if(!$userchild){
            return response()->json(['message' => 'Invalid User'], 200);
        }

        $user = User::find($userchild->parent_id);
        if (!$user) {
            return response()->json(['message' => 'Invalid Parent User'], 200);
        }

        $activation_token_array = json_decode($user->activation_token);


        $matched = 0;
        foreach ($activation_token_array as $key => $ata){
            if($activateToken ==  $ata->token){
               // unset($activation_token_array[$key]);
                $matched++;
            }
        }
        if($matched == 0){
            return response()->view('errors.404-json', [], 404);
        }
        $userchild->update([
            'user_email_verified_at' => Carbon::now(),
        ]);
        return redirect()->route('crtx.account.setup',['email' => $staff_emails]);
    }

    public function addStaff(Request $request)
    {
        $staff_emails = $request->staff_emails;

        $user = User::where('email',$staff_emails)->first();

        if ($user == null) {
             return response()->json(['message' => 'This email address is not exist'], 200);
        }
        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'staff_emails' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validate->fails()) {
            $error = $this->errorMessages($validate);
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'errors' => $error]);
        }


        $userstaffemailupdate = User::find($user->id);
        $userstaffemailupdate->name = $request->first_name.' '.$request->last_name;
        $userstaffemailupdate->password = $request->password;
        $userstaffemailupdate->save();

        // First, check if the user already has the specified role (role_id = 5)
        $userStaffRole = RoleUser::where('user_id', $user->id)
        ->where('role_id', 5)
        ->first();

        if (!$userStaffRole) {
            // The user doesn't have the role, so we can save it
            $userStaffRole = new RoleUser();
            $userStaffRole->user_id = $user->id;
            $userStaffRole->role_id = 5;
            $userStaffRole->save();
        }

        // Unset activate token code
        $parentchild = User::where('email', $staff_emails)->first();


        if(!$parentchild){
            return response()->json(['message' => 'Invalid User'], 200);
        }

        $parentuser = User::find($parentchild->parent_id);



        if (!$parentuser) {
            return response()->json(['message' => 'Invalid Parent User'], 200);
        }

        $activation_token_array = json_decode($parentuser->activation_token);

        $activateToken = $user->activation_token;

        foreach ($activation_token_array as $key => $ata){

            if($activateToken ==  $ata->token){
                unset($activation_token_array[$key]);
            }
        }


        $activation_token_array = json_encode($activation_token_array,true);

        $decoded_array = json_decode($activation_token_array, true);

        // Create a new array to store the inner content
        $new_array = [];
        // Loop through the decoded array and add the inner content to the new array
        foreach ($decoded_array as $item) {
            $new_array[] = $item;
        }
        // Encode the new array back to JSON
        $new_json = json_encode($new_array);




        $userstaffemailupdate->update([
            'user_email_verified_at' => Carbon::now(),
        ]);

        $parentuser->update([
            'activation_token' => $new_json,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Linked!'
        ], 200);
    }

    public function deleteStaff(Request $request)
    {
        $staff_emails = $request->email;

        $user = User::where('email',$staff_emails)->first();

        if ($user == null) {
             return response()->json(['message' => 'This email address is not exist'], 200);
        }

        /*$user->deleted_at = Carbon::now();*/
        $user->email = substr_replace($user->email, '-inactive', strpos($user->email, '@'), 0);
        $user->save();

        RoleUser::where('user_id', $user->id)->where('role_id', 5)->delete();

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => 3,
        ]);

        ClinicUser::where('user_id', $user->id)->where('clinic_id', $request->clinic_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff Member Successfully Deleted!'
        ], 200);
    }

    public function array_implode($a)
    {
        if(is_array($a)) {
            return implode(',', $a);
        }else{
            return $a;
        }

    }

    public function registrationEmail(Request $request)
    {

        $firstName = $request->first_name;
        $lastName =  $request->last_name;
        $degree_abbreviation = $request->degree_abbreviation;
        $degree_abbreviation_other = $request->degree_abbreviation_other;
        $email = $request->email;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $industry = $request->industry;
        $website_url = $request->website_url;
        $practice_name = $request->practice_name;
      //  $practice_legal_name = $request->practice_legal_name;
        $practice_email = $request->practice_email;
        $address = $request->address;
        $town = $request->town;
        $state = $request->state;
        $office_name = $request->office_name;
        $zip_code = $request->zip_code;
        $multiple_locations = $request->multiple_locations;
        $is_location_canada = $request->is_location_canada;
        $phone = $request->phone;
        $office_hours = $request->office_hours;
        $staffEmails = $request->staff_emails;

        // $websiteInformationDetail = $request->website_information_detail;
        $practiceManagementSystem = $request->practice_management_system;
        $practiceDifferent = $request->practice_different;
        $micrositeUrl = $request->microsite_url;
        $primary_doctor_firstname = $request->primary_doctor_firstname;
        $primary_doctor_lastname = $request->primary_doctor_lastname;
        $primary_doctor_email = $request->primary_doctor_email;
        $primary_doctor_phone = $request->primary_doctor_phone;
        $tracking_phone = $request->tracking_phone;

        $emailContent =  'Following is the details of a registration attempt from customer '.$firstName.' '.$lastName." :-\n\n";
        $emailContent .= "First Name: " . $firstName . "\n";
        $emailContent .= "Last Name: " . $lastName . "\n";
        $emailContent .= "Degree Abbreviation: " . $degree_abbreviation . "\n";
        if (!empty($degree_abbreviation_other)) {
            $emailContent .= "Degree Abbreviation Other: " . $degree_abbreviation_other . "\n";
        }
        $emailContent .= "Email: " . $email . "\n";
        $emailContent .= "Industry: " . $industry . "\n";
        $emailContent .= "Website URL: " . $website_url . "\n";
        $emailContent .= "Primary Doctor First Name: " . $primary_doctor_firstname . "\n";
        $emailContent .= "Primary Doctor Last Name: " . $primary_doctor_lastname . "\n";
        $emailContent .= "Primary Doctor Email  Address: " . $primary_doctor_email . "\n";
        $emailContent .= "Primary Doctor Phone: " . $primary_doctor_phone . "\n";
        $emailContent .= "Tracking Number Forwards To: " . $tracking_phone . "\n";
        $emailContent .= "Practice Name: " . $practice_name . "\n";
       // $emailContent .= "Practice Legal Name: " . $practice_legal_name . "\n";
        $emailContent .= "Practice Email: " . $practice_email . "\n";
        $emailContent .= "Address: " . $address . "\n";
        $emailContent .= "Town: " . $town . "\n";
        $emailContent .= "State: " . $state . "\n";
        $emailContent .= "Zip Code: " . $zip_code . "\n";
        $emailContent .= "Office Name: " . $office_name . "\n";
        $emailContent .= "Multiple Locations: " . $multiple_locations . "\n";
        $emailContent .= "Is location Canada? : " . $is_location_canada . "\n";
        $emailContent .= "Phone: " . $phone . "\n";
        $emailContent .= "Office Hours: " . $office_hours . "\n";
        $emailContent .= "Staff Emails: " . $staffEmails . "\n";
        $emailContent .= "Practice Management System: " . $practiceManagementSystem . "\n";
        $emailContent .= "Practice Different: " . $practiceDifferent . "\n";
        $newStaffEmail = env('SIGNUP_EMAIL');

        // Check if $email is null or empty
        if (empty($email)) {
            // Set $primary_doctor_email as the recipient email
            $email = $primary_doctor_email;
        }

        try {
            // Send the plain text email
            Mail::raw($emailContent, function (Message $message) use ($newStaffEmail) {
                $message->to($newStaffEmail)
                    ->subject('Registration Field Details');
            });
            $name = $firstName .' '. $lastName;

            Mail::send('emails.personalize_onboarding', ['name'=>$name], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Welcome to CRTX! Next Steps to Complete Your Setup');
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.'
            ], 200);
        } catch (Exception $e) {
            // An error occurred while sending the email
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    public function aiSetupEmail(Request $request)
    {
        if($request->new_clinic_id){
            $clinic = Clinic::find($request->new_clinic_id);
            if ($clinic){
                $clinic->multiple_doctors = $request->multiple_doctors ?? '';
                if($request->multiple_doctors == 'Yes'){
                    $clinic->multiple_doctors = $request->multiple_doctors_details ?? '';
                }
                $clinic->current_website_accurate = $request->website_information_accurate ?? '';
                $clinic->testimonials = $request->testimonial_links ?? '';
                $clinic->usertestimonials = $request->testimonial_video_urls ?? '';
                $clinic->financing = $request->work_financing_company ?? '';
                $clinic->financing_options = $this->array_implode($request->financing_company);
                $clinic->promotional_specials_desc = $request->special_pricing ?? '';
                $clinic->promotional_specials = ($clinic->promotional_specials_desc) ? 1 : '';
                $clinic->another_company = $request->marketing_company ?? '';
                $clinic->media_budget = str_replace(['$', ','], '', $request->media_budget) ?? '';
                $clinic->financing_options_other = $request->financing_company_other ?? '';
                $clinic->save();
            }
        }
        $firstName = $clinic->primary_doctor_firstname;
        $lastName =  $clinic->primary_doctor_lastname;

        $websiteInformationAccurate = $request->website_information_accurate;
        $testimonial_links = $request->testimonial_links ?? '';
        $testimonial_video_urls = $request->testimonial_video_urls ?? '';
        $work_financing_company = $request->work_financing_company ?? '';
        $special_pricing = $request->special_pricing ?? '';
        $marketing_company = $request->marketing_company ?? '';
        $media_budget = $request->media_budget ?? '';
        $financing_company = $this->array_implode($request->financing_company) ?? '';
        $financing_company_other = $request->financing_company_other ?? '';

        $emailContent =  'Following is the details of a Marketing setup registration attempt from customer '.$firstName.' '.$lastName." :-\n\n";
        $emailContent .= "First Name: " . $firstName . "\n";
        $emailContent .= "Last Name: " . $lastName . "\n";
        $emailContent .= "Marketing Setup Form Details: \n\n\n\n";

        $emailContent .= "Multiple Doctors: " . $request->multiple_doctors . "\n";
        $emailContent .= "Multiple Doctors Details: " . $request->multiple_doctors_details . "\n";
        $emailContent .= "Website Information Accurate: " . $websiteInformationAccurate . "\n";
        $emailContent .= "work financing company:".$work_financing_company . "\n";
        $emailContent .= "financing company:".$financing_company . "\n";
        $emailContent .= "financing company other: ".$financing_company_other . "\n";
        $emailContent .= "special_pricing: ".$special_pricing . "\n";
        $emailContent .= "marketing company:".$marketing_company . "\n";
        $emailContent .= "media budget:".$media_budget . "\n";

        $newStaffEmail = env('SIGNUP_EMAIL');


        try {
            // Send the plain text email
            Mail::raw($emailContent, function (Message $message) use ($newStaffEmail) {
                $message->to($newStaffEmail)
                    ->subject('Marketing Setup Registration Field Details'); // Replace with your email subject
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.'
            ], 200);
        } catch (Exception $e) {
            // An error occurred while sending the email
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    public function resendToken(Request $request){
        try {
            $input = $request->all();
            if($input['email']){
                $user = User::where(['email'=>$input['email']])->first();
                if($user){
                        if(!$user->password){
                            $clinicUser = ClinicUser::where(['user_id'=>$user->id])->first();
                            $clinicId = '';
                            if($request->input('clinic_id')){
                                $clinicId = explode(',',$request->input('clinic_id'));
                            }
                            if ($clinicUser || $clinicId){
                                if($clinicId){
                                    $clinic_id = $clinicId;
                                }else{
                                    $clinic_id = $clinicUser->clinic_id;
                                }
                                $clinic = Clinic::find($clinic_id)->first();
                                if($clinic){
                                    $activationLink = route('activate', ['token' => $user->activation_token,'staff_emails' => $user->email]);
                                    Mail::to($user->email)->send(new WelcomeEmail((isset($clinic->dr_name)) ?? '-', $clinic->dr_fullname ?? '-',$clinic->clinic_name ?? '-', $activationLink));
                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Resend activation token to user.'
                                    ], 200);
                                }else{
                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Clinic not found.'
                                    ], 200);
                                }
                            }else{
                                return response()->json([
                                    'success' => true,
                                    'message' => 'Clinic not found.'
                                ], 200);
                            }
                        }else{
                            return response()->json([
                                'success' => true,
                                'message' => 'User is already verified.'
                            ], 200);
                        }
                }else{
                    return response()->json([
                        'success' => true,
                        'message' => 'User not found.'
                    ], 200);
                }
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Email is missing.'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage()
            ], 200);

        }
    }

    public function getNurturingTemplate(Request $request) {
        $clinic_id = $request->input('clinic_id', ''); // Use the default value directly here

        $autosequencData = DB::table('automationsequence')->whereNull('status_id')->where('dayinterval', '!=', 0)->get();

        $records = [];
        $clinicdata = Clinic::find($clinic_id);
        $clinicValues = $clinicdata->getAttributes();
        $nurturing_display_name = '';
        $scheduling_link = '';
        if($clinicValues['nurturing_display_name'] != ''){
            $nurturing_display_name = $clinicValues['nurturing_display_name'];
        }
        if($clinicValues['scheduling_link'] != ''){
            $scheduling_link = $clinicValues['scheduling_link'];
        }

        foreach ($autosequencData as $value) {
            $manageData = $this->checkRecordExists($clinic_id, $value->dayinterval);
            if($nurturing_display_name != ''){
                $value->text_template = str_replace('Grace',$nurturing_display_name,$value->text_template);
            }
            if($scheduling_link != ''){
                $value->text_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->text_template);
                $value->email_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->email_template);
            }

            $templateService = new TemplateService();
            $value = $templateService->replacePlaceholders($value, $clinicValues);

            $record = [
                'dayinterval' => $manageData['dayinterval'] ?? $value->dayinterval,
                'text_template' => $manageData['text_template'] ?? $value->text_template,
                'email_subject' => $manageData['email_subject'] ?? $value->email_subject,
                'email_template' => $manageData['email_template'] ?? $value->email_template,
            ];
            $records[] = (object) $record;
        }
        $template = collect($records);


        return response()->json([
            'success' => true,
            'nurturing_templates' => $template
        ], 200);
    }

    public function checkRecordExists($clinicid, $dayinterval)
    {

        $manageRecord = ManageTemplate::where('clinic_id', $clinicid)
            ->where('dayinterval', $dayinterval)
            ->whereNull('status_id')
            ->first();


        $clinicdata = Clinic::find($clinicid);
        $clinicValues = $clinicdata->getAttributes();

        $templateService = new TemplateService();
        $manageRecord = $templateService->replacePlaceholders($manageRecord, $clinicValues);

        if ($manageRecord) {
            return [
                'dayinterval' => $manageRecord->dayinterval,
                'text_template' => $manageRecord->text_template,
                'email_subject' => $manageRecord->email_subject,
                'email_template' => $manageRecord->email_template,
            ];
        }

        // If no record is found, you can return null or an empty array as needed.
        return [];
    }

    public function manageTemplate(Request $request)
    {

        $clinicId = $request->clinicid;
        $statusId = $request->statusId;
        $dayInterval = $request->dayinterval;
        $type = $request->type; // New parameter to specify the type of update
        $subject = $request->email_subject ?? null;
        $content = $request->content ?? 'hold'; // Assuming 'content' holds the value to update


        // Ensure 'type' and 'content' are provided
        if (!$type || !$content) {
            return response()->json([
                'success' => false,
                'message' => 'Type or content missing in the request',
            ]);
        }

        // Construct the data to update or insert
        $dataToUpdate = [
            $type => $content,
            'updated_at' => Carbon::now(),
        ];


        if ($type == "email_template" && $subject !== null) {
            $dataToUpdate['email_subject'] = $subject;
        }

        try {
            // Update or insert data into the database
            $existingRecord = ManageTemplate::where('clinic_id', $clinicId)
                ->where('status_id', $statusId)
                ->where('dayinterval', $dayInterval)
                ->first();

            if ($existingRecord) {
                // Update existing record
                $existingRecord->update($dataToUpdate);
            } else {
                // Insert new record
                $dataToUpdate['clinic_id'] = $clinicId;
                $dataToUpdate['status_id'] = $statusId;
                $dataToUpdate['dayinterval'] = $dayInterval;
                $dataToUpdate['created_at'] = Carbon::now();

                ManageTemplate::create($dataToUpdate);
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' updated successfully',
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database errors)
            return response()->json([
                'success' => false,
                'message' => 'Error updating data: ' . $e->getMessage(),
            ]);
        }
    }


    public function uploadLogo(Request $request)
    {

        $request->validate([
            'clinic_id' => 'required',
            'clinic_logo' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $clinic = Clinic::find($request->clinic_id);

        if (!$clinic) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        if ($request->hasFile('clinic_logo')) {
            // Delete existing profile picture if any
            $clinic->clearMediaCollection('clinic_logo');
            // Add the new profile picture to the collection
            $clinic->addMediaFromRequest('clinic_logo')->toMediaCollection('clinic_logo');
        }

        // Add the new clinic logo to the collection
       // $clinic->addMediaFromRequest('clinic_logo')->toMediaCollection('clinic_logo');

        return response()->json([
            'success' => true,
            'message' => 'Clinic logo uploaded successfully',
        ]);
    }

    public function uploadImage(Request $request)
    {

        // Validate the request
        $request->validate([
            'upload' => 'required|image|max:2048', // Example validation rules
        ]);

        // Store the uploaded file
        $path = $request->file('upload')->store('images', 'public');
        $url = Storage::disk('public')->url($path);


        // Return the path to the uploaded file
        return response()->json(['path' => $path,'url' => $url]);
    }

    public function updateToggles(Request $request)
    {
        $clinic_id = $request->input('clinic_id');
        $key = $request->input('key');
        $existingData = Clinic::find($clinic_id)->automation_campaign;
        // Decode the JSON string into an associative array
        $data = json_decode($existingData, true);
        // Toggle the status of the specified key
        if (isset($data[$key]) && $data[$key] == 'true') {
            $data[$key] = 'false';
        } elseif (isset($data[$key]) && $data[$key] == 'false') {
            $data[$key] = 'true';
        }
        // Encode the array back to a JSON string
        $updatedData = json_encode($data);
        Clinic::updateOrInsert(
            ['id' => $clinic_id],
            ['automation_campaign' => $updatedData]
        );

        $updatedAutomationCampaign = Clinic::where('id', $clinic_id)->select('automation_campaign')->first();
        // Decode the JSON data without backslashes
        $decodedData = json_decode($updatedAutomationCampaign->automation_campaign, true);

        return response()->json(['message' => 'Status toggled successfully', 'data' => $decodedData]);

    }

        public function getAutomationCampaign(Request $request)
    {
        $clinic_id = $request->input('clinic_id');

        if (!$clinic_id) {
            return response()->json(['message' => 'Clinic ID is required'], 400);
        }

        // Assuming you want to retrieve a specific clinic with its automation_campaign status
        $clinic = Clinic::find($clinic_id);

        if (!$clinic) {
            return response()->json(['message' => 'Clinic not found'], 404);
        }

        $automation_campaign = json_decode($clinic->automation_campaign, true);

        // Convert the associative array to indexed array using array_values
        $indexedArray = ($automation_campaign !== null) ? array_values($automation_campaign) : [];
        $indexedKeys = ($automation_campaign !== null) ? array_keys($automation_campaign) : [];

        // Use array_map to iterate over the indexed array
        $filteredIds = explode(',', env('STATUS_IDS'));

        $joinedData = array_map(function ($status, $id) use ($filteredIds) {
            // Check if the ID is in the filtered IDs array
            if (in_array($id, $filteredIds)) {
                $crmStatus = CrmStatus::withTrashed()->find($id);
                return [
                    'id' => $crmStatus->id,
                    'name' => $crmStatus ? $crmStatus->name : 'Unknown',
                    'status' => $status,
                ];
            }
        }, $indexedArray, $indexedKeys);

        // Remove any null values (where the ID was not found in the filtered IDs)
        $joinedData = array_filter($joinedData);



        $data = [
            'clinic_id' => $clinic->id,
            'statuses' => $joinedData,
        ];


        return response()->json($data);
    }

    public function getAutomationTemplate(Request $request) {
        $clinic_id = $request->input('clinicid'); // Use the default value directly here
        $status_id = $request->input('status_id'); // Use the default value directly here
        $autosequencData = DB::table('automationsequence')->where('status_id', $status_id)->select('*')->orderBy('id')->get();

        $records = [];
        $clinicdata = Clinic::find($clinic_id);
        $statusName = CrmStatus::where('id', $status_id)->value('name');
        $clinicValues = $clinicdata->getAttributes();
        $nurturing_display_name = '';
        $scheduling_link = '';
        if($clinicValues['nurturing_display_name'] != ''){
            $nurturing_display_name = $clinicValues['nurturing_display_name'];
        }
        if($clinicValues['scheduling_link'] != ''){
            $scheduling_link = $clinicValues['scheduling_link'];
        }
        foreach ($autosequencData as $value) {
            $manageData = $this->checkRecordExistsAutomation($clinic_id, $status_id, $value->dayinterval);



            if($nurturing_display_name != ''){
                $value->text_template = str_replace('Grace',$nurturing_display_name,$value->text_template);
            }
            if($scheduling_link != ''){
                $value->text_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->text_template);
                $value->email_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->email_template);
            }
            $templateService = new TemplateService();
            $value = $templateService->replacePlaceholders($value, $clinicValues);

            $record = [
                'id'          => $manageData['id'] ?? $value->id,
                'dayinterval' => $manageData['dayinterval'] ?? $value->dayinterval,
                'status_id' => $manageData['status_id'] ?? $value->status_id,
                'text_template' => $manageData['text_template'] ?? $value->text_template,
                'email_subject' => $manageData['email_subject'] ?? $value->email_subject,
                'email_template' => $manageData['email_template'] ?? $value->email_template,
                'status_name' => $statusName
            ];
            $records[] = (object) $record;

        }

         // Fetch records from ManageTemplate table where status_id and clinic_id match
            $additionalRecords = ManageTemplate::where('clinic_id', $clinic_id)
            ->where('status_id', $status_id)
            ->whereNotIn('dayinterval', $autosequencData->pluck('dayinterval'))
            ->orderBy('id')
            ->get();

        foreach ($additionalRecords as $record) {
        $records[] = $record;
        }
        $template = collect($records);
        return response()->json([
            'success' => true,
            'nurturing_templates' => $template
        ], 200);
    }

    public function checkRecordExistsAutomation($clinicid,$statusId, $dayinterval)
    {

        $manageRecord = ManageTemplate::where('clinic_id', $clinicid)
            ->where('status_id', $statusId)
            ->where('dayinterval', $dayinterval)
            ->orderBy('id')
            ->first();


        $clinicdata = Clinic::find($clinicid);
        $clinicValues = $clinicdata->getAttributes();

        $templateService = new TemplateService();
        $manageRecord = $templateService->replacePlaceholders($manageRecord, $clinicValues);


        if ($manageRecord) {
            return [
                'id'          =>  $manageRecord->id,
                'dayinterval' => $manageRecord->dayinterval,
                'status_id'   => $manageRecord->status_id,
                'text_template' => $manageRecord->text_template,
                'email_subject' => $manageRecord->email_subject,
                'email_template' => $manageRecord->email_template,
            ];
        }

        // If no record is found, you can return null or an empty array as needed.
        return [];
    }


    public function manageautomationTemplate(Request $request)
    {
        $clinicId = $request->clinicid;
        $statusId = $request->status_id;
        $dayInterval = $request->dayinterval;
        $text_template = $request->text_template;
        $email_subject = $request->email_subject;
        $email_template = $request->email_template;
        $id = $request->id;


        try {
            // Update or insert data into the database
                $manageTemplate = ManageTemplate::firstOrCreate(['id' => $id]);

                // Update the fields with the provided data
                $manageTemplate->clinic_id = $clinicId;
                $manageTemplate->status_id = $statusId;
                $manageTemplate->dayinterval = $dayInterval;
                $manageTemplate->text_template = $text_template;
                $manageTemplate->email_subject = $email_subject;
                $manageTemplate->email_template = $email_template;

                // Save the changes to the database
                $manageTemplate->save();


                    #print query here to get updated data from database

                    return response()->json([
                        'success' => true,
                        'message' => 'Data updated successfully',
                    ]);
                } catch (\Exception $e) {
                    // Handle any exceptions (e.g., database errors)
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating data: ' . $e->getMessage(),
                    ]);
                }
    }

    public function getImmediateTemplate(Request $request) {
        $clinic_id = $request->input('clinicid'); // Use the default value directly here
        $autosequencData = DB::table('automationsequence')->where('dayinterval', 0)->select('*')->orderBy('id')->get();

        $records = [];
        $clinicdata = Clinic::find($clinic_id);
        $clinicValues = $clinicdata->getAttributes();
        $nurturing_display_name = '';
        $scheduling_link = '';



        foreach ($autosequencData as $value) {
            $manageData = $this->checkRecordExistsImmediate($clinic_id,  0);

            if($nurturing_display_name != ''){
                $value->text_template = str_replace('Grace',$nurturing_display_name,$value->text_template);
            }
            if($scheduling_link != ''){
                $value->text_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->text_template);
                $value->email_template = str_replace('https://calendly.com/implanthotline/15min',$scheduling_link,$value->email_template);
            }
            $templateService = new TemplateService();
            $value = $templateService->replacePlaceholders($value, $clinicValues);

            $record = [
                'id'          => $manageData['id'] ?? $value->id,
                'dayinterval' => 0,
                'status_id' => null,
                'text_template' => $manageData['text_template'] ?? $value->text_template,
                'email_subject' => $manageData['email_subject'] ?? $value->email_subject,
                'email_template' => $manageData['email_template'] ?? $value->email_template,

            ];
            $records[] = (object) $record;

        }

        $template = collect($records);
        return response()->json([
            'success' => true,
            'nurturing_templates' => $template
        ], 200);
    }

    public function checkRecordExistsImmediate($clinicid, $dayinterval)
    {
        $manageRecord = ManageTemplate::where('clinic_id', $clinicid)
            ->where('dayinterval', $dayinterval)
            ->orderBy('id')
            ->first();

        $clinicdata = Clinic::find($clinicid);
        $clinicValues = $clinicdata->getAttributes();

        $templateService = new TemplateService();
        $manageRecord = $templateService->replacePlaceholders($manageRecord, $clinicValues);


        if ($manageRecord) {
            return [
                'id'          =>  $manageRecord->id,
                'dayinterval' => $manageRecord->dayinterval,
                'status_id'   => $manageRecord->status_id,
                'text_template' => $manageRecord->text_template,
                'email_subject' => $manageRecord->email_subject,
                'email_template' => $manageRecord->email_template,
            ];
        }

        // If no record is found, you can return null or an empty array as needed.
        return [];
    }


    public function manageimmediateTemplate(Request $request)
    {
        $clinicId = $request->clinicid;
        $statusId = $request->status_id;
        $dayInterval = $request->dayinterval;
        $text_template = $request->text_template;
        $email_subject = $request->email_subject;
        $email_template = $request->email_template;
        $id = $request->id;


        try {
            // Update or insert data into the database
                $manageTemplate = ManageTemplate::firstOrCreate(['id' => $id]);

                // Update the fields with the provided data
                $manageTemplate->clinic_id = $clinicId;
                $manageTemplate->status_id = $statusId;
                $manageTemplate->dayinterval = $dayInterval;
                $manageTemplate->text_template = $text_template;
                $manageTemplate->email_subject = $email_subject;
                $manageTemplate->email_template = $email_template;

                // Save the changes to the database
                $manageTemplate->save();


                    #print query here to get updated data from database

                    return response()->json([
                        'success' => true,
                        'message' => 'Data updated successfully',
                    ]);
                } catch (\Exception $e) {
                    // Handle any exceptions (e.g., database errors)
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating data: ' . $e->getMessage(),
                    ]);
                }
    }

    public function toggleimmediateTemplateStatus(Request $request)
    {
        // Fetch the clinic by ID
        $clinic = Clinic::findOrFail($request->id);

        $fieldToUpdate = '';
        if ($request->templateType === 'sms') {
            $fieldToUpdate = 'immediate_sms';
        } elseif ($request->templateType === 'email') {
            $fieldToUpdate = 'immediate_mail';
        }

        // Update the specified field based on the active/inactive state
        if ($fieldToUpdate) {
            $clinic->$fieldToUpdate = $request->isActive ? 1 : 0;
        }


        // Save the changes
        $clinic->save();

        return response()->json([
            'success' => true,
            'message' => 'Template status updated successfully.',
        ]);
    }

public function getToggleValues(Request $request)
    {
        // Find the clinic by ID
        $clinic = Clinic::findOrFail($request->clinic_id);

        // Fetch immediate template values (immediate_sms and immediate_mail) for the clinic
        $templates = Clinic::where('id', $clinic->id)
            ->select('immediate_sms', 'immediate_mail')
            ->first();

        // Return the templates as JSON response
        return response()->json($templates);
    }

    public function sendVerificationEmail($id)
    {
        $emailVerificationService = new DnsEmailVerificationService();
        return $emailVerificationService->sendVerificationEmail($id);
    }

    public function getClinic($id)
    {
        $getDnsVerificationService = new GetDnsVerificationService();
        $data = $getDnsVerificationService->getDnsVerification($id);
        return response()->json($data);
    }

    /*
        This function is getting the aws SNS notification from https subscriber
        From the notification it's getting info of email content from s3 bucket
    */
    public function emailNotification(Request $request): \Illuminate\Http\JsonResponse
    {


        // Handle the SNS notification {Domain}/api/clinic/email-notification
        $data = $request->getContent();
        $data1 = json_decode($data);

        //\Log::info('SNS Request Headers:', ['headers' => $request->headers->all()]);
        //\Log::info('Raw SNS Payload:', ['body' => $request->getContent()]);

        if($data1){
            //\Log::info('Decoded SNS Notification:', ['data' => $data1]);
            //\Log::info('SNS Notification received: ', ['data' => $request->all()]);

            if (isset($data1->Records[0])) {
                $bucketName = $data1->Records[0]->s3->bucket->name ?? 'N/A';
                $objectKey = $data1->Records[0]->s3->object->key ?? 'N/A';
                $objectKey = urldecode($objectKey);
                $emails = $this->awsEmailService->fetchEmailContent($bucketName, $objectKey);


                preg_match('/<(.+?)>/', $emails['from'], $matches);

                if (isset($matches[1])) {
                    $leadEmail = $matches[1];
                } else {
                    echo "No email found";
                exit;
                }

                // Fetch lead and clinic information
                $lead = CrmCustomer::with(['clinic'])->where('crm_customers.email', $leadEmail)->get()->last();

                if ($lead) {
                    $leadId = $lead->id;
                    $clinic = Clinic::select('clinic_name', 'email_assistant','id','email_id','chat_api_key')
                        ->where('id', '=', $lead->clinic_id)
                        ->where('auto_reply_email','=',true)
                        ->first();

                    // Check if clinic data is retrieved and auto_reply_email is enabled
                    if ($clinic) {

                        // Use openAIAutoSmsService to generate a response based on the email's body
                        $userMessage = $emails['body'];
                        $assistantId = $clinic->email_assistant;
                        $chatApiKey  = $clinic->chat_api_key;
                        $newAIMessage = $this->openAIAutoService->generateAIResponseWithThread($userMessage, $assistantId, $chatApiKey);

                        // Format original email content to include in the reply (quoted format)
                        $originalEmailFormatted = "
                        <br><br>
                        ---------- Original Message ----------
                        <br>
                        From: {$emails['from']} - {$leadEmail}<br>
                        To: " . implode(', ', $emails['to']) . "<br>
                        Sent: {$emails['created_at']}<br>
                        Subject: {$emails['subject']}<br><br>
                        {$emails['body']}
                        ";

                        // Combine the AI-generated response with the original email
                        $email_template = nl2br($newAIMessage) . $originalEmailFormatted;
                        $receiveremail = $leadEmail;
                        $email_subject = "Re: " . $emails['subject'];

                        // Check clinic SMTP settings and send the email
                        if ($lead->clinic->smtpMailer == '') {
                            // Send email using default SMTP settings
                            Mail::mailer('smtp')->send([], [], function ($message) use ($email_template, $receiveremail, $email_subject) {
                                $message->to($receiveremail)
                                    ->subject($email_subject)
                                    ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                    ->setBody($email_template, 'text/html');
                            });
                        } else {
                            // Custom SMTP settings
                            config([
                                'mail.mailers.smtp.host' => $lead->clinic->smtpServer,
                                'mail.mailers.smtp.port' => $lead->clinic->smtpPort,
                                'mail.mailers.smtp.encryption' => 'tls',
                                'mail.mailers.smtp.username' => $lead->clinic->smtpUsername,
                                'mail.mailers.smtp.password' => $lead->clinic->smtpPassword,
                            ]);

                            // Send email based on lead center
                            if ($lead->clinic->lead_center == "Yes") {
                                Mail::purge();
                                Mail::send([], [], function ($message) use ($email_template, $receiveremail, $email_subject) {
                                    $message->to($receiveremail)
                                        ->subject($email_subject)
                                        ->from('consultation@thedentalimplanthotline.com', 'The Dental Implant Hotline')
                                        ->setBody($email_template, 'text/html');
                                });
                            } else {
                                Mail::purge();
                                Mail::send([], [], function ($message) use ($email_template, $receiveremail, $email_subject, $lead) {
                                    $message->to($receiveremail)
                                        ->subject($email_subject)
                                        ->from("admin@" . $lead->clinic->microsite_website, $lead->clinic->clinic_name)
                                        ->replyTo($lead->clinic->email_id, $lead->clinic->clinic_name)
                                        ->setBody($email_template, 'text/html');
                                });
                            }
                        }

                        // Enter sent email details into the EmailSent table
                        EmailSent::create([
                            'user_id' => '1',
                            'clinic_id' => $clinic->id,
                            'from' => $clinic->email_id,
                            'subject' => $email_subject,
                            'to' => is_array($receiveremail) ? implode(',', $receiveremail) : $receiveremail,                           
                            'body' => $email_template,                            
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Log the sent email in CRM Notes
                        $CrmNote = new CrmNote;
                        $CrmNote->note = "Reply Email Sent";
                        $CrmNote->user_id = 1;  // Adjust the user ID as necessary
                        $CrmNote->customer_id = $leadId;
                        $CrmNote->save();
                    }
                }

                return response()->json(['message' => 'SNS Notification received.','data'=>$emails], 200);
            } else {
                \Log::warning('SNS Notification received but no Records found.');
                return response()->json(['message' => 'SNS Notification received but no Records found.'], 200);
            }
        } else {
            \Log::error('Failed to decode SNS Payload', ['payload' => $data]);
            return response()->json(['message' => 'SNS Notification received but empty or invalid payload.'], 200);
        }
    }


    public function updateAutoReply(Request $request, $clinicId)
    {
        $validatedData = $request->validate([
            'auto_reply_email' => 'required|boolean',
            'auto_reply_sms' => 'required|boolean',
        ]);

        $clinic = Clinic::find($clinicId);

        if (!$clinic) {
            return response()->json(['message' => 'Clinic not found'], 404);
        }
        $clinic->auto_reply_email = $validatedData['auto_reply_email'];
        $clinic->auto_reply_sms = $validatedData['auto_reply_sms'];
        $clinic->save();

        return response()->json([
            'message' => 'Auto reply updated successfully',
            'data' => [
                'auto_reply_email' => $clinic->auto_reply_email,
                'auto_reply_sms' => $clinic->auto_reply_sms,
            ],
        ]);
    }

    public function getAutoReply($clinicId)
    {
        $clinic = Clinic::where('id',$clinicId)
            ->select('auto_reply_email','auto_reply_sms')
            ->first();

        return response()->json([
            'data' => $clinic
        ]);
    }


}
