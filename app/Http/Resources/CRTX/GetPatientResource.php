<?php

namespace App\Http\Resources\CRTX;

use App\Http\Resources\CRTX\NotesResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use DB;

class GetPatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $timezone = "America/New_York"; // Default timezone

       
        $play_file = '';
        // Check if a clinic is associated with the resource and has a timezone
        if ($this->clinic && !empty($this->clinic->timezone)) {
            $timezone = $this->clinic->timezone;
        }
        $carbonDate_created_at = Carbon::parse($this->created_at);
        if($timezone == "America/New_York"){
            $formattedDate_created_at = $carbonDate_created_at->format('m/d/Y g:i A');
        }
        else{
            $formattedDate_created_at = $carbonDate_created_at->setTimezone($timezone)->format('m/d/Y g:i A');
        }

        $callRail_time = '';
       
        $formatedDob = $this->dob ? Carbon::parse($this->dob)->format('m/d/Y') : null;

        function getClinicTimezone($clinic) {
            return ($clinic && !empty($clinic->timezone)) ? $clinic->timezone : "America/New_York";
        }

        $timelinetimezone = getClinicTimezone($this->clinic);

        

        function formatDate($date, $timezone) {
            return Carbon::parse($date)->setTimezone($timezone)->format('m/d/Y g:i A');
        }
        

        $customerId = $this->id;
       // Fetch notes with user_name
        $notes = DB::table('crm_notes')
        ->select('crm_notes.id', 'crm_notes.note AS content', 'crm_notes.user_id', 'crm_notes.created_at', 'crm_notes.updated_at', 
                'users.name AS user_name')
        ->join('users', 'users.id', '=', 'crm_notes.user_id')
        ->where('crm_notes.customer_id', $customerId)
        ->whereNull('crm_notes.deleted_at')
        ->orderBy('crm_notes.created_at', 'desc')
        ->get()
        ->map(function ($note) use ($timezone) { 
            return [
                'id' => $note->id,
                'type' => 'note',
                'note' => $note->content,
                'user_id' => $note->user_id,
                'user_name' => $note->user_name, // Add user_name
                'inbound' => null, // No inbound field for notes
                'created_at' => formatDate($note->created_at, $timezone),
                'updated_at' => formatDate($note->updated_at, $timezone),
            ];
        });

        // Fetch chats with user_name and inbound field
        $chats = DB::table('crm_chats')
        ->select('crm_chats.id', 'crm_chats.chat AS content', 'crm_chats.user_id', 'crm_chats.created_at', 'crm_chats.updated_at', 
                'crm_chats.inbound', 'users.name AS user_name')
        ->join('users', 'users.id', '=', 'crm_chats.user_id')
        ->where('crm_chats.lead_id', $customerId)
        ->whereNull('crm_chats.deleted_at')
        ->orderBy('crm_chats.created_at', 'desc')
        ->get()
        ->map(function ($chat) use ($timezone) {
            return [
                'id' => $chat->id,
                'type' => 'chat',
                'note' => $chat->content,
                'user_id' => $chat->user_id,
                'user_name' => $chat->user_name, // Add user_name
                'inbound' => $chat->inbound, // Add inbound field
                'created_at' => formatDate($chat->created_at, $timezone),
                'updated_at' => formatDate($chat->updated_at, $timezone),
            ];
        });

        // Merge and sort by created_at (newest first)
        $mergedData = $notes->merge($chats)
        ->map(function ($item) {  
            $item['created_at'] = Carbon::parse($item['created_at']);
            return $item;
        })
        ->sortByDesc('created_at')  
        ->map(function ($item) {  
            $item['created_at'] = $item['created_at']->format('m/d/Y g:i A');
            return $item;
        })->values();
   
       

        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->email,
            'phone' => $this->formattedPhone(),
            'dob' => $formatedDob,
            'address' => $this->address,
            'description' => $this->description,
            'badge' => $this->badge,
            'phone_form' => $this->phone_form,
            'three_plus_attempts' => $this->three_plus_attempts,
            'city' => $this->city,
            'state' => $this->state,
            'form_data' => $this->form_data,
            'form_id' => $this->form_id,
            'consultation_booked_date' => $this->consultation_booked_date ? Carbon::parse($this->consultation_booked_date)->format('m/d/Y g:i A') : null,
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
            'source' => $this->source ?? '-',
            'medium' => $this->medium ?? '-',
            'keywords' => $this->keywords ?? '-',
            'campaign' => $this->campaign ?? '-',
            'landing_page_url' => $this->landing_page_url ?? '-',
            'phone_verified' => $this->phone_verified,
            'email_verified' => $this->email_verified,
            'patient_journey_automation' => $this->patient_journey_automation,
            'patient_journey_email_sequence' => $this->patient_journey_email_sequence,
            'patient_journey_sms_sequence' => $this->patient_journey_sms_sequence,
            'created_at' => $formattedDate_created_at,
           //'Notes' => NotesResource::Collection($this->whenLoaded('crmNotes')),
            'Notes' => $mergedData,
            'lead_score' => $this->lead_score ?? '',
           /* 'call_summary' => $this->call_summary ?? '',
            'full_transcript' => $this->full_transcript ?? '',*/
            'phone_score' => $this->phone_score ?? '',
            'email_score' => $this->email_score ?? '0',
            'name_score' => $this->name_score ?? '',
            'trustfull_details' => $this->trustfull_details ?? '',
            'callrail_details' => $this->phone_form == 'Phone Call' ? $this->call_details : $this->callrail_details,
            /*'callrailTime' => $callRail_time ?? '',*/
            'total_calls' => $total_calls ?? '',
            'phone_whatsapp' => $this->phone_whatsapp ?? null,
            'phone_linkedin' => $this->phone_linkedin ?? null,
            'phone_amazon' => $this->phone_amazon ?? null,
            'phone_facebook' => $this->phone_facebook ?? null,
            'phone_instagram' => $this->phone_instagram ?? null,
            'phone_twitter' => $this->phone_twitter ?? null,
            'email_whatsapp' => $this->email_whatsapp ?? null,
            'email_linkedin' => $this->email_linkedin ?? null,
            'email_amazon' => $this->email_amazon ?? null,
            'email_facebook' => $this->email_facebook ?? null,
            'email_instagram' => $this->email_instagram ?? null,
            'email_twitter' => $this->email_twitter ?? null,
            'score_url' => config('app.score_url'),
            'microsite_health_clinic_id' => config('app.microsite_health_clinic_id'),
            /*'audio_file_url' => $play_file,*/
            'quicksource'=> $this->source_id,
            'source_id' => $this->source_id,
            'credit_score_above_650' => $this->credit_score_above_650,
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
