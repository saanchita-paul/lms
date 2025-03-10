@extends('layouts.admin')
@section('content')

<div class="card">
  <div class="card-content">   
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.crmCustomer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.crm-customers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.id') }}
                        </th>
                        <td>
                            {{ $crmCustomer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.clinic') }}
                        </th>
                        <td>
                            {{ $crmCustomer->clinic->clinic_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.first_name') }}
                        </th>
                        <td>
                            {{ $crmCustomer->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.last_name') }}
                        </th>
                        <td>
                            {{ $crmCustomer->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.phone_form') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::PHONE_FORM_SELECT[$crmCustomer->phone_form] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.source') }}
                        </th>
                        <td>
                            {{ $crmCustomer->source->source_name ?? '' }}
                        </td>
                    </tr>

                    <?php 
                    if($crmCustomer->source_other != ''){
                    ?>    
                        <tr>
                        <th>
                            Source Other
                        </th>
                        <td>
                            {{ $crmCustomer->source_other ?? '' }}
                        </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.status') }}
                        </th>
                        <td>
                            {{ $crmCustomer->status->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.three_plus_attempts') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::THREE_PLUS_ATTEMPTS_SELECT[$crmCustomer->three_plus_attempts] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.email') }}
                        </th>
                        <td>
                            {{ $crmCustomer->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.phone') }}
                        </th>
                        <td>
                            {{ $crmCustomer->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.dob') }}
                        </th>
                        <td>
                            {{ $crmCustomer->dob }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.city') }}
                        </th>
                        <td>
                            {{ $crmCustomer->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.state') }}
                        </th>
                        <td>
                            {{ $crmCustomer->state }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.description') }}
                        </th>
                        <td>
                            {{ $crmCustomer->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.badge') }}
                        </th>
                        <td>
                            {{ $crmCustomer->badge }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.form_data') }}
                        </th>
                        <td>
                            {!! $crmCustomer->form_data !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.created_at') }}
                        </th>
                        <td>
                            {!! $crmCustomer->created_at->format('m/d/Y H:i:s') !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.updated_at') }}
                        </th>
                        <td>
                            {!! $crmCustomer->updated_at->format('m/d/Y H:i:s') !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.consultation_booked_date') }}
                        </th>
                        <td>
                            {{ $crmCustomer->consultation_booked_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.no_showed_date') }}
                        </th>
                        <td>
                            {{ $crmCustomer->no_showed_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.convert_to_deal') }}
                        </th>
                        <td>
                            <label><input type="checkbox" disabled="disabled" {{ $crmCustomer->convert_to_deal ? 'checked' : '' }}><span></span></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.convert_deal_date') }}
                        </th>
                        <td>
                            {{ $crmCustomer->convert_deal_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.reason') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::REASON_SELECT[$crmCustomer->reason] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.value') }}
                        </th>
                        <td>
                            {{ $crmCustomer->value }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.deal_status') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::DEAL_STATUS_SELECT[$crmCustomer->deal_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.budget') }}
                        </th>
                        <td>
                            {{ $crmCustomer->budget }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.verbal_confirmation') }}
                        </th>
                        <td>
                            {{ $crmCustomer->verbal_confirmation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.informed_consult_fee') }}
                        </th>
                        <td>
                            {{ $crmCustomer->informed_consult_fee }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.won_lost') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::WON_LOST_SELECT[$crmCustomer->won_lost] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.won_lost_date') }}
                        </th>
                        <td>
                            {{ $crmCustomer->won_lost_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.assignee') }}
                        </th>
                        <td>
                            @foreach($crmCustomer->assignees as $key => $assignee)
                                <span class="label label-info">{{ $assignee->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.crmCustomer.fields.lead_type') }}
                        </th>
                        <td>
                            {{ App\Models\CrmCustomer::LEAD_TYPE_SELECT[$crmCustomer->lead_type] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.crm-customers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
  </div>  
</div>



@endsection