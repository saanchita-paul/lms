<?php

namespace App\Http\Requests;

use App\Models\Source;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSourceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('source_edit');
    }

    public function rules()
    {
        return [
            'source_name' => [
                'string',
                'required',
            ],
        ];
    }
}
