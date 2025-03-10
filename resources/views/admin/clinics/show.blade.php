@extends('layouts.admin')
@section('content')

<div class="card">
  <div class="card-content">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.clinic.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clinics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.id') }}
                        </th>
                        <td>
                            {{ $clinic->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Clinic::TYPE_SELECT[$clinic->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.clinic_name') }}
                        </th>
                        <td>
                            {{ $clinic->clinic_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.clinic_legal_name') }}
                        </th>
                        <td>
                            {{ $clinic->clinic_legal_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.dr_name') }}
                        </th>
                        <td>
                            {{ $clinic->dr_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.address') }}
                        </th>
                        <td>
                            {{ $clinic->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.email') }}
                        </th>
                        <td>
                            {{ $clinic->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.office_number') }}
                        </th>
                        <td>
                            {{ $clinic->office_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.hotline_phone_number') }}
                        </th>
                        <td>
                            {{ $clinic->hotline_phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.website') }}
                        </th>
                        <td>
                            {{ $clinic->website }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.microsite_website') }}
                        </th>
                        <td>
                            {{ $clinic->microsite_website }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.specialty') }}
                        </th>
                        <td>
                            {{ $clinic->specialty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.office_hours') }}
                        </th>
                        <td>
                            {{ $clinic->office_hours }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.consultation_details_offers') }}
                        </th>
                        <td>
                            {{ $clinic->consultation_details_offers }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.scheduling_hours') }}
                        </th>
                        <td>
                            {{ $clinic->scheduling_hours }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.emails_for_scheduling') }}
                        </th>
                        <td>
                            {{ $clinic->emails_for_scheduling }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.virtual_consultation') }}
                        </th>
                        <td>
                            {{ $clinic->virtual_consultation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.services_offered_pricing') }}
                        </th>
                        <td>
                            {{ $clinic->services_offered_pricing }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.financing') }}
                        </th>
                        <td>
                            {{ $clinic->financing }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.insurance_details') }}
                        </th>
                        <td>
                            {{ $clinic->insurance_details }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.medicaid') }}
                        </th>
                        <td>
                            <label for="medicaid"><input type="checkbox" class="filled-in" disabled="disabled" {{ $clinic->medicaid ? 'checked' : '' }}><span></span></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.medicare') }}
                        </th>
                        <td>
                            <label for="medicare"><input type="checkbox" class="filled-in" disabled="disabled" {{ $clinic->medicare ? 'checked' : '' }}><span></span></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.doctor_specifics') }}
                        </th>
                        <td>
                            {{ $clinic->doctor_specifics }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.google_map_location') }}
                        </th>
                        <td>
                            {{ $clinic->google_map_location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.covid_19_specifics') }}
                        </th>
                        <td>
                            {{ $clinic->covid_19_specifics }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.languages_spoken') }}
                        </th>
                        <td>
                            {{ $clinic->languages_spoken }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.consult_email_patients') }}
                        </th>
                        <td>
                            {!! $clinic->consult_email_patients !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.reply_texts_patients') }}
                        </th>
                        <td>
                            {!! $clinic->reply_texts_patients !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.extra_notes') }}
                        </th>
                        <td>
                            {!! $clinic->extra_notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.slack_webhook_url') }}
                        </th>
                        <td>
                            {{ $clinic->slack_webhook_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.callrail_company') }}
                        </th>
                        <td>
                            {{ $clinic->callrail_company }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.twilio_number') }}
                        </th>
                        <td>
                            {{ $clinic->twilio_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.twilio_subid') }}
                        </th>
                        <td>
                            {{ $clinic->twilio_subid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.twilio_token') }}
                        </th>
                        <td>
                            {{ $clinic->twilio_token }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.google_analytics') }}
                        </th>
                        <td>
                            {{ $clinic->google_analytics }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.google_ads') }}
                        </th>
                        <td>
                            {{ $clinic->google_ads }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.facebook') }}
                        </th>
                        <td>
                            {{ $clinic->facebook }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.custom_message') }}
                        </th>
                        <td>
                            {!! $clinic->custom_message !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.timezone') }}
                        </th>
                        <td>
                            {{ $clinic->timezone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.marketingdashboardurl') }}
                        </th>
                        <td>
                            {{ $clinic->marketingdashboardurl }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.schedulemeetingurl') }}
                        </th>
                        <td>
                            {{ $clinic->schedulemeetingurl }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.salestrainingurl') }}
                        </th>
                        <td>
                            {{ $clinic->salestrainingurl }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.reportsending') }}
                        </th>
                        <td>
                            {{ App\Models\Clinic::REPORTSENDING_SELECT[$clinic->reportsending] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.reportrecipients') }}
                        </th>
                        <td>
                            {{ $clinic->reportrecipients }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.lead_center') }}
                        </th>
                        <td>
                            {{ App\Models\Clinic::LEAD_CENTER_SELECT[$clinic->lead_center] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.autosms') }}
                        </th>
                        <td>
                            <label for="autosms"><input type="checkbox" class="filled-in" disabled="disabled" {{ $clinic->autosms ? 'checked' : '' }}><span></span></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.autosmstext') }}
                        </th>
                        <td>
                            {{ $clinic->autosmstext }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.priorsmstext') }}
                        </th>
                        <td>
                            {{ $clinic->priorsmstext }}
                        </td>
                    </tr>                
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.leadcenteraccountmanager') }}
                        </th>
                        <td>
                            {{ $clinic->leadcenteraccountmanager }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Clinic::STATUS_SELECT[$clinic->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.manager') }}
                        </th>
                        <td>
                            @foreach($clinic->managers as $key => $manager)
                                <span class="label label-info">{{ $manager->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clinics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

</div>

@endsection