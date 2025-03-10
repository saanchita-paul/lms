<?php

namespace App\Http\Resources\CRTX;

use App\Models\ClinicUser;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Http\Resources\CRTX\ClinicDnsRecordResource;

class ClinicResource extends JsonResource
{


    public function __construct($resource)
    {
        // Ensure dnsRecords relationship is loaded
        $resource->loadMissing('dnsRecords');
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fields = ['','',''];
        if (isset($this->town_state_zip)) {
            if (substr_count($this->town_state_zip, ',') == 2) {
                $fields = explode(',', $this->town_state_zip);
            }elseif (substr_count($this->town_state_zip, ',') == 1) {
                $fields = explode(',', $this->town_state_zip);
                $fields_next = explode(' ', $fields[1]);
                $fields[1] = $fields_next[1];
                $fields[2] = $fields_next[2];
            }
        }

        $town = ($fields[0]) ? $fields[0] : '';
        $state = ($fields[1]) ? $fields[1] : '';
        $zip_code = ($fields[2]) ? $fields[2] : '';



//        $staff_email = User::select('email','user_email_verified_at')->where(['parent_id'=>$this->pivot->user_id])->get();
        $clinicUsers = ClinicUser::where(['clinic_id' => $this->id])->with('user', 'userRole')->get();
        $staff_email = [];

        foreach ($clinicUsers as $key => $clinicUser) {
            // Check if 'user' and 'userRole' properties are not null
            if ($clinicUser->user && $clinicUser->userRole) {
                if ($clinicUser->userRole['role_id'] === 5) {
                    if (strpos($clinicUser->user['email'], 'microsite.com') === false) {
                        $staff_email[$key]['id'] = $clinicUser->user['id'];
                        $staff_email[$key]['email'] = $clinicUser->user['email'];
                        $staff_email[$key]['user_email_verified_at'] = $clinicUser->user['user_email_verified_at'];
                        $staff_email[$key]['password_null'] = $clinicUser->user['password'] == null;
                        $staff_email[$key]['name_null'] = $clinicUser->user['name'] == null;
                    }
                }
            }
        }

        $monthly_media_budget = 0;
        if($this->media_budget){
            $monthly_media_budget = "$ ".number_format($this->media_budget, 0, '.', ',');
        }


        return [
            'id' => $this->id,
            'dr_name' => $this->dr_name,
            'degree_abbreviation' => $this->dr_abbreviations,
            'industry' => $this->type,
            'website_url' => $this->website,
            'practice_name' => $this->clinic_name,
            'practice_legal_name' => $this->clinic_legal_name,
            'practice_email' => $this->email,
            'primary_doctor_firstname' => $this->primary_doctor_firstname,
            'primary_doctor_lastname' => $this->primary_doctor_lastname,
            'primary_doctor_email' => $this->primary_doctor_email,
            'primary_doctor_phone' => $this->primary_doctor_phone,
            'tracking_phone' => $this->tracking_phone,
            'address' => $this->address,
            'town' => $town,
            'state' => $state,
            'zip_code' => $zip_code,
            'phone' => $this->office_number,
            'office_name' => $this->office_name,
            'office_hours' => $this->office_hours,
            'holidays' => $this->holidays,
            'multiple_doctors' => $this->multiple_doctors,
            'practice_specialty' => explode(',', $this->practice_specialty),
            'practice_specialty_other' => $this->practice_specialty_other,
            'primary_services' => explode(',', $this->primary_services),
            'practice_management_system' => $this->practice_management_system,
            'practice_different' => $this->practice_different,
            'website_information_detail' => $this->current_website_accurate,
            'website_type' => $this->website_type,
            'ai_website_url' => $this->microsite_website,
            'professional_photography' => $this->patient_photos == 1? 'Yes' : 'No',
            'professional_video' => $this->professional_video == 1? 'Yes' : 'No',
            'quality_patient_after_photos' => $this->patient_photos == 1? 'Yes' : 'No',
            'patient_testimonial_videos' => $this->testimonials,
            'testimonial_link' => $this->usertestimonials,
            'google_my_business_review' => $this->google_map == 1? 'Yes' : 'No',
            'separate_marketing_company' => $this->another_company,
            'paid_media' => explode(',', $this->paid_media),
            'monthly_media_budget' => $monthly_media_budget,
            'primary_selling_message' => $this->primary_selling,
            'promotional_specials' => $this->promotional_specials == 1 ? 'Yes' : 'No',
            'promotional_specials_desc' => $this->promotional_specials_desc,
            'technology' => explode(',', $this->technology),
            'technology_desc' => $this->listtechnology,
            'work_with_finance_company' => $this->financing,
            'financing_options' => explode(',', $this->financing_options),
            'other_finance_company' => $this->financing_options_other,
            'multiple_localtion_details' => $this->multiple_localtion_details ? json_decode($this->multiple_localtion_details) : null,
            'primary_services_other' => $this->primary_services_other,
            'zohomarketingdashboard'=>$this->zohomarketingdashboard,
            'nurture_automation'=>$this->nurture_automation,
            'nurturing_display_name'=>$this->nurturing_display_name,
            'scheduling_link' => $this->scheduling_link,
            'link1' => $this->link1,
            'link2' => $this->link2,
            'link3' => $this->link3,
            'staff_email'=>$staff_email,
            'inbox_id'=>$this->inbox_id,
            'license_key'=>$this->license_key,
            'email_id'=>$this->email_id,
            'marketingdashboardurl' => $this->marketingdashboardurl,
            'schedulemeetingurl' => $this->schedulemeetingurl,
            'salestrainingurl' => $this->salestrainingurl,
            'success_coach_url' => $this->success_coach_url,
            // Newly added fields
            'doctors_biography' => $this->doctors_biography,
            'doctors_biography_description' => $this->doctors_biography_description,
            'doctors_biography_summary' => $this->doctors_biography_summary,
            'doctors_biography_summary_description' => $this->doctors_biography_summary_description,
            'marketing_message' => $this->marketing_message,
            'marketing_message_description' => $this->marketing_message_description,
            'clinic_logo' => $this->getFirstMedia('clinic_logo')!=null ? $this->getFirstMedia('clinic_logo')->getUrl() : '',
            'callrail_installation_script' => $this->callrail_installation_script,
            'dns_records' => ClinicDnsRecordResource::collection($this->whenLoaded('dnsRecords')),
            'emails_for_scheduling' =>$this->emails_for_scheduling,
            'whereby_link' => $this->whereby_link,
            'assistant_id' => $this->assistant_id,
            'chat_api_key' => $this->chat_api_key,
        ];
    }
}
