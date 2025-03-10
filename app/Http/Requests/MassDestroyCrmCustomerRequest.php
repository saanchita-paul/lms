<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CrmCustomer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCrmCustomerRequest extends FormRequest  {





public function authorize()
{
    abort_if(Gate::denies('crm_customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');




return true;
    
}
public function rules()
{
    



return [
'ids' => 'required|array',
    'ids.*' => 'exists:crm_customers,id',
]
    
}

}