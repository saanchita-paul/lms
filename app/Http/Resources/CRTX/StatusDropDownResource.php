<?php

namespace App\Http\Resources\CRTX;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusDropDownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        switch ($this->name) {
            case 'Attempt Three Plus':
                $name = 'Attempt Three';
                break;
            case 'Nurturing (Only FORMS)':
                $name = 'Nurturing';
                break;
            default:
                $name = $this->name;
        }

        return [
            'id' => $this->id,
            'name' => $name,
            'board' => $this->board,
        ];
    }
}
