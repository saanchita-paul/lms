<?php

namespace App\Http\Resources\CRTX;
use Carbon\Carbon;
use App\Http\Resources\CRTX\NotesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $source = [
            'id' => $this->whenLoaded('source')->id ?? 'null',
            'name' => $this->whenLoaded('source')->source_name?? 'null',
        ];

        $quickstatus = [
            'id' => $this->whenLoaded('status')->id ?? 'null',
            'name' => $this->whenLoaded('status')->name ?? 'null',
        ];

        $status = $this->whenLoaded('status')->id ?? 'null';
        $quicksource = $this->whenLoaded('source')->id ?? 'null';
        $user = [
            'id' => $this->whenLoaded('users')->id ?? 'null',
            'name' => $this->whenLoaded('users')->name ?? 'null',
            'email' => $this->whenLoaded('users')->email ?? 'null',
            'consultemail' => $this->whenLoaded('users')->consultemail ?? 'null',
            'last_login_date' => $this->whenLoaded('users')->last_login_date ?? 'null',
        ];

        $phoneWithoutPrefix = ltrim($this->phone, '+1');
        $maskedPhone = '(' . substr($phoneWithoutPrefix, 0, 3) . ') ' . substr($phoneWithoutPrefix, 3, 3) . '-' . substr($phoneWithoutPrefix, 6);
        $timezone = "America/New_York"; // Default timezone



        // Check if a clinic is associated with the resource and has a timezone
        if ($this->clinic && !empty($this->clinic->timezone)) {
            $timezone = $this->clinic->timezone;
        }
        $carbonDate_created_at = Carbon::parse($this->created_at);
        if($this->deleted_at){
            $carbonDate_deleted_at = Carbon::parse($this->deleted_at);

            $formattedDate_deleted_at = $carbonDate_deleted_at->setTimezone($timezone)->format('m/d/Y g:i A');

        }else{
            $formattedDate_deleted_at = $this->deleted_at;
        }
        if($timezone == "America/New_York"){
            $formattedDate_created_at = $carbonDate_created_at->setTimezone($timezone)->format('M d, Y g:i A');
        }
        else{
            $formattedDate_created_at = $carbonDate_created_at->setTimezone($timezone)->format('M d, Y g:i A');
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


        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->email,
            'phone' => $this->formattedPhone(),
            'dob' => $this->dob,
           // 'badge' => $this->badge,
//            'address' => $this->address,
//            'description' => $this->description,
//            'badge' => $this->badge,
            'phone_form' => $this->phone_form,
            'reason' => $this->reason,
//            'three_plus_attempts' => $this->three_plus_attempts,
//            'city' => $this->city,
//            'state' => $this->state,
//            'form_data' => $this->form_data,
//            'form_id' => $this->form_id,
                'consultation_booked_date' => $this->consultation_booked_date ? Carbon::parse($this->consultation_booked_date)->format('m/d/Y g:i A') : null,
//            'no_showed_date' => $this->no_showed_date,
//            'convert_to_deal' => $this->convert_to_deal,
//            'convert_deal_date' => $this->convert_deal_date,//            
            'value' => $this->value,
            'deal_status' => $this->deal_status,
            'won_lost' => $this->won_lost,
            'won_lost_date' => $this->won_lost_date,
//            'lifetimevalue' => $this->lifetimevalue,
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
//            'user' => $user,
            'source_id' => $source,
            'status_id' => $status,
            'quickstatus'=> $quickstatus,            
//            'last_updated' => $this->last_updated,
            'created_at' => $formattedDate_created_at,
            'deleted_at' => $formattedDate_deleted_at,
            'lead_score' =>$this->lead_score,
            'Notes' => NotesResource::Collection($this->whenLoaded('crmNotes')),
            'quicksource'=> $this->source_id,
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
