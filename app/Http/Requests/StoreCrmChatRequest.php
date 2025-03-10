<?php

namespace App\Http\Requests;

use App\Models\CrmChat;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCrmChatRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('crm_chat_create');
    }

    public function rules()
    {
        return [
            'lead_id' => [
                'required',
                'integer',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'chat'  => [
                'required',
                'string',
            ],
        ];
    }
}
