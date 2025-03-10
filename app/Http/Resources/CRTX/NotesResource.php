<?php

namespace App\Http\Resources\CRTX;

use Carbon\Carbon;
use App\Models\Clinic;
use Illuminate\Http\Resources\Json\JsonResource;

class NotesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $clinic = Clinic::find($this->crmcustomer->clinic_id);

        $timezone = "America/New_York"; // Default timezone
        // Check if a clinic is associated with the resource and has a timezone
        if ($clinic && !empty($clinic->timezone)) {
            $timezone = $clinic->timezone;
        }
        $carbonDate_created_at = Carbon::parse($this->created_at);

        $formattedDate_created_at = $carbonDate_created_at->setTimezone($timezone)->format('m/d/Y g:i A');


        $carbonDate_updated_at = Carbon::parse($this->updated_at);
        $formattedDate_updated_at = $carbonDate_updated_at->setTimezone($timezone)->format('m/d/Y g:i A');

        return [
            'id' => $this->id,
            'note' => $this->note,
            'created_at' => $formattedDate_created_at,
            'updated_at' => $formattedDate_updated_at,
            'user_id' => $this->user_id,
            'user_name' => $this->users->name,
        ];

    }
}
