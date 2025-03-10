<?php

namespace App\Http\Resources\CRTX;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (isset($this->whenLoaded('clinic')->town_state_zip)) {
            $fields = explode(',', $this->whenLoaded('clinic')->town_state_zip);
        }else{
            $fields = ['','',''];
        }
        [$town, $state, $zip_code] = $fields;
        $town = $fields[0];
        $state = $fields[1];
        $zip_code = $fields[2];
        $source = [
            'id' => $this->whenLoaded('source')->id ?? 'null',
            'name' => $this->whenLoaded('source')->source_name ?? 'null',
            'created_at' => $this->whenLoaded('source')->created_at ?? 'null',
            'updated_at' => $this->whenLoaded('source')->source_name ?? 'null',
        ];

         $status = [
            'id' => $this->whenLoaded('status')->id ?? 'null',
            'name' => $this->whenLoaded('status')->name ?? 'null',
            'board' => $this->whenLoaded('status')->board ?? 'null',
            'created_at' => $this->whenLoaded('status')->created_at ?? 'null',
            'updated_at' => $this->whenLoaded('status')->updated_at ?? 'null',
        ];

         $clinic = [
            'id' => $this->whenLoaded('clinic')->id ?? 'null',
            'id' => $this->whenLoaded('clinic')->id ?? 'null',
            'dr_name' => $this->whenLoaded('clinic')->dr_name ?? 'null',
            'degree_abbreviation' => $this->whenLoaded('clinic')->dr_abbreviations ?? 'null',
            'industry' => $this->whenLoaded('clinic')->type ?? 'null',
            'website_url' => $this->whenLoaded('clinic')->website ?? 'null',
            'practice_name' => $this->whenLoaded('clinic')->clinic_name ?? 'null',
            'practice_legal_name' => $this->whenLoaded('clinic')->clinic_legal_name ?? 'null',
            'practice_email' => $this->whenLoaded('clinic')->email ?? 'null',
            'address' => $this->whenLoaded('clinic')->address ?? 'null',
            'town' => $town,
            'state' => $state,
            'zip_code' => $zip_code,
            'phone' => $this->whenLoaded('clinic')->hotline_phone_number ?? 'null',
            'office_hours' => $this->whenLoaded('clinic')->office_hours ?? 'null',
            'holidays' => $this->whenLoaded('clinic')->holidays ?? 'null',
            'multiple_doctors' => $this->whenLoaded('clinic')->multiple_doctors ?? 'null',
            'practice_specialty' => explode(',', $this->whenLoaded('clinic')->specialty) ?? 'null',
            'primary_services' => explode(',', $this->whenLoaded('clinic')->primary_services) ?? 'null',
            'practice_management_system' => $this->whenLoaded('clinic')->practice_management_system ?? 'null',
            'practice_different' => $this->whenLoaded('clinic')->practice_different ?? 'null',
            'website_information_detail' => $this->whenLoaded('clinic')->current_website_accurate ?? 'null',
            'website_type' => $this->whenLoaded('clinic')->website_type ?? 'null',
            'ai_website_url' => $this->whenLoaded('clinic')->microsite_website ?? 'null',
            'professional_photography' => $this->whenLoaded('clinic')->patient_photos ?? 'null',
            'professional_video' => $this->whenLoaded('clinic')->professional_video ?? 'null',
            'quality_patient_after_photos' => $this->whenLoaded('clinic')->patient_photos ?? 'null',
            'patient_testimonial_videos' => $this->whenLoaded('clinic')->testimonials ?? 'null',
            'testimonial_link' => $this->whenLoaded('clinic')->usertestimonials ?? 'null',
            'google_my_business_review' => $this->whenLoaded('clinic')->google_map ?? 'null',
            'separate_marketing_company' => $this->whenLoaded('clinic')->another_company ?? 'null',
            'paid_media' => explode(',', $this->whenLoaded('clinic')->paid_media) ?? 'null',
            'monthly_media_budget' => $this->whenLoaded('clinic')->media_budget ?? 'null',
            'primary_selling_message' => $this->whenLoaded('clinic')->primary_selling ?? 'null',
            'promotional_specials' => $this->whenLoaded('clinic')->promotional_specials ?? 'null',
            'promotional_specials_desc' => $this->whenLoaded('clinic')->promotional_specials_desc ?? 'null',
            'technology' => explode(',', $this->whenLoaded('clinic')->technology) ?? 'null',
            'technology_desc' => $this->whenLoaded('clinic')->listtechnology ?? 'null',
            'work_with_finance_company' => $this->whenLoaded('clinic')->financing ?? 'null',
            'financing_options' => explode(',', $this->whenLoaded('clinic')->financing_options) ?? 'null',
            'other_finance_company' => $this->whenLoaded('clinic')->financing_options_other ?? 'null',
            'multiple_localtion_details' => json_decode($this->whenLoaded('clinic')->multiple_localtion_details),
            'primary_services_other' => $this->whenLoaded('clinic')->primary_services_other ?? 'null',
        ];

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'address' => $this->address,
            'description' => $this->description,
            'badge' => $this->badge,
            'phone_form' => $this->phone_form,
            'three_plus_attempts' => $this->three_plus_attempts,
            'city' => $this->city,
            'state' => $this->state,
            'form_data' => $this->form_data,
            'form_id' => $this->form_id,
            'consultation_booked_date' => $this->consultation_booked_date,
            'no_showed_date' => $this->no_showed_date,
            'convert_to_deal' => $this->convert_to_deal,
            'convert_deal_date' => $this->convert_deal_date,
            'reason' => $this->reason,
            'value' => $this->value,
            'lifetimevalue' => $this->lifetimevalue,
            'patientID' => $this->patientID,
            'lastFetchDate' => $this->lastFetchDate,
            'ccapture' => $this->ccapture,
            'budget' => $this->budget,
            'verbal_confirmation' => $this->verbal_confirmation,
            'informed_consult_fee' => $this->informed_consult_fee,
            'deal_status' => $this->deal_status,
            'won_lost' => $this->won_lost,
            'won_lost_date' => $this->won_lost_date,
            'lead_type' => $this->lead_type ,
            'has_sms' => $this->has_sms,
            'automation' => $this->automation,
            'email_sequence' => $this->email_sequence,
            'sms_sequence' => $this->sms_sequence,
            'next_mail_date' => $this->next_mail_date,
            'source_other' => $this->source_other,
            'status_id' => $this->status_id,
            'source_id' => $this->source_id,
            'phone_verified' => $this->phone_verified,
            'email_verified' => $this->email_verified,
            'patient_journey_automation' => $this->patient_journey_automation,
            'patient_journey_email_sequence' => $this->patient_journey_email_sequence,
            'patient_journey_sms_sequence' => $this->patient_journey_sms_sequence,
            'clinic' => $clinic,
            'source' => $source,
            'status' => $status,
        ];
    }
}
