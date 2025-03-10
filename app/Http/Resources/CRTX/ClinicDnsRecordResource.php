<?php

namespace App\Http\Resources\CRTX;

use Illuminate\Http\Resources\Json\JsonResource;

class ClinicDnsRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'clinic_id' => $this->clinic_id,
            'type' => $this->type,
            'name' => $this->name,
            'value' => $this->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
