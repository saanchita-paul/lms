<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:services,name|max:255',
            'clinic_id' => 'required|exists:clinics,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'This service name already exists.',
            'name.required' => 'The service name is required.',
            'clinic_id.required' => 'The clinic ID is required.',
            'clinic_id.exists' => 'Invalid clinic ID.',
        ];
    }
}
