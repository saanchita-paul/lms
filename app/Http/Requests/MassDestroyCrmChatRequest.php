<?php

namespace App\Http\Requests;

use App\Models\CrmChat;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCrmChatRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('crm_chat_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:crm_chats,id',
        ];
    }
}
