<?php

namespace App\Http\Resources\CRTX;

use App\Http\Resources\CRTX\getSourcesResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
//         [$town, $state, $zip_code] = $fields;
//         $town = $fields[0];
//         $state = $fields[1];
         $zip_code = $fields[2] ?? '-';


        $source = [
            'id' => $this->whenLoaded('source')->id ?? 'null',
            'name' => $this->whenLoaded('source')->source_name ?? 'null',
            'created_at' => $this->whenLoaded('source')->created_at ?? 'null',
            'updated_at' => $this->whenLoaded('source')->source_name ?? 'null',
        ];

         $status = [
            'id' => $this->whenLoaded('status')->id ?? 'null',
            'name' => $this->crm_status_name ?? 'null',
            'board' => $this->whenLoaded('status')->board ?? 'null',
            'created_at' => $this->whenLoaded('status')->created_at ?? 'null',
            'updated_at' => $this->whenLoaded('status')->updated_at ?? 'null',
        ];

         $clinic = [
            'id' => $this->whenLoaded('clinic')->id ?? 'null',
            'practice_name' => $this->whenLoaded('clinic')->clinic_name ?? 'null',
            'practice_legal_name' => $this->whenLoaded('clinic')->clinic_legal_name ?? 'null',
        ];

        $timezone = "America/New_York";
        if ($this->clinic && !empty($this->clinic->timezone)) {
            $timezone = $this->clinic->timezone;
        }

        if($this->crm_customers_created_at){
            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->crm_customers_created_at)->setTimezone($timezone)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }else{
            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->setTimezone($timezone)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        }

        $callrailDetails = json_decode($this->callrail_details, true);

        $landingPageUrlWithoutQueryString = '';
        if(isset($callrailDetails) && isset($callrailDetails['landing_page_url']))
        {
            $landingPageUrl  = $callrailDetails['landing_page_url'];
            $urlParts = explode('?', $landingPageUrl);
            $parsedUrl = parse_url($urlParts[0]);
            $landingPageUrlWithoutQueryString = pathinfo($parsedUrl['path'], PATHINFO_BASENAME);
        }

        // Check if $landingPageUrlWithoutQueryString is empty
        if (empty($landingPageUrlWithoutQueryString)) {
            // Set the home page URL as a fallback
            $landingPageUrlWithoutQueryString = 'home'; // Modify this according to your actual home page URL
        }

        $form_data = str_replace('"""', '', $this->form_data);
        // Remove the "FormData:" part to leave just the JSON content
        $jsonString = trim(str_replace('FormData:', '', $form_data));
        $jsonString = trim(str_replace("<b style='color:red'>Invalid Email Address received from Callrail</b> ", '', $jsonString));
        // Decode the JSON string to an associative array
        $formDataArray = json_decode($jsonString, true);

        $zipCode = $this->zipcode;

        if (empty($zipCode) && is_array($formDataArray)) {
            foreach ($formDataArray as $key => $value) {
                if (strpos($key, 'ZIP Code') !== false) {
                    $zipCode = $value;
                    break;
                }
            }
        }

        return [
            'id' => $this->cc_id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->cc_email,
            'phone' => $this->formattedPhone(),
//            'dob' => $this->dob,
//            'address' => $this->address,
//            'description' => $this->description,
//            'badge' => $this->badge,
            'phone_form' => $this->phone_form,
//            'three_plus_attempts' => $this->three_plus_attempts,
            'city' => $this->city,
            'state' => $this->state,
//            'form_data' => $this->form_data,
//            'form_id' => $this->form_id,
            'consultation_booked_date' => $this->consultation_booked_date,
            'no_showed_date' => $this->no_showed_date,
//            'convert_to_deal' => $this->convert_to_deal,
            'convert_deal_date' => $this->convert_deal_date,
            'reason' => $this->reason,
            'value' => $this->value,
            'lifetimevalue' => $this->lifetimevalue,
//            'patientID' => $this->patientID,
//            'lastFetchDate' => $this->lastFetchDate,
//            'ccapture' => $this->ccapture,
//            'budget' => $this->budget,
//            'verbal_confirmation' => $this->verbal_confirmation,
//            'informed_consult_fee' => $this->informed_consult_fee,
//            'deal_status' => $this->deal_status,
//            'won_lost' => $this->won_lost,
//            'won_lost_date' => $this->won_lost_date,
//            'lead_type' => $this->lead_type ,
//            'has_sms' => $this->has_sms,
//            'automation' => $this->automation,
//            'email_sequence' => $this->email_sequence,
//            'sms_sequence' => $this->sms_sequence,
//            'next_mail_date' => $this->next_mail_date,
//            'source_other' => $this->source_other,
//            'clinic_id' => $this->clinic_id,
//            'status_id' => $this->status_id,
//            'source_id' => $this->source_id,
//            'phone_verified' => $this->phone_verified,
//            'email_verified' => $this->email_verified,
//            'patient_journey_automation' => $this->patient_journey_automation,
//            'patient_journey_email_sequence' => $this->patient_journey_email_sequence,
//            'patient_journey_sms_sequence' => $this->patient_journey_sms_sequence,
//            'clinic' => $clinic,
            'source' => $source,
            'status' => $status,
            'zip_code' => $zipCode,
            'created_at' => $created_at,
            'lead_score' => $this->lead_score,
            'phone_score' => $this->phone_score,
            'email_score' => $this->email_score,
            'name_score' => $this->name_score,
            'tagName' => $this->tagName,
            'landing_page_url' => $landingPageUrlWithoutQueryString,
        ];
    }
    public function formattedPhone()
    {
        // Assuming 'phone' is the attribute name for the phone number
        $phone = $this->phone;
        if ($phone) {
            // Remove the '+1' prefix from the phone number
            $cleanedPhone = substr($phone, 2);

            // Format the cleaned phone number
            return substr($cleanedPhone, 0, 3) . '-' . substr($cleanedPhone, 3, 3) . '-' . substr($cleanedPhone, 6);
        }

        return null;

    }
}
