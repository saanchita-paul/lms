@extends('layouts.admin')
@section('content')

<div class="card">
  <div class="card-content">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.clinic.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.clinics.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.clinic.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clinic_name">{{ trans('cruds.clinic.fields.clinic_name') }}</label>
                <input class="form-control {{ $errors->has('clinic_name') ? 'is-invalid' : '' }}" type="text" name="clinic_name" id="clinic_name" value="{{ old('clinic_name', '') }}" required>
                @if($errors->has('clinic_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.clinic_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clinic_legal_name">{{ trans('cruds.clinic.fields.clinic_legal_name') }}</label>
                <input class="form-control {{ $errors->has('clinic_legal_name') ? 'is-invalid' : '' }}" type="text" name="clinic_legal_name" id="clinic_legal_name" value="{{ old('clinic_legal_name', '') }}" required>
                @if($errors->has('clinic_legal_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic_legal_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.clinic_legal_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="dr_name">{{ trans('cruds.clinic.fields.dr_name') }}</label>
                <input class="form-control {{ $errors->has('dr_name') ? 'is-invalid' : '' }}" type="text" name="dr_name" id="dr_name" value="{{ old('dr_name', '') }}" required>
                @if($errors->has('dr_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dr_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.dr_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="" for="address">{{ trans('cruds.clinic.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" >{{ old('address') }}</textarea>
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="" for="email">{{ trans('cruds.clinic.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" >
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="" for="office_number">{{ trans('cruds.clinic.fields.office_number') }}</label>
                <input class="form-control {{ $errors->has('office_number') ? 'is-invalid' : '' }}" type="text" name="office_number" id="office_number" value="{{ old('office_number', '') }}" >
                @if($errors->has('office_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('office_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.office_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hotline_phone_number">{{ trans('cruds.clinic.fields.hotline_phone_number') }}</label>
                <input class="form-control {{ $errors->has('hotline_phone_number') ? 'is-invalid' : '' }}" type="text" name="hotline_phone_number" id="hotline_phone_number" value="{{ old('hotline_phone_number', '') }}">
                @if($errors->has('hotline_phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hotline_phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.hotline_phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="website">{{ trans('cruds.clinic.fields.website') }}</label>
                <input class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" type="text" name="website" id="website" value="{{ old('website', '') }}">
                @if($errors->has('website'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.website_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="microsite_website">{{ trans('cruds.clinic.fields.microsite_website') }}</label>
                <input class="form-control {{ $errors->has('microsite_website') ? 'is-invalid' : '' }}" type="text" name="microsite_website" id="microsite_website" value="{{ old('microsite_website', '') }}">
                @if($errors->has('microsite_website'))
                    <div class="invalid-feedback">
                        {{ $errors->first('microsite_website') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.microsite_website_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="specialty">{{ trans('cruds.clinic.fields.specialty') }}</label>
                <textarea class="form-control {{ $errors->has('specialty') ? 'is-invalid' : '' }}" name="specialty" id="specialty">{{ old('specialty') }}</textarea>
                @if($errors->has('specialty'))
                    <div class="invalid-feedback">
                        {{ $errors->first('specialty') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.specialty_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="office_hours">{{ trans('cruds.clinic.fields.office_hours') }}</label>
                <textarea class="form-control {{ $errors->has('office_hours') ? 'is-invalid' : '' }}" name="office_hours" id="office_hours">{{ old('office_hours') }}</textarea>
                @if($errors->has('office_hours'))
                    <div class="invalid-feedback">
                        {{ $errors->first('office_hours') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.office_hours_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="consultation_details_offers">{{ trans('cruds.clinic.fields.consultation_details_offers') }}</label>
                <textarea class="form-control {{ $errors->has('consultation_details_offers') ? 'is-invalid' : '' }}" name="consultation_details_offers" id="consultation_details_offers">{{ old('consultation_details_offers') }}</textarea>
                @if($errors->has('consultation_details_offers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consultation_details_offers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.consultation_details_offers_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="scheduling_hours">{{ trans('cruds.clinic.fields.scheduling_hours') }}</label>
                <textarea class="form-control {{ $errors->has('scheduling_hours') ? 'is-invalid' : '' }}" name="scheduling_hours" id="scheduling_hours">{{ old('scheduling_hours') }}</textarea>
                @if($errors->has('scheduling_hours'))
                    <div class="invalid-feedback">
                        {{ $errors->first('scheduling_hours') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.scheduling_hours_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="emails_for_scheduling">{{ trans('cruds.clinic.fields.emails_for_scheduling') }}</label>
                <textarea class="form-control {{ $errors->has('emails_for_scheduling') ? 'is-invalid' : '' }}" name="emails_for_scheduling" id="emails_for_scheduling">{{ old('emails_for_scheduling') }}</textarea>
                @if($errors->has('emails_for_scheduling'))
                    <div class="invalid-feedback">
                        {{ $errors->first('emails_for_scheduling') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.emails_for_scheduling_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="virtual_consultation">{{ trans('cruds.clinic.fields.virtual_consultation') }}</label>
                <input class="form-control {{ $errors->has('virtual_consultation') ? 'is-invalid' : '' }}" type="text" name="virtual_consultation" id="virtual_consultation" value="{{ old('virtual_consultation', '') }}">
                @if($errors->has('virtual_consultation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('virtual_consultation') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.virtual_consultation_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="services_offered_pricing">{{ trans('cruds.clinic.fields.services_offered_pricing') }}</label>
                <textarea class="form-control {{ $errors->has('services_offered_pricing') ? 'is-invalid' : '' }}" name="services_offered_pricing" id="services_offered_pricing">{{ old('services_offered_pricing') }}</textarea>
                @if($errors->has('services_offered_pricing'))
                    <div class="invalid-feedback">
                        {{ $errors->first('services_offered_pricing') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.services_offered_pricing_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="financing">{{ trans('cruds.clinic.fields.financing') }}</label>
                <textarea class="form-control {{ $errors->has('financing') ? 'is-invalid' : '' }}" name="financing" id="financing">{{ old('financing') }}</textarea>
                @if($errors->has('financing'))
                    <div class="invalid-feedback">
                        {{ $errors->first('financing') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.financing_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="insurance_details">{{ trans('cruds.clinic.fields.insurance_details') }}</label>
                <textarea class="form-control {{ $errors->has('insurance_details') ? 'is-invalid' : '' }}" name="insurance_details" id="insurance_details">{{ old('insurance_details') }}</textarea>
                @if($errors->has('insurance_details'))
                    <div class="invalid-feedback">
                        {{ $errors->first('insurance_details') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.insurance_details_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('medicaid') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="medicaid" value="0">
                    
                    <label class="form-check-label" for="medicaid"><input class="form-check-input" type="checkbox" name="medicaid" id="medicaid" value="1" {{ old('medicaid', 0) == 1 ? 'checked' : '' }}>
                        <span>{{ trans('cruds.clinic.fields.medicaid') }}</span>        
                    </label>
                </div>
                @if($errors->has('medicaid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medicaid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.medicaid_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('medicare') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="medicare" value="0">
                    
                    <label class="form-check-label" for="medicare">
                        <input class="form-check-input" type="checkbox" name="medicare" id="medicare" value="1" {{ old('medicare', 0) == 1 ? 'checked' : '' }}>
                        <span>{{ trans('cruds.clinic.fields.medicare') }}</span>
                    </label>
                </div>
                @if($errors->has('medicare'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medicare') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.medicare_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="doctor_specifics">{{ trans('cruds.clinic.fields.doctor_specifics') }}</label>
                <textarea class="form-control {{ $errors->has('doctor_specifics') ? 'is-invalid' : '' }}" name="doctor_specifics" id="doctor_specifics">{{ old('doctor_specifics') }}</textarea>
                @if($errors->has('doctor_specifics'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor_specifics') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.doctor_specifics_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_map_location">{{ trans('cruds.clinic.fields.google_map_location') }}</label>
                <textarea class="form-control {{ $errors->has('google_map_location') ? 'is-invalid' : '' }}" name="google_map_location" id="google_map_location">{{ old('google_map_location') }}</textarea>
                @if($errors->has('google_map_location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_map_location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_map_location_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="covid_19_specifics">{{ trans('cruds.clinic.fields.covid_19_specifics') }}</label>
                <textarea class="form-control {{ $errors->has('covid_19_specifics') ? 'is-invalid' : '' }}" name="covid_19_specifics" id="covid_19_specifics">{{ old('covid_19_specifics') }}</textarea>
                @if($errors->has('covid_19_specifics'))
                    <div class="invalid-feedback">
                        {{ $errors->first('covid_19_specifics') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.covid_19_specifics_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="languages_spoken">{{ trans('cruds.clinic.fields.languages_spoken') }}</label>
                <input class="form-control {{ $errors->has('languages_spoken') ? 'is-invalid' : '' }}" type="text" name="languages_spoken" id="languages_spoken" value="{{ old('languages_spoken', '') }}">
                @if($errors->has('languages_spoken'))
                    <div class="invalid-feedback">
                        {{ $errors->first('languages_spoken') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.languages_spoken_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="consult_email_patients">{{ trans('cruds.clinic.fields.consult_email_patients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('consult_email_patients') ? 'is-invalid' : '' }}" name="consult_email_patients" id="consult_email_patients">{!! old('consult_email_patients') !!}</textarea>
                @if($errors->has('consult_email_patients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consult_email_patients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.consult_email_patients_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reply_texts_patients">{{ trans('cruds.clinic.fields.reply_texts_patients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('reply_texts_patients') ? 'is-invalid' : '' }}" name="reply_texts_patients" id="reply_texts_patients">{!! old('reply_texts_patients') !!}</textarea>
                @if($errors->has('reply_texts_patients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reply_texts_patients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.reply_texts_patients_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra_notes">{{ trans('cruds.clinic.fields.extra_notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('extra_notes') ? 'is-invalid' : '' }}" name="extra_notes" id="extra_notes">{!! old('extra_notes') !!}</textarea>
                @if($errors->has('extra_notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('extra_notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.extra_notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slack_webhook_url">{{ trans('cruds.clinic.fields.slack_webhook_url') }}</label>
                <input class="form-control {{ $errors->has('slack_webhook_url') ? 'is-invalid' : '' }}" type="text" name="slack_webhook_url" id="slack_webhook_url" value="{{ old('slack_webhook_url', '') }}">
                @if($errors->has('slack_webhook_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slack_webhook_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.slack_webhook_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="callrail_company">{{ trans('cruds.clinic.fields.callrail_company') }}</label>
                <input class="form-control {{ $errors->has('callrail_company') ? 'is-invalid' : '' }}" type="number" name="callrail_company" id="callrail_company" value="{{ old('callrail_company', '') }}" step="1">
                @if($errors->has('callrail_company'))
                    <div class="invalid-feedback">
                        {{ $errors->first('callrail_company') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.callrail_company_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_number">{{ trans('cruds.clinic.fields.twilio_number') }}</label>
                <input class="form-control {{ $errors->has('twilio_number') ? 'is-invalid' : '' }}" type="text" name="twilio_number" id="twilio_number" value="{{ old('twilio_number', '') }}">
                @if($errors->has('twilio_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_subid">{{ trans('cruds.clinic.fields.twilio_subid') }}</label>
                <input class="form-control {{ $errors->has('twilio_subid') ? 'is-invalid' : '' }}" type="text" name="twilio_subid" id="twilio_subid" value="{{ old('twilio_subid', '') }}">
                @if($errors->has('twilio_subid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_subid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_subid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_token">{{ trans('cruds.clinic.fields.twilio_token') }}</label>
                <input class="form-control {{ $errors->has('twilio_token') ? 'is-invalid' : '' }}" type="text" name="twilio_token" id="twilio_token" value="{{ old('twilio_token', '') }}">
                @if($errors->has('twilio_token'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_token') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_token_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_analytics">{{ trans('cruds.clinic.fields.google_analytics') }}</label>
                <input class="form-control {{ $errors->has('google_analytics') ? 'is-invalid' : '' }}" type="text" name="google_analytics" id="google_analytics" value="{{ old('google_analytics', '') }}">
                @if($errors->has('google_analytics'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_analytics') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_analytics_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_ads">{{ trans('cruds.clinic.fields.google_ads') }}</label>
                <input class="form-control {{ $errors->has('google_ads') ? 'is-invalid' : '' }}" type="text" name="google_ads" id="google_ads" value="{{ old('google_ads', '') }}">
                @if($errors->has('google_ads'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_ads') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_ads_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="facebook">{{ trans('cruds.clinic.fields.facebook') }}</label>
                <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text" name="facebook" id="facebook" value="{{ old('facebook', '') }}">
                @if($errors->has('facebook'))
                    <div class="invalid-feedback">
                        {{ $errors->first('facebook') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.facebook_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="custom_message">{{ trans('cruds.clinic.fields.custom_message') }}</label>
                <textarea class="form-control {{ $errors->has('custom_message') ? 'is-invalid' : '' }}" name="custom_message" id="custom_message">{{ old('custom_message') }}</textarea>
                @if($errors->has('custom_message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('custom_message') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.custom_message_helper') }}</span>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.timezone') }}</label>
                <select class="browser-default form-control select2 {{ $errors->has('timezone') ? 'is-invalid' : '' }}" name="timezone" id="timezone">
                    <option value {{ old('timezone', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    <option value="America/New_York"  {{ old('timezone', '') === 'America/New_York' ? 'selected' : '' }}>Eastern</option>
                    <option value="America/Chicago"  {{ old('timezone', '') === 'America/Chicago' ? 'selected' : '' }}>Central</option>
                    <option value="America/Denver"  {{ old('timezone', '') === 'America/Denver' ? 'selected' : '' }}>Mountain</option>
                    <option value="America/Los_Angeles"  {{ old('timezone', '') === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific</option>
                </select>
                @if($errors->has('timezone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('timezone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.timezone_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="marketingdashboardurl">{{ trans('cruds.clinic.fields.marketingdashboardurl') }}</label>
                <input class="form-control {{ $errors->has('marketingdashboardurl') ? 'is-invalid' : '' }}" type="text" name="marketingdashboardurl" id="marketingdashboardurl" value="{{ old('marketingdashboardurl', '') }}">
                @if($errors->has('marketingdashboardurl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('marketingdashboardurl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.marketingdashboardurl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="schedulemeetingurl">{{ trans('cruds.clinic.fields.schedulemeetingurl') }}</label>
                <input class="form-control {{ $errors->has('schedulemeetingurl') ? 'is-invalid' : '' }}" type="text" name="schedulemeetingurl" id="schedulemeetingurl" value="{{ old('schedulemeetingurl', '') }}">
                @if($errors->has('schedulemeetingurl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('schedulemeetingurl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.schedulemeetingurl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="salestrainingurl">{{ trans('cruds.clinic.fields.salestrainingurl') }}</label>
                <input class="form-control {{ $errors->has('salestrainingurl') ? 'is-invalid' : '' }}" type="text" name="salestrainingurl" id="salestrainingurl" value="{{ old('salestrainingurl', '') }}">
                @if($errors->has('salestrainingurl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('salestrainingurl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.salestrainingurl_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="reportsending">{{ trans('cruds.clinic.fields.reportsending') }}</label>
                <select class="form-control {{ $errors->has('reportsending') ? 'is-invalid' : '' }}" name="reportsending" id="reportsending">
                    <option value disabled {{ old('reportsending', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::REPORTSENDING_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('reportsending', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('reportsending'))
                    <div class="invalid-reportsending">
                        {{ $errors->first('reportsending') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.reportsending_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reportrecipients">{{ trans('cruds.clinic.fields.reportrecipients') }}</label>
                <input class="form-control {{ $errors->has('reportrecipients') ? 'is-invalid' : '' }}" type="text" name="reportrecipients" id="reportrecipients" value="{{ old('reportrecipients', '') }}">
                @if($errors->has('reportrecipients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reportrecipients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.reportrecipients_helper') }}</span>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.lead_center') }}</label>
                <select class="form-control {{ $errors->has('lead_center') ? 'is-invalid' : '' }}" name="lead_center" id="lead_center">
                    <option value disabled {{ old('lead_center', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::LEAD_CENTER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('lead_center', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('lead_center'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lead_center') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.lead_center_helper') }}</span>
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('autosms') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="autosms" value="0">
                    
                    <label class="form-check-label" for="autosms"><input class="form-check-input" type="checkbox" name="autosms" id="autosms" value="1" {{ old('autosms', 0) == 1 ? 'checked' : '' }}>
                        <span>{{ trans('cruds.clinic.fields.autosms') }}</span>        
                    </label>
                </div>
                @if($errors->has('autosms'))
                    <div class="invalid-feedback">
                        {{ $errors->first('autosms') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.autosms_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="autosmstext">{{ trans('cruds.clinic.fields.autosmstext') }}</label>
                <textarea class="form-control {{ $errors->has('autosmstext') ? 'is-invalid' : '' }}" name="autosmstext" id="autosmstext">{{ old('autosmstext') }}</textarea>
                @if($errors->has('autosmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('autosmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.autosmstext_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="priorsmstext">{{ trans('cruds.clinic.fields.priorsmstext') }}</label>
                <textarea class="form-control {{ $errors->has('priorsmstext') ? 'is-invalid' : '' }}" name="priorsmstext" id="priorsmstext">{{ old('priorsmstext') }}</textarea>
                @if($errors->has('priorsmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('priorsmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.priorsmstext_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="leadcenteraccountmanager">{{ trans('cruds.clinic.fields.leadcenteraccountmanager') }}</label>
                <input class="form-control {{ $errors->has('leadcenteraccountmanager') ? 'is-invalid' : '' }}" type="text" name="leadcenteraccountmanager" id="leadcenteraccountmanager" value="{{ old('leadcenteraccountmanager', '') }}">
                @if($errors->has('leadcenteraccountmanager'))
                    <div class="invalid-feedback">
                        {{ $errors->first('leadcenteraccountmanager') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.leadcenteraccountmanager_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required">{{ trans('cruds.clinic.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="managers">{{ trans('cruds.clinic.fields.manager') }}</label>
                
                <select class="browser-default form-control select2 {{ $errors->has('managers') ? 'is-invalid' : '' }}" name="managers[]" id="managers" multiple>
                    @foreach($managers as $id => $manager)
                        <option value="{{ $id }}" {{ in_array($id, old('managers', [])) ? 'selected' : '' }}>{{ $manager }}</option>
                    @endforeach
                </select>
                @if($errors->has('managers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('managers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.manager_helper') }}</span>
            </div>
            <div class="form-group input-field">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/clinics/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $clinic->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection