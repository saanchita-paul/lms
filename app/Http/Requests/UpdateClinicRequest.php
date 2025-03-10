<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateClinicRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('clinic_edit');
    }

    public function rules()
    {
        return [
            'type'                 => [
                'required',
            ],
            'clinic_name'          => [
                'string',
                'required',
            ],
            'clinic_legal_name'    => [
                'string',
                'required',
            ],
            'dr_name'              => [
                'string',
                'required',
            ],
            'address'              => [
                'string',
                'nullable',
            ],
            'email'                => [
                'string',
                'nullable',
            ],
            'office_number'        => [
                'string',
                'nullable',
            ],
            'hotline_phone_number' => [
                'string',
                'nullable',
            ],
            'website'              => [
                'string',
                'nullable',
            ],
            'microsite_website'    => [
                'string',
                'nullable',
            ],
            'virtual_consultation' => [
                'string',
                'nullable',
            ],
            'languages_spoken'     => [
                'string',
                'nullable',
            ],
            'domain_verification'    => [
                'string',
                'Verify',
            ],
            'callrail_company'     => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'twilio_number'        => [
                'string',
                'nullable',
            ],
            'twilio_subid'         => [
                'string',
                'nullable',
            ],
            'twilio_token'         => [
                'string',
                'nullable',
            ],
            'google_analytics'     => [
                'string',
                'nullable',
            ],
            'google_ads'           => [
                'string',
                'nullable',
            ],
            'facebook'             => [
                'string',
                'nullable',
            ],
            'marketingdashboardurl'=> [
                'string',
                'nullable',
            ],
            'schedulemeetingurl'   => [
                'string',
                'nullable',
            ],
            'salestrainingurl'     => [
                'string',
                'nullable',
            ],
            'success_coach_url'     => [
                'string',
                'nullable',
            ],
            'leadcenteraccountmanager'     => [
                'string',
                'nullable',
            ],
            'reportsending'        => [
                'string',
                'nullable',
            ],
            'reportrecipients'        => [
                'string',
                'nullable',
            ],
            'status'               => [
                'required',
            ],
            'managers.*'           => [
                'integer',
            ],
            'managers'             => [
                'array',
            ],
        ];
    }
}
