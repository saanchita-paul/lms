@extends('layouts.admin')
@section('content')
<?php
$isadmin =  Auth()->user()->getIsAdminAttribute();
$display = "";
if(!$isadmin){
    $display = "hide";
}

$multiple_localtion_details    = explode('|*|', $clinic->multiple_localtion_details);

$practice_specialty     = explode(',', $clinic->practice_specialty);
$primary_services       = explode(',', $clinic->primary_services);
$primary_selling        = explode(',', $clinic->primary_selling);
$financing_options      = explode(',', $clinic->financing_options);
$technology             = explode(',', $clinic->technology);
$available_treatment    = explode(',', $clinic->available_treatment);
//dd($primary_selling );


?>
<style>
  #template .row {
      margin-bottom: 20px;
    }
    #template .btn-small{
        padding: 2px 5px;
    }
   /* #template .ck.ck-editor
    {
        width: 15% !important;
    }*/

    .button.button-small {
      height: 30px;
      line-height: 30px;
      padding: 0px 10px;
    }

    td input[type=text],
    td select {
      width: 100%;
      height: 30px;
      margin: 0;
      padding: 2px 8px;
    }

    td:last-child .button {
      width: 30px;
      height: 30px;
      text-align: center;
      padding: 0px;
      margin-bottom: 0px;
      margin-right: 5px;
      background-color: #FFF;
    }

    td:last-child .button .fa {
      line-height: 30px;
      width: 30px;
    }
    #template {
    position:relative;
    max-width:100%;
    margin:auto;
    overflow:hidden;

    }

     #preconsultation {
    position:relative;
    max-width:100%;
    margin:auto;
    overflow:hidden;

    }
    .table-wrap {
        width:100%;
        overflow:auto;
    }
    #template  table {
        width:100%;
        margin:auto;
        border-collapse:separate;
        border-spacing:0;
    }

    #preconsultation  table {
        width:100%;
        margin:auto;
        border-collapse:separate;
        border-spacing:0;
    }
    #template th, #template td {
        padding:15px 15px ;
        background:#fff;
        white-space:nowrap;
        vertical-align:top;
    }
    #preconsultation th, #preconsultation td {
        padding:15px 15px ;
        background:#fff;
        white-space:nowrap;
        vertical-align:top;
    }
    #template thead, .table-scroll tfoot {
        background:#f9f9f9;
    }
    .row .col.m2
    {
        width: 21.6666666667%;
        margin-left: auto;
        left: auto;
    }
</style>

<div class="hide">
    <ul class="stepper horizontal" id="horizStepper">
        <li class="step active">

        </li>
    </ul>


    <ul class="stepper linear" id="linearStepper">
        <li class="step active">

        </li>
    </ul>
</div>
@if (\Session::has('success'))
    <div class="card-alert card green">
        <div class="card-content white-text">
          <p>{!! \Session::get('success') !!}</p>
        </div>
        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
<div class="card">
  <div class="card-content">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.clinic.title_singular') }}
    </div>

    <section class="basic-tabs mt-1 section users-edit">
        <div class="row">
            <div class="col s12">
              <!-- tabs  -->

                <ul class="tabs tab-demo z-depth-1 card-border-gray">
                  <li class="tab col m2">
                    <a href="#general" class="active">
                      <i class="material-icons">brightness_low</i>
                      <span>Practice Info</span>
                    </a>
                  </li>
                  <li class="tab col m2">
                    <a href="#template">
                      <i class="material-icons">sms</i>
                      <span>Nurture Templates</span>
                    </a>
                  </li>
                   <li class="tab col m2">
                    <a href="#preconsultation">
                      <i class="material-icons">mail</i>
                      <span>Pre Consultation Templates</span>
                    </a>
                  </li>
                </ul>

            </div>
        </div>
    </section>
    <div class="card-body" id="general">
        <form method="POST" action="{{ route("admin.clinics.update", [$clinic->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <ul class="stepper" id="nonLinearStepper">
                <li class="step active">
                    <div class="step-title waves-effect blue-text">Practice Info</div>
                    <div class="step-content">

                        <div class="form-group <?php echo $display;?>">
                            <label class="required">{{ trans('cruds.clinic.fields.type') }}</label>
                            <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Clinic::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $clinic->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <label for="website">{{ trans('cruds.clinic.fields.website') }}</label>
                            <input class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" type="text" name="website" id="website" value="{{ old('website', $clinic->website) }}">
                            @if($errors->has('website'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('website') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.website_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="clinic_name">{{ trans('cruds.clinic.fields.clinic_name') }}</label>
                            <input class="form-control {{ $errors->has('clinic_name') ? 'is-invalid' : '' }}" type="text" name="clinic_name" id="clinic_name" value="{{ old('clinic_name', $clinic->clinic_name) }}" required>
                            @if($errors->has('clinic_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('clinic_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.clinic_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="clinic_legal_name">{{ trans('cruds.clinic.fields.clinic_legal_name') }}</label>
                            <input class="form-control {{ $errors->has('clinic_legal_name') ? 'is-invalid' : '' }}" type="text" name="clinic_legal_name" id="clinic_legal_name" value="{{ old('clinic_legal_name', $clinic->clinic_legal_name) }}" required>
                            @if($errors->has('clinic_legal_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('clinic_legal_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.clinic_legal_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="dr_name">{{ trans('cruds.clinic.fields.dr_name') }}</label>
                            <input class="form-control {{ $errors->has('dr_name') ? 'is-invalid' : '' }}" type="text" name="dr_name" id="dr_name" value="{{ old('dr_name', $clinic->dr_name) }}" required>
                            @if($errors->has('dr_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dr_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.dr_name_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="" for="dr_fullname">{{ trans('cruds.clinic.fields.dr_fullname') }}</label>
                            <input class="form-control {{ $errors->has('dr_fullname') ? 'is-invalid' : '' }}" type="text" name="dr_fullname" id="dr_fullname" value="{{ old('dr_fullname', $clinic->dr_fullname) }}">
                            @if($errors->has('dr_fullname'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dr_fullname') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.dr_fullname_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="" for="dr_abbreviations">{{ trans('cruds.clinic.fields.dr_abbreviations') }}</label>
                            <input class="form-control {{ $errors->has('dr_abbreviations') ? 'is-invalid' : '' }}" type="text" name="dr_abbreviations" id="dr_abbreviations" value="{{ old('dr_abbreviations', $clinic->dr_abbreviations) }}">
                            @if($errors->has('dr_abbreviations'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dr_abbreviations') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.dr_abbreviations_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="" for="multiple_doctors">{{ trans('cruds.clinic.fields.multiple_doctors') }}</label>
                            <textarea class="form-control {{ $errors->has('multiple_doctors') ? 'is-invalid' : '' }}" name="multiple_doctors" id="multiple_doctors">{{ old('multiple_doctors', $clinic->multiple_doctors) }}</textarea>
                            @if($errors->has('multiple_doctors'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('multiple_doctors') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.multiple_doctors_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="">{{ trans('cruds.clinic.fields.practice_specialty') }}</label>
                            <select class="form-control {{ $errors->has('practice_specialty') ? 'is-invalid' : '' }}" name="practice_specialty[]" id="practice_specialty" multiple>
                                @foreach(App\Models\Clinic::PRACTICE_SPECIALTY_SELECT as $key => $label)
                                    <option value="{{ $key }}" @if(in_array($key,$practice_specialty)) selected @endif >{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('practice_specialty'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('practice_specialty') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.practice_specialty_helper') }}</span>
                        </div>

                        <div class="form-group" id="multi_specialty_other_div"  @if(in_array("Multi-Specialty",$practice_specialty)) style="display: block;" @else style="display: none;" @endif>
                            <label class="" for="multi_specialty_other">{{ trans('cruds.clinic.fields.multi_specialty_other') }}</label>
                            <input class="form-control {{ $errors->has('multi_specialty_other') ? 'is-invalid' : '' }}" type="text" name="multi_specialty_other" id="multi_specialty_other" value="{{ old('multi_specialty_other', $clinic->multi_specialty_other) }}">
                            @if($errors->has('multi_specialty_other'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('multi_specialty_other') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.multi_specialty_other_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="">{{ trans('cruds.clinic.fields.primary_services') }}</label>
                            <select class="form-control {{ $errors->has('primary_services') ? 'is-invalid' : '' }}" name="primary_services[]" id="primary_services" multiple>
                                @foreach(App\Models\Clinic::PRIMARY_SERVICES_SELECT as $key => $label)
                                    <option value="{{ $key }}" @if(in_array($key,$primary_services)) selected @endif >{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('primary_services'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('primary_services') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.primary_services_helper') }}</span>
                        </div>

                        <div class="form-group" id="primary_services_other_div"  @if(in_array("Other",$primary_services)) style="display: block;" @else style="display: none;" @endif>
                            <label class="" for="primary_services_other">{{ trans('cruds.clinic.fields.primary_services_other') }}</label>
                            <input class="form-control {{ $errors->has('primary_services_other') ? 'is-invalid' : '' }}" type="text" name="primary_services_other" id="primary_services_other" value="{{ old('primary_services_other', $clinic->primary_services_other) }}">
                            @if($errors->has('primary_services_other'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('primary_services_other') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.primary_services_other_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="" for="address">{{ trans('cruds.clinic.fields.address') }}</label>
                            <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $clinic->address) }}">
                            @if($errors->has('address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.address_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="" for="town_state_zip">{{ trans('cruds.clinic.fields.town_state_zip') }}</label>
                            <input class="form-control {{ $errors->has('town_state_zip') ? 'is-invalid' : '' }}" type="text" name="town_state_zip" id="town_state_zip" value="{{ old('town_state_zip', $clinic->town_state_zip) }}">
                            @if($errors->has('town_state_zip'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('town_state_zip') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.town_state_zip_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="" for="office_number">{{ trans('cruds.clinic.fields.office_number') }}</label>
                            <input class="form-control {{ $errors->has('office_number') ? 'is-invalid' : '' }}" type="text" name="office_number" id="office_number" value="{{ old('office_number', $clinic->office_number) }}" >
                            @if($errors->has('office_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('office_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.office_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="" for="email">{{ trans('cruds.clinic.fields.email') }}</label>
                            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $clinic->email) }}" >
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.email_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="" for="form_notification_email">{{ trans('cruds.clinic.fields.form_notification_email') }}</label>
                            <input class="form-control {{ $errors->has('form_notification_email') ? 'is-invalid' : '' }}" type="email" name="form_notification_email" id="form_notification_email" value="{{ old('form_notification_email', $clinic->form_notification_email) }}">
                            @if($errors->has('form_notification_email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('form_notification_email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.form_notification_email_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="office_hours">{{ trans('cruds.clinic.fields.office_hours') }}</label>
                            <textarea class="form-control {{ $errors->has('office_hours') ? 'is-invalid' : '' }}" name="office_hours" id="office_hours">{{ old('office_hours', $clinic->office_hours) }}</textarea>
                            @if($errors->has('office_hours'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('office_hours') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.office_hours_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="holidays">{{ trans('cruds.clinic.fields.holidays') }}</label>
                            <textarea class="form-control {{ $errors->has('holidays') ? 'is-invalid' : '' }}" name="holidays" id="holidays">{{ old('holidays', $clinic->holidays) }}</textarea>
                            @if($errors->has('holidays'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('holidays') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.holidays_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('cruds.clinic.fields.multiple_locations') }}</label>
                            <select class="form-control {{ $errors->has('multiple_locations') ? 'is-invalid' : '' }}" name="multiple_locations" id="multiple_locations">
                                <option value disabled {{ old('multiple_locations', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Clinic::MULTIPLE_LOCATIONS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('multiple_locations', $clinic->multiple_locations) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('multiple_locations'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('multiple_locations') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.multiple_locations_helper') }}</span>
                        </div>

                        <div class="form-group" id="multiple_localtion_details_div"  @if($clinic->multiple_locations == "Yes") style="display: block;" @else style="display: none;" @endif>
                            <label for="multiple_localtion_details">{{ trans('cruds.clinic.fields.multiple_localtion_details') }}</label>
                            <?php

                            foreach($multiple_localtion_details as $location){

                            ?>  <div id="inputFormRow">
                                <input class="form-control {{ $errors->has('multiple_localtion_details') ? 'is-invalid' : '' }}" name="multiple_localtion_details[]" id="multiple_localtion_details" value="{{ $location }}">
                                <div class="input-group-append"><button id="removeRow" type="button" class="btn btn-danger btn-small amber darken-4">Remove</button></div>
                                </div>
                            <?php
                            }
                            ?>
                            @if($errors->has('multiple_localtion_details'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('multiple_localtion_details') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.multiple_localtion_details_helper') }}</span>
                            <div id="newRow"></div>
                            <button id="addRow" type="button" class="btn btn-info btn-small" style="margin-top: 10px;"><i class="material-icons left">add</i> Add More</button>
                        </div>

                        <div class="form-group" id="marketing_multiple_locations_div" @if($clinic->multiple_locations == "Yes") style="display: block;" @else style="display: none;" @endif>
                            <label>{{ trans('cruds.clinic.fields.marketing_multiple_locations') }}</label>
                            <select class="form-control {{ $errors->has('marketing_multiple_locations') ? 'is-invalid' : '' }}" name="marketing_multiple_locations" id="marketing_multiple_locations">
                                <option value disabled {{ old('marketing_multiple_locations', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Clinic::MARKETING_MULTIPLE_LOCATIONS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('marketing_multiple_locations', $clinic->marketing_multiple_locations) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('marketing_multiple_locations'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('marketing_multiple_locations') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.marketing_multiple_locations_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('cruds.clinic.fields.current_website_accurate') }}</label>
                            <select class="form-control {{ $errors->has('current_website_accurate') ? 'is-invalid' : '' }}" name="current_website_accurate" id="current_website_accurate">
                                <option value disabled {{ old('current_website_accurate', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Clinic::CURRENT_WEBSITE_ACCURATE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('current_website_accurate', $clinic->current_website_accurate) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('current_website_accurate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_website_accurate') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.current_website_accurate_helper') }}</span>
                        </div>

                        <div class="form-group" id="needs_updated_div"  @if($clinic->current_website_accurate == "No") style="display: block;" @else style="display: none;" @endif>
                            <label for="needs_updated">{{ trans('cruds.clinic.fields.needs_updated') }}</label>
                            <textarea class="form-control {{ $errors->has('needs_updated') ? 'is-invalid' : '' }}" name="needs_updated" id="needs_updated">{{ old('needs_updated', $clinic->needs_updated) }}</textarea>
                            @if($errors->has('needs_updated'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('needs_updated') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.needs_updated_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="" for="practice_management_system">{{ trans('cruds.clinic.fields.practice_management_system') }}</label>
                            <input class="form-control {{ $errors->has('practice_management_system') ? 'is-invalid' : '' }}" type="text" name="practice_management_system" id="practice_management_system" value="{{ old('practice_management_system', $clinic->practice_management_system) }}">
                            @if($errors->has('practice_management_system'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('practice_management_system') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.clinic.fields.practice_management_system_helper') }}</span>
                        </div>
                    </div>
            </li>
            <li class="step">
                <div class="step-title waves-effect blue-text">Marketing</div>
                <div class="step-content">

                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.website_type') }}</label>
                        <select class="form-control {{ $errors->has('website_type') ? 'is-invalid' : '' }}" name="website_type" id="website_type">
                            <option value disabled {{ old('website_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::WEBSITE_TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('website_type', $clinic->website_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('website_type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('website_type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.website_type_helper') }}</span>
                    </div>

                    <div class="form-group" id="microsite_website_div"  @if($clinic->website_type == "Microsite") style="display: block;" @else style="display: none;" @endif>
                        <label for="microsite_website">{{ trans('cruds.clinic.fields.microsite_website') }}</label>
                        <input class="form-control {{ $errors->has('microsite_website') ? 'is-invalid' : '' }}" type="text" name="microsite_website" id="microsite_website" value="{{ old('microsite_website', $clinic->microsite_website) }}">
                        @if($errors->has('microsite_website'))
                            <div class="invalid-feedback">
                                {{ $errors->first('microsite_website') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.microsite_website_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label class="" for="area">{{ trans('cruds.clinic.fields.area') }}</label>
                        <input class="form-control {{ $errors->has('area') ? 'is-invalid' : '' }}" type="text" name="area" id="area" value="{{ old('area', $clinic->area) }}">
                        @if($errors->has('area'))
                            <div class="invalid-feedback">
                                {{ $errors->first('area') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.area_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="">{{ trans('cruds.clinic.fields.primary_selling') }}</label>
                        <select class="form-control {{ $errors->has('primary_selling') ? 'is-invalid' : '' }}" name="primary_selling[]" id="primary_selling" multiple>
                            @foreach(App\Models\Clinic::PRIMARY_SELLING_SELECT as $key => $label)
                                <option value="{{ $key }}" @if(in_array($key,$primary_selling)) selected @endif >{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('primary_selling'))
                            <div class="invalid-feedback">
                                {{ $errors->first('primary_selling') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.primary_selling_helper') }}</span>
                    </div>
                    <div class="form-group" id="primary_selling_other_div"  @if(in_array("Other",$primary_selling)) style="display: block;" @else style="display: none;" @endif>
                        <label class="" for="primary_selling_other">{{ trans('cruds.clinic.fields.primary_selling_other') }}</label>
                        <input class="form-control {{ $errors->has('primary_selling_other') ? 'is-invalid' : '' }}" type="text" name="primary_selling_other" id="primary_selling_other" value="{{ old('primary_selling_other', $clinic->primary_selling_other) }}">
                        @if($errors->has('primary_selling_other'))
                            <div class="invalid-feedback">
                                {{ $errors->first('primary_selling_other') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.primary_selling_other_helper') }}</span>
                    </div>

                    <!-- additional fields -->
                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.hero_image') }}</label>
                        <select class="form-control {{ $errors->has('hero_image') ? 'is-invalid' : '' }}" name="hero_image" id="hero_image">
                            <option value disabled {{ old('hero_image', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::HERO_IMAGE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('hero_image', $clinic->hero_image) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('hero_image'))
                            <div class="invalid-feedback">
                                {{ $errors->first('hero_image') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.hero_image_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.docto_bio') }}</label>
                        <select class="form-control {{ $errors->has('docto_bio') ? 'is-invalid' : '' }}" name="docto_bio" id="docto_bio">
                            <option value disabled {{ old('docto_bio', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::DOCTOR_BIO_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('docto_bio', $clinic->docto_bio) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('docto_bio'))
                            <div class="invalid-feedback">
                                {{ $errors->first('docto_bio') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.docto_bio_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.testimonials') }}</label>
                        <select class="form-control {{ $errors->has('testimonials') ? 'is-invalid' : '' }}" name="testimonials" id="testimonials">
                            <option value disabled {{ old('testimonials', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::TESTIMONIALS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('testimonials', $clinic->testimonials) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('testimonials'))
                            <div class="invalid-feedback">
                                {{ $errors->first('testimonials') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.testimonials_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.google_map') }}</label>
                        <select class="form-control {{ $errors->has('google_map') ? 'is-invalid' : '' }}" name="google_map" id="google_map">
                            <option value disabled {{ old('google_map', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::GOOGLE_MAP_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('google_map', $clinic->google_map) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('google_map'))
                            <div class="invalid-feedback">
                                {{ $errors->first('google_map') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.google_map_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.patient_photos') }}</label>
                        <select class="form-control {{ $errors->has('patient_photos') ? 'is-invalid' : '' }}" name="patient_photos" id="patient_photos">
                            <option value disabled {{ old('patient_photos', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::PATIENT_PHOTO_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('patient_photos', $clinic->patient_photos) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('patient_photos'))
                            <div class="invalid-feedback">
                                {{ $errors->first('patient_photos') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.patient_photos_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label class="">{{ trans('cruds.clinic.fields.technology') }}</label>
                        <select class="form-control {{ $errors->has('technology') ? 'is-invalid' : '' }}" name="technology[]" id="technology" multiple>
                            @foreach(App\Models\Clinic::TECHNOLOGY_SELECT as $key => $label)
                                <option value="{{ $key }}" @if(in_array($key,$technology)) selected @endif >{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('technology'))
                            <div class="invalid-feedback">
                                {{ $errors->first('technology') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.technology_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="">{{ trans('cruds.clinic.fields.available_treatment') }}</label>
                        <select class="form-control {{ $errors->has('available_treatment') ? 'is-invalid' : '' }}" name="available_treatment[]" id="available_treatment" multiple>
                            @foreach(App\Models\Clinic::TREATMENT_AVAILABLE_SELECT as $key => $label)
                                <option value="{{ $key }}" @if(in_array($key,$available_treatment)) selected @endif >{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('available_treatment'))
                            <div class="invalid-feedback">
                                {{ $errors->first('available_treatment') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.available_treatment_helper') }}</span>
                    </div>
                    <div class="form-group" id="available_treatment_other_div"  @if(in_array("Other",$available_treatment)) style="display: block;" @else style="display: none;" @endif>
                        <label class="" for="available_treatment_other">{{ trans('cruds.clinic.fields.available_treatment_other') }}</label>
                        <input class="form-control {{ $errors->has('available_treatment_other') ? 'is-invalid' : '' }}" type="text" name="available_treatment_other" id="available_treatment_other" value="{{ old('available_treatment_other', $clinic->available_treatment_other) }}">
                        @if($errors->has('available_treatment_other'))
                            <div class="invalid-feedback">
                                {{ $errors->first('available_treatment_other') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.available_treatment_other_helper') }}</span>
                    </div>

                    <!-- end of additional fields -->
                    <div class="form-group hide">
                        <label for="practice_different">{{ trans('cruds.clinic.fields.practice_different') }}</label>
                        <textarea class="form-control {{ $errors->has('practice_different') ? 'is-invalid' : '' }}" name="practice_different" id="practice_different">{{ old('practice_different', $clinic->practice_different) }}</textarea>
                        @if($errors->has('practice_different'))
                            <div class="invalid-feedback">
                                {{ $errors->first('practice_different') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.practice_different_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="another_company">{{ trans('cruds.clinic.fields.another_company') }}</label>
                        <textarea class="form-control {{ $errors->has('another_company') ? 'is-invalid' : '' }}" name="another_company" id="another_company">{{ old('another_company', $clinic->another_company) }}</textarea>
                        @if($errors->has('another_company'))
                            <div class="invalid-feedback">
                                {{ $errors->first('another_company') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.another_company_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="other_marketing_notes">{{ trans('cruds.clinic.fields.other_marketing_notes') }}</label>
                        <textarea class="form-control {{ $errors->has('other_marketing_notes') ? 'is-invalid' : '' }}" name="other_marketing_notes" id="other_marketing_notes">{{ old('other_marketing_notes', $clinic->other_marketing_notes) }}</textarea>
                        @if($errors->has('other_marketing_notes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('other_marketing_notes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.other_marketing_notes_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="media_budget">{{ trans('cruds.clinic.fields.media_budget') }}</label>
                        <input class="form-control {{ $errors->has('media_budget') ? 'is-invalid' : '' }}" type="text" name="media_budget" id="media_budget" value="{{ old('media_budget', $clinic->media_budget) }}">
                        @if($errors->has('media_budget'))
                            <div class="invalid-feedback">
                                {{ $errors->first('media_budget') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.media_budget_helper') }}</span>
                    </div>

                    <div class="">
                      <h5 class="pink-text text-accent-2 card-title">Promotional Starting Prices</h5>
                    </div>
                    <div class="form-group">
                        <label for="full_arch_price">{{ trans('cruds.clinic.fields.full_arch_price') }}</label>
                        <input class="form-control {{ $errors->has('full_arch_price') ? 'is-invalid' : '' }}" type="text" name="full_arch_price" id="full_arch_price" value="{{ old('full_arch_price', $clinic->full_arch_price) }}">
                        @if($errors->has('full_arch_price'))
                            <div class="invalid-feedback">
                                {{ $errors->first('full_arch_price') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.full_arch_price_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="overdenture_price">{{ trans('cruds.clinic.fields.overdenture_price') }}</label>
                        <input class="form-control {{ $errors->has('overdenture_price') ? 'is-invalid' : '' }}" type="text" name="overdenture_price" id="overdenture_price" value="{{ old('overdenture_price', $clinic->overdenture_price) }}">
                        @if($errors->has('overdenture_price'))
                            <div class="invalid-feedback">
                                {{ $errors->first('overdenture_price') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.overdenture_price_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="single_implant_price">{{ trans('cruds.clinic.fields.single_implant_price') }}</label>
                        <input class="form-control {{ $errors->has('single_implant_price') ? 'is-invalid' : '' }}" type="text" name="single_implant_price" id="single_implant_price" value="{{ old('single_implant_price', $clinic->single_implant_price) }}">
                        @if($errors->has('single_implant_price'))
                            <div class="invalid-feedback">
                                {{ $errors->first('single_implant_price') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.single_implant_price_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label>{{ trans('cruds.clinic.fields.consultation') }}</label>
                        <select class="form-control {{ $errors->has('consultation') ? 'is-invalid' : '' }}" name="consultation" id="consultation">
                            <option value disabled {{ old('consultation', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\Clinic::CONSULTATION_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('consultation', $clinic->consultation) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('consultation'))
                            <div class="invalid-feedback">
                                {{ $errors->first('consultation') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.consultation_helper') }}</span>
                    </div>
                    <div class="form-group" id="consultation_price_div" @if($clinic->consultation == "Fee (amount)") style="display: block;" @else style="display: none;" @endif>
                        <label for="consultation_price">{{ trans('cruds.clinic.fields.consultation_price') }}</label>
                        <input class="form-control {{ $errors->has('consultation_price') ? 'is-invalid' : '' }}" type="text" name="consultation_price" id="consultation_price" value="{{ old('consultation_price', $clinic->consultation_price) }}">
                        @if($errors->has('consultation_price'))
                            <div class="invalid-feedback">
                                {{ $errors->first('consultation_price') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.consultation_price_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="financing_options">{{ trans('cruds.clinic.fields.financing_options') }}</label>
                        <select class="form-control {{ $errors->has('financing_options') ? 'is-invalid' : '' }}" name="financing_options[]" id="financing_options" multiple>
                            @foreach(App\Models\Clinic::FINANCING_OPTION_SELECT as $key => $label)
                                <option value="{{ $key }}" @if(in_array($key,$financing_options)) selected @endif >{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('financing_options'))
                            <div class="invalid-reportsending">
                                {{ $errors->first('financing_options') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.financing_options_helper') }}</span>
                    </div>
                    <div class="form-group" id="financing_options_other_div" @if(in_array("Other",$financing_options)) style="display: block;" @else style="display: none;" @endif>
                        <label for="financing_options_other">{{ trans('cruds.clinic.fields.financing_options_other') }}</label>
                        <input class="form-control {{ $errors->has('financing_options_other') ? 'is-invalid' : '' }}" type="text" name="financing_options_other" id="financing_options_other" value="{{ old('financing_options_other', $clinic->financing_options_other) }}">
                        @if($errors->has('financing_options_other'))
                            <div class="invalid-feedback">
                                {{ $errors->first('financing_options_other') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.financing_options_other_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="link1">{{ trans('cruds.clinic.fields.link1') }}</label>
                        <input class="form-control {{ $errors->has('link1') ? 'is-invalid' : '' }}" type="text" name="link1" id="link1" value="{{ old('link1', $clinic->link1) }}">
                        @if($errors->has('link1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('link1') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.link1_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="link2">{{ trans('cruds.clinic.fields.link2') }}</label>
                        <input class="form-control {{ $errors->has('link2') ? 'is-invalid' : '' }}" type="text" name="link2" id="link2" value="{{ old('link2', $clinic->link2) }}">
                        @if($errors->has('link2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('link2') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.link2_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="link3">{{ trans('cruds.clinic.fields.link3') }}</label>
                        <input class="form-control {{ $errors->has('link3') ? 'is-invalid' : '' }}" type="text" name="link3" id="link3" value="{{ old('link3', $clinic->link3) }}">
                        @if($errors->has('link3'))
                            <div class="invalid-feedback">
                                {{ $errors->first('link3') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.link3_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="usertestimonials">{{ trans('cruds.clinic.fields.usertestimonials') }}</label>
                        <textarea class="form-control {{ $errors->has('usertestimonials') ? 'is-invalid' : '' }}" name="usertestimonials" id="usertestimonials">{{ old('usertestimonials', $clinic->usertestimonials) }}</textarea>
                        @if($errors->has('usertestimonials'))
                            <div class="invalid-feedback">
                                {{ $errors->first('usertestimonials') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.usertestimonials_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="listtechnology">{{ trans('cruds.clinic.fields.listtechnology') }}</label>
                        <textarea class="form-control {{ $errors->has('listtechnology') ? 'is-invalid' : '' }}" name="listtechnology" id="listtechnology">{{ old('listtechnology', $clinic->listtechnology) }}</textarea>
                        @if($errors->has('listtechnology'))
                            <div class="invalid-feedback">
                                {{ $errors->first('listtechnology') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.listtechnology_helper') }}</span>
                    </div>

                </div>
            </li>
            <li class="step">
                <div class="step-title waves-effect blue-text">Lead Center <br>(Only applicable if we are answering Implant calls)</div>
                <div class="step-content">

                    <div class="form-group">
                        <label for="location_specifics">{{ trans('cruds.clinic.fields.location_specifics') }}</label>
                        <input class="form-control {{ $errors->has('location_specifics') ? 'is-invalid' : '' }}" type="text" name="location_specifics" id="location_specifics" value="{{ old('location_specifics', $clinic->location_specifics) }}">
                        @if($errors->has('location_specifics'))
                            <div class="invalid-feedback">location_specifics
                                {{ $errors->first('location_specifics') }}location_specifics
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.location_specifics_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="hotline_phone_number">{{ trans('cruds.clinic.fields.hotline_phone_number') }}</label>
                        <input class="form-control {{ $errors->has('hotline_phone_number') ? 'is-invalid' : '' }}" type="text" name="hotline_phone_number" id="hotline_phone_number" value="{{ old('hotline_phone_number', $clinic->hotline_phone_number) }}">
                        @if($errors->has('hotline_phone_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('hotline_phone_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.hotline_phone_number_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="appointment_confirmations">{{ trans('cruds.clinic.fields.appointment_confirmations') }}</label>
                        <input class="form-control {{ $errors->has('appointment_confirmations') ? 'is-invalid' : '' }}" type="text" name="appointment_confirmations" id="appointment_confirmations" value="{{ old('appointment_confirmations', $clinic->appointment_confirmations) }}">
                        @if($errors->has('appointment_confirmations'))
                            <div class="invalid-feedback">
                                {{ $errors->first('appointment_confirmations') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.appointment_confirmations_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="scheduling_hours">{{ trans('cruds.clinic.fields.scheduling_hours') }}</label>
                        <textarea class="form-control {{ $errors->has('scheduling_hours') ? 'is-invalid' : '' }}" name="scheduling_hours" id="scheduling_hours">{{ old('scheduling_hours', $clinic->scheduling_hours) }}</textarea>
                        @if($errors->has('scheduling_hours'))
                            <div class="invalid-feedback">
                                {{ $errors->first('scheduling_hours') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.scheduling_hours_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="primary_treatment_coordinator">{{ trans('cruds.clinic.fields.primary_treatment_coordinator') }}</label>
                        <input class="form-control {{ $errors->has('primary_treatment_coordinator') ? 'is-invalid' : '' }}" type="text" name="primary_treatment_coordinator" id="primary_treatment_coordinator" value="{{ old('primary_treatment_coordinator', $clinic->primary_treatment_coordinator) }}">
                        @if($errors->has('primary_treatment_coordinator'))
                            <div class="invalid-feedback">
                                {{ $errors->first('primary_treatment_coordinator') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.primary_treatment_coordinator_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="emails_for_scheduling">{{ trans('cruds.clinic.fields.emails_for_scheduling') }}</label>
                        <textarea class="form-control {{ $errors->has('emails_for_scheduling') ? 'is-invalid' : '' }}" name="emails_for_scheduling" id="emails_for_scheduling">{{ old('emails_for_scheduling', $clinic->emails_for_scheduling) }}</textarea>
                        @if($errors->has('emails_for_scheduling'))
                            <div class="invalid-feedback">
                                {{ $errors->first('emails_for_scheduling') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.emails_for_scheduling_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="financing">{{ trans('cruds.clinic.fields.financing') }}</label>
                        <textarea class="form-control {{ $errors->has('financing') ? 'is-invalid' : '' }}" name="financing" id="financing">{{ old('financing', $clinic->financing) }}</textarea>
                        @if($errors->has('financing'))
                            <div class="invalid-feedback">
                                {{ $errors->first('financing') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.financing_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="insurance_details">{{ trans('cruds.clinic.fields.insurance_details') }}</label>
                        <textarea class="form-control {{ $errors->has('insurance_details') ? 'is-invalid' : '' }}" name="insurance_details" id="insurance_details">{{ old('insurance_details', $clinic->insurance_details) }}</textarea>
                        @if($errors->has('insurance_details'))
                            <div class="invalid-feedback">
                                {{ $errors->first('insurance_details') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.insurance_details_helper') }}</span>
                    </div>
                    <div class="form-group">

                            <input type="hidden" name="medicaid" value="0">

                            <label for="medicaid"><input class="filled-in" type="checkbox" name="medicaid" id="medicaid" value="1" {{ $clinic->medicaid || old('medicaid', 0) === 1 ? 'checked' : '' }}><span>{{ trans('cruds.clinic.fields.medicaid') }}</span></label>

                        @if($errors->has('medicaid'))
                            <div class="invalid-feedback">
                                {{ $errors->first('medicaid') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.medicaid_helper') }}</span>
                    </div>
                    <div class="">

                            <input type="hidden" name="medicare" value="0">

                            <label for="medicare"><input class="filled-in" type="checkbox" name="medicare" id="medicare" value="1" {{ $clinic->medicare || old('medicare', 0) === 1 ? 'checked' : '' }}><span>{{ trans('cruds.clinic.fields.medicare') }}</span></label>

                        @if($errors->has('medicare'))
                            <div class="invalid-feedback">
                                {{ $errors->first('medicare') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.medicare_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="doctor_specifics">{{ trans('cruds.clinic.fields.doctor_specifics') }}</label>
                        <textarea class="form-control {{ $errors->has('doctor_specifics') ? 'is-invalid' : '' }}" name="doctor_specifics" id="doctor_specifics">{{ old('doctor_specifics', $clinic->doctor_specifics) }}</textarea>
                        @if($errors->has('doctor_specifics'))
                            <div class="invalid-feedback">
                                {{ $errors->first('doctor_specifics') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.doctor_specifics_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="covid_19_specifics">{{ trans('cruds.clinic.fields.covid_19_specifics') }}</label>
                        <textarea class="form-control {{ $errors->has('covid_19_specifics') ? 'is-invalid' : '' }}" name="covid_19_specifics" id="covid_19_specifics">{{ old('covid_19_specifics', $clinic->covid_19_specifics) }}</textarea>
                        @if($errors->has('covid_19_specifics'))
                            <div class="invalid-feedback">
                                {{ $errors->first('covid_19_specifics') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.covid_19_specifics_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="languages_spoken">{{ trans('cruds.clinic.fields.languages_spoken') }}</label>
                        <input class="form-control {{ $errors->has('languages_spoken') ? 'is-invalid' : '' }}" type="text" name="languages_spoken" id="languages_spoken" value="{{ old('languages_spoken', $clinic->languages_spoken) }}">
                        @if($errors->has('languages_spoken'))
                            <div class="invalid-feedback">
                                {{ $errors->first('languages_spoken') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.languages_spoken_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="leadcenteraccountmanager">{{ trans('cruds.clinic.fields.leadcenteraccountmanager') }}</label>
                        <input class="form-control {{ $errors->has('leadcenteraccountmanager') ? 'is-invalid' : '' }}" type="text" name="leadcenteraccountmanager" id="leadcenteraccountmanager" value="{{ old('leadcenteraccountmanager', $clinic->leadcenteraccountmanager) }}">
                        @if($errors->has('leadcenteraccountmanager'))
                            <div class="invalid-feedback">
                                {{ $errors->first('leadcenteraccountmanager') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.leadcenteraccountmanager_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="dih_account_details">{{ trans('cruds.clinic.fields.dih_account_details') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('dih_account_details') ? 'is-invalid' : '' }}" name="dih_account_details" id="dih_account_details">{{ old('dih_account_details', $clinic->dih_account_details) }}</textarea>
                        @if($errors->has('dih_account_details'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dih_account_details') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.clinic.fields.dih_account_details_helper') }}</span>
                    </div>

                </div>
            </li>

            @if($isadmin)
            <div class="form-group">
                <label for="virtual_consultation">{{ trans('cruds.clinic.fields.virtual_consultation') }}</label>
                <input class="form-control {{ $errors->has('virtual_consultation') ? 'is-invalid' : '' }}" type="text" name="virtual_consultation" id="virtual_consultation" value="{{ old('virtual_consultation', $clinic->virtual_consultation) }}">
                @if($errors->has('virtual_consultation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('virtual_consultation') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.virtual_consultation_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="consult_email_patients">{{ trans('cruds.clinic.fields.consult_email_patients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('consult_email_patients') ? 'is-invalid' : '' }}" name="consult_email_patients" id="consult_email_patients">{!! old('consult_email_patients', $clinic->consult_email_patients) !!}</textarea>
                @if($errors->has('consult_email_patients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consult_email_patients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.consult_email_patients_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="specialty">{{ trans('cruds.clinic.fields.specialty') }}</label>
                <textarea class="form-control {{ $errors->has('specialty') ? 'is-invalid' : '' }}" name="specialty" id="specialty">{{ old('specialty', $clinic->specialty) }}</textarea>
                @if($errors->has('specialty'))
                    <div class="invalid-feedback">
                        {{ $errors->first('specialty') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.specialty_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="consultation_details_offers">{{ trans('cruds.clinic.fields.consultation_details_offers') }}</label>
                <textarea class="form-control {{ $errors->has('consultation_details_offers') ? 'is-invalid' : '' }}" name="consultation_details_offers" id="consultation_details_offers">{{ old('consultation_details_offers', $clinic->consultation_details_offers) }}</textarea>
                @if($errors->has('consultation_details_offers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('consultation_details_offers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.consultation_details_offers_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="services_offered_pricing">{{ trans('cruds.clinic.fields.services_offered_pricing') }}</label>
                <textarea class="form-control {{ $errors->has('services_offered_pricing') ? 'is-invalid' : '' }}" name="services_offered_pricing" id="services_offered_pricing">{{ old('services_offered_pricing', $clinic->services_offered_pricing) }}</textarea>
                @if($errors->has('services_offered_pricing'))
                    <div class="invalid-feedback">
                        {{ $errors->first('services_offered_pricing') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.services_offered_pricing_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="google_map_location">{{ trans('cruds.clinic.fields.google_map_location') }}</label>
                <textarea class="form-control {{ $errors->has('google_map_location') ? 'is-invalid' : '' }}" name="google_map_location" id="google_map_location">{{ old('google_map_location', $clinic->google_map_location) }}</textarea>
                @if($errors->has('google_map_location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_map_location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_map_location_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="reply_texts_patients">{{ trans('cruds.clinic.fields.reply_texts_patients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('reply_texts_patients') ? 'is-invalid' : '' }}" name="reply_texts_patients" id="reply_texts_patients">{!! old('reply_texts_patients', $clinic->reply_texts_patients) !!}</textarea>
                @if($errors->has('reply_texts_patients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reply_texts_patients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.reply_texts_patients_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra_notes">{{ trans('cruds.clinic.fields.extra_notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('extra_notes') ? 'is-invalid' : '' }}" name="extra_notes" id="extra_notes">{!! old('extra_notes', $clinic->extra_notes) !!}</textarea>
                @if($errors->has('extra_notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('extra_notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.extra_notes_helper') }}</span>
            </div>
            @endif
            @if($isadmin)
            <div class="form-group">
                <label for="slack_webhook_url">{{ trans('cruds.clinic.fields.slack_webhook_url') }}</label>
                <input class="form-control {{ $errors->has('slack_webhook_url') ? 'is-invalid' : '' }}" type="text" name="slack_webhook_url" id="slack_webhook_url" value="{{ old('slack_webhook_url', $clinic->slack_webhook_url) }}">
                @if($errors->has('slack_webhook_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slack_webhook_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.slack_webhook_url_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="callrail_company">{{ trans('cruds.clinic.fields.callrail_company') }}</label>
                <input class="form-control {{ $errors->has('callrail_company') ? 'is-invalid' : '' }}" type="number" name="callrail_company" id="callrail_company" value="{{ old('callrail_company', $clinic->callrail_company) }}" step="1">
                @if($errors->has('callrail_company'))
                    <div class="invalid-feedback">
                        {{ $errors->first('callrail_company') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.callrail_company_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_number">{{ trans('cruds.clinic.fields.twilio_number') }}</label>
                <input class="form-control {{ $errors->has('twilio_number') ? 'is-invalid' : '' }}" type="text" name="twilio_number" id="twilio_number" value="{{ old('twilio_number', $clinic->twilio_number) }}">
                @if($errors->has('twilio_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_subid">{{ trans('cruds.clinic.fields.twilio_subid') }}</label>
                <input class="form-control {{ $errors->has('twilio_subid') ? 'is-invalid' : '' }}" type="text" name="twilio_subid" id="twilio_subid" value="{{ old('twilio_subid', $clinic->twilio_subid) }}">
                @if($errors->has('twilio_subid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_subid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_subid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twilio_token">{{ trans('cruds.clinic.fields.twilio_token') }}</label>
                <input class="form-control {{ $errors->has('twilio_token') ? 'is-invalid' : '' }}" type="text" name="twilio_token" id="twilio_token" value="{{ old('twilio_token', $clinic->twilio_token) }}">
                @if($errors->has('twilio_token'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twilio_token') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.twilio_token_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_analytics">{{ trans('cruds.clinic.fields.google_analytics') }}</label>
                <input class="form-control {{ $errors->has('google_analytics') ? 'is-invalid' : '' }}" type="text" name="google_analytics" id="google_analytics" value="{{ old('google_analytics', $clinic->google_analytics) }}">
                @if($errors->has('google_analytics'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_analytics') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_analytics_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_ads">{{ trans('cruds.clinic.fields.google_ads') }}</label>
                <input class="form-control {{ $errors->has('google_ads') ? 'is-invalid' : '' }}" type="text" name="google_ads" id="google_ads" value="{{ old('google_ads', $clinic->google_ads) }}">
                @if($errors->has('google_ads'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_ads') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_ads_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="facebook">{{ trans('cruds.clinic.fields.facebook') }}</label>
                <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text" name="facebook" id="facebook" value="{{ old('facebook', $clinic->facebook) }}">
                @if($errors->has('facebook'))
                    <div class="invalid-feedback">
                        {{ $errors->first('facebook') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.facebook_helper') }}</span>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.google_ad_team') }}</label>
                <select class="form-control {{ $errors->has('google_ad_team') ? 'is-invalid' : '' }}" name="google_ad_team" id="google_ad_team">
                    <option value disabled {{ old('google_ad_team', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::GOOGLE_AD_TEAM as $key => $label)
                        <option value="{{ $key }}" {{ old('google_ad_team', $clinic->google_ad_team) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('google_ad_team'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_ad_team') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.google_ad_team_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.facebook_ad_team') }}</label>
                <select class="form-control {{ $errors->has('facebook_ad_team') ? 'is-invalid' : '' }}" name="facebook_ad_team" id="facebook_ad_team">
                    <option value disabled {{ old('facebook_ad_team', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::FACEBOOK_AD_TEAM as $key => $label)
                        <option value="{{ $key }}" {{ old('facebook_ad_team', $clinic->facebook_ad_team) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('facebook_ad_team'))
                    <div class="invalid-feedback">
                        {{ $errors->first('facebook_ad_team') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.facebook_ad_team_helper') }}</span>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.nurture_automation') }}</label>
                <select class="form-control {{ $errors->has('nurture_automation') ? 'is-invalid' : '' }}" name="nurture_automation" id="nurture_automation">
                    <option value {{ old('nurture_automation', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::NURTURE_AUTOMATION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('nurture_automation', $clinic->nurture_automation) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('nurture_automation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nurture_automation') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.nurture_automation_helper') }}</span>
            </div>

            <div class="form-group">
                <div class="col-lg-12">
                    <div class="input-group" id="smtpcode">
                        <label class="label">{{ trans('cruds.clinic.fields.smtpUsername') }}</label>
                        <input type="text" name="smtpUsername" placeholder="smtp Username" value="{{ old('smtpUsername', $clinic->smtpUsername) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.smtpServer') }}</label>
                        <input type="text" name="smtpServer" placeholder="smtp Server" value="{{ old('smtpServer', $clinic->smtpServer) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.smtpPort') }}</label>
                        <input type="text" name="smtpPort" placeholder="smtp Port" value="{{ old('smtpPort', $clinic->smtpPort) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.smtpPassword') }}</label>
                        <input type="text" name="smtpPassword" placeholder="smtp Password" value="{{ old('smtpPassword', $clinic->smtpPassword) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.smtpMailer') }}</label>
                        <input type="text" name="smtpMailer" placeholder="smtp Mailer" value="{{ old('smtpMailer', $clinic->smtpMailer) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.patient_journey_campaign') }}</label>
                <select class="form-control {{ $errors->has('patient_journey_campaign') ? 'is-invalid' : '' }}" name="patient_journey_campaign" id="patient_journey_campaign">
                    <option value {{ old('patient_journey_campaign', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::PATIENT_JOURNEY_CAMPAIGN_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('patient_journey_campaign', $clinic->patient_journey_campaign) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('patient_journey_campaign'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient_journey_campaign') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.patient_journey_campaign_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="custom_message">{{ trans('cruds.clinic.fields.custom_message') }}</label>
                <textarea class="form-control {{ $errors->has('custom_message') ? 'is-invalid' : '' }}" name="custom_message" id="custom_message">{{ old('custom_message', $clinic->custom_message) }}</textarea>
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
                    <option value  {{ old('lead_center', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    <option value="America/New_York"  {{ old('timezone', $clinic->timezone) === 'America/New_York' ? 'selected' : '' }}>Eastern</option>
                    <option value="America/Chicago"  {{ old('timezone', $clinic->timezone) === 'America/Chicago' ? 'selected' : '' }}>Central</option>
                    <option value="America/Denver"  {{ old('timezone', $clinic->timezone) === 'America/Denver' ? 'selected' : '' }}>Mountain</option>
                    <option value="America/Los_Angeles"  {{ old('timezone', $clinic->timezone) === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific</option>
                </select>
                @if($errors->has('timezone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('timezone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.timezone_helper') }}</span>
            </div>
            @endif
            @if($isadmin)
            <div class="form-group">
                <label for="marketingdashboardurl">{{ trans('cruds.clinic.fields.marketingdashboardurl') }}</label>
                <input class="form-control {{ $errors->has('marketingdashboardurl') ? 'is-invalid' : '' }}" type="text" name="marketingdashboardurl" id="marketingdashboardurl" value="{{ old('marketingdashboardurl', $clinic->marketingdashboardurl) }}">
                @if($errors->has('marketingdashboardurl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('marketingdashboardurl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.marketingdashboardurl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="schedulemeetingurl">{{ trans('cruds.clinic.fields.schedulemeetingurl') }}</label>
                <input class="form-control {{ $errors->has('schedulemeetingurl') ? 'is-invalid' : '' }}" type="text" name="schedulemeetingurl" id="schedulemeetingurl" value="{{ old('schedulemeetingurl', $clinic->schedulemeetingurl) }}">
                @if($errors->has('schedulemeetingurl'))
                    <div class="invalid-feedback">
                        {{ $errors->first('schedulemeetingurl') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.schedulemeetingurl_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="salestrainingurl">{{ trans('cruds.clinic.fields.salestrainingurl') }}</label>
                <input class="form-control {{ $errors->has('salestrainingurl') ? 'is-invalid' : '' }}" type="text" name="salestrainingurl" id="salestrainingurl" value="{{ old('salestrainingurl', $clinic->salestrainingurl) }}">
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
                        <option value="{{ $key }}" {{ old('reportsending', $clinic->reportsending) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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
                 <div class="col-lg-12">
                     <div class="input-group">
                         <label class="label" name="emailcampaign">{{ trans('cruds.clinic.fields.emailcampaign') }}</label>
                         <div class="p-t-10">
                             <label class="radio-container m-r-45">Yes
                                 <input type="radio" name="emailcampaign" value="1"  {{ ($clinic->emailcampaign == '1')? 'checked' : '' }} />
                                 <span class="checkmark"></span>
                             </label>
                             <label class="radio-container">No
                                 <input type="radio" name="emailcampaign" value="0" {{ ($clinic->emailcampaign == '0')? 'checked' : '' }}/>
                                 <span class="checkmark"></span>
                             </label>
                         </div>
                     </div>
                 </div>

            <div class="form-group">
                <label for="reportrecipients">{{ trans('cruds.clinic.fields.reportrecipients') }}</label>
                <input class="form-control {{ $errors->has('reportrecipients') ? 'is-invalid' : '' }}" type="text" name="reportrecipients" id="reportrecipients" value="{{ old('reportrecipients', $clinic->reportrecipients) }}">
                @if($errors->has('reportrecipients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reportrecipients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.reportrecipients_helper') }}</span>
            </div>
            @endif

            @if($isadmin)
            <div class="form-group">
                <div class="col-lg-12">
                    <div class="input-group">
                        <label class="label" name="nexhealth">{{ trans('cruds.clinic.fields.nexhealth') }}</label>
                        <div class="p-t-10">
                            <label class="radio-container m-r-45">Yes
                                <input type="radio" name="nexhealthselection" value="1"  {{ ($clinic->nexhealthselection == '1')? 'checked' : '' }} />
                                <span class="checkmark"></span>
                            </label>
                            <label class="radio-container">No
                                <input type="radio" name="nexhealthselection" value="0" {{ ($clinic->nexhealthselection == '0')? 'checked' : '' }}/>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="input-group" id="mycode">
                        <label class="label">{{ trans('cruds.clinic.fields.subdomain') }}</label>
                        <input type="text" name="subdomain" placeholder="Enter Subdomain" value="{{ old('subdomain', $clinic->subdomain) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.location') }}</label>
                        <input type="text" name="location" placeholder="Enter Location" value="{{ old('location', $clinic->location) }}">
                        <label class="label">{{ trans('cruds.clinic.fields.nexhealthkey') }}</label>
                        <input type="text" name="nexhealthkey" placeholder="Enter key" value="{{ old('nexhealthkey', $clinic->nexhealthkey) }}">
                    </div>
                </div>

            </div>
            <div class="form-group">
                <label>{{ trans('cruds.clinic.fields.lead_center') }}</label>
                <select class="form-control {{ $errors->has('lead_center') ? 'is-invalid' : '' }}" name="lead_center" id="lead_center">
                    <option value disabled {{ old('lead_center', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::LEAD_CENTER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('lead_center', $clinic->lead_center) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
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

                    <input type="hidden" name="autosms" value="0">

                    <label for="autosms"><input class="filled-in" type="checkbox" name="autosms" id="autosms" value="1" {{ $clinic->autosms || old('autosms', 0) === 1 ? 'checked' : '' }}><span>{{ trans('cruds.clinic.fields.autosms') }}</span></label>

                @if($errors->has('autosms'))
                    <div class="invalid-feedback">
                        {{ $errors->first('autosms') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.autosms_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="autosmstext">{{ trans('cruds.clinic.fields.autosmstext') }}</label>
                <textarea class="form-control {{ $errors->has('autosmstext') ? 'is-invalid' : '' }}" name="autosmstext" id="autosmstext">{{ old('autosmstext', $clinic->autosmstext) }}</textarea>
                @if($errors->has('autosmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('autosmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.autosmstext_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="priorsmstext">{{ trans('cruds.clinic.fields.priorsmstext') }}</label>
                <textarea class="form-control {{ $errors->has('priorsmstext') ? 'is-invalid' : '' }}" name="priorsmstext" id="priorsmstext">{{ old('priorsmstext', $clinic->priorsmstext) }}</textarea>
                @if($errors->has('priorsmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('priorsmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.priorsmstext_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="workingdaysmstext">{{ trans('cruds.clinic.fields.workingdaysmstext') }}</label>
                <textarea class="form-control {{ $errors->has('workingdaysmstext') ? 'is-invalid' : '' }}" name="workingdaysmstext" id="workingdaysmstext">{{ old('workingdaysmstext', $clinic->workingdaysmstext) }}</textarea>
                @if($errors->has('workingdaysmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('workingdaysmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.workingdaysmstext_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nonworkingdaysmstext">{{ trans('cruds.clinic.fields.nonworkingdaysmstext') }}</label>
                <textarea class="form-control {{ $errors->has('nonworkingdaysmstext') ? 'is-invalid' : '' }}" name="nonworkingdaysmstext" id="nonworkingdaysmstext">{{ old('nonworkingdaysmstext', $clinic->nonworkingdaysmstext) }}</textarea>
                @if($errors->has('nonworkingdaysmstext'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nonworkingdaysmstext') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.nonworkingdaysmstext_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="managers">{{ trans('cruds.clinic.fields.manager') }}</label>

                <select class="browser-default form-control select2 {{ $errors->has('managers') ? 'is-invalid' : '' }}" name="managers[]" id="managers" multiple>
                    @foreach($managers as $id => $manager)
                        <option value="{{ $manager['id'] }}" {{ (in_array($manager['id'], old('managers', [])) || $clinic->managers->contains($manager['id'])) ? 'selected' : '' }}>{{ $manager['name'] }} - {{ $manager['roles'][0]->title }}</option>
                    @endforeach
                </select>
                @if($errors->has('managers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('managers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.manager_helper') }}</span>
            </div>
            @endif
            <div class="form-group <?php echo $display;?>">
                <label class="required">{{ trans('cruds.clinic.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $clinic->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.status_helper') }}</span>
            </div>


            <div class="form-group input-field">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
            </ul>
        </form>
    </div>

     <div class="card-body" id="template" style="display:none;">
        <div class="table-wrap">
        <form method="POST" action="" enctype="multipart/form-data">
            @method('PUT')
            @csrf
        <div class="content-area content-right">
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered" id="editableTable123">
                    <thead>
                      <tr>
                        <th width="10%" class="text-center" style="display:none;">Edit</th>
                        <th width="0.5%" class="text-center">Day</th>
                        <th width="33%" class="text-center">Text Template</th>
                        <th width="15%" class="text-center">Email Subject</th>
                        <th width="51%" class="text-center">Email Template</th>
                        <th width="0.5%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($template as $key =>$format)
                         @php $key = $loop->iteration @endphp

                        <tr data-id="{{$key}}">

                        <td data-field="dayinterval" class="dayinterval" style="text-align:center">{{$format->dayinterval}}</td>
                        <td data-field="text_template">
                            @if($format->text_template != 'hold')
                            <textarea class="form-control text_template {{ $errors->has('text_template') ? 'is-invalid' : '' }}" name="text_template" id="text_template">{{$format->text_template}}</textarea><br/>
                            @else
                                {{$format->text_template}}
                            @endif
                        </td>
                        <td data-field="email_subject">
                            @if($format->email_subject != 'hold')
                            <textarea class="form-control email_subject {{ $errors->has('email_subject') ? 'is-invalid' : '' }}" name="email_subject" id="email_subject">{{$format->email_subject}}</textarea><br/>
                            <button id="btn" class="btn" style="display:none">Save</button>
                            @else
                                {{$format->email_subject}}
                            @endif
                        </td>
                        <td data-field="email_template">
                            @if($format->email_subject != 'hold')
                            <textarea class="form-control email_template {{ $errors->has('email_template') ? 'is-invalid' : '' }}" name="email_template" id="email_template">{{$format->email_template}}</textarea><br/>
                           <button id="btn" class="btn" style="display:none">Save</button>
                            @else
                                {{$format->email_template}}
                            @endif
                        </td>
                        <td>
                            @if($format->email_subject != 'hold' || $format->text_template != 'hold' || $format->email_template != 'hold')
                            <button class="btn-small savebtn"><i class="fa fa-save"></i></button>
                            @endif
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </form>
        </div>
    </div>
    <div class="card-body" id="preconsultation" style="display:none;">
        <div class="table-wrap">
        <form method="POST" action="" enctype="multipart/form-data">
            @method('PUT')
            @csrf
        <div class="content-area content-right">
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-bordered" id="editableTable123">
                    <thead>
                      <tr>
                        <th width="10%" class="text-center" style="display:none;">Edit</th>
                        <th width="0.5%" class="text-center">Day</th>
                        <th width="33%" class="text-center">Text Template</th>
                        <th width="15%" class="text-center">Email Subject</th>
                        <th width="51%" class="text-center">Email Template</th>
                        <th width="0.5%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($patientcampaign as $key =>$pjourneytemplate)
                         @php $key = $loop->iteration @endphp

                        <tr data-id="{{$key}}">

                        <td data-field="pjourney_dayinterval" class="pjourney_dayinterval" style="text-align:center">{{$pjourneytemplate->dayinterval}}</td>
                        <td data-field="pjourney_text_template">
                            @if($pjourneytemplate->text_template != 'hold')
                            <textarea class="form-control pjourney_text_template {{ $errors->has('pjourney_text_template') ? 'is-invalid' : '' }}" name="pjourney_text_template" id="pjourneytext_template">{{$pjourneytemplate->text_template}}</textarea><br/>
                            @else
                                {{$pjourneytemplate->text_template}}
                            @endif
                        </td>
                        <td data-field="pjourney_email_subject">
                            @if($pjourneytemplate->email_subject != 'hold')
                            <textarea class="form-control pjourney_email_subject {{ $errors->has('pjourney_email_subject') ? 'is-invalid' : '' }}" name="pjourney_email_subject" id="email_subject">{{$pjourneytemplate->email_subject}}</textarea><br/>
                            <button id="btn" class="btn" style="display:none">Save</button>
                            @else
                                {{$pjourneytemplate->email_subject}}
                            @endif
                        </td>
                        <td data-field="pjourney_email_template">
                            @if($pjourneytemplate->email_subject != 'hold')
                            <textarea class="form-control pjourney_email_template {{ $errors->has('pjourney_email_template') ? 'is-invalid' : '' }}" name="pjourney_email_template" id="pjourney_email_template">{{$pjourneytemplate->email_template}}</textarea><br/>
                           <button id="btn" class="btn" style="display:none">Save</button>
                            @else
                                {{$pjourneytemplate->email_template}}
                            @endif
                        </td>
                        <td>
                            <button class="btn-small pjourneysavebtn"><i class="fa fa-save"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection

@section('scripts')
<script src="{{asset('js/form-wizard.min.js')}}"></script>

<script>
    $('#yourElement').addClass('animated bounceOutLeft');
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

  $(function () {

        if($("#nurture_automation").val() == 'No' || $("#nurture_automation").val() == '') {
            $('#smtpcode').hide();
        }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $(".savebtn").click(function(e){
        e.preventDefault();
        var currentRow=$(this).closest("tr");
        var clinicID = '{{ $clinic->id }}';
        var dayintervalValue=$(this).closest('tr').find('.dayinterval').text();
        var texttemplateValue = $(this).closest('tr').find('.text_template').val();
        var emailsubjectValue = $(this).closest('tr').find('.email_subject').val();
        var emailtemplateValue = $(this).closest('tr').find('.email_template').val();
        var url = '{{ route("admin.clinic.update", [$clinic->id]) }}';

        $.ajax({
           url:url,
           method:'POST',
           data:{
                  clinicid:clinicID,
                  dayinterval:dayintervalValue,
                  text_template:texttemplateValue,
                  email_subject:emailsubjectValue,
                  email_template:emailtemplateValue
                },
           success:function(response){
              if(response.success){
                  alert(response.message) //Message come from controller
              }else{
                  alert("Error")
              }
           },
           error:function(error){
              console.log(error)
           }
        });
    });

    if($("input[name='nexhealthselection']:checked").val() == '1')
    {
        $('#mycode').show();
    }
    else
    {
        $('#mycode').hide();
    }

    $('input[name="nexhealthselection"]').on('click', function() {
      //  alert($(this).val());
        if ($(this).val() == '1') {
            $('#mycode').show();
        }
        else {
            $('#mycode').hide();
        }
    });

    $('#multiple_locations').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'Yes') {
                $('#multiple_localtion_details_div').show();
                $('#marketing_multiple_locations_div').show();
                return true;
            }else{
                $('#multiple_localtion_details_div').hide();
                $('#marketing_multiple_locations_div').hide();
                return true;
            }
        });
    });


    $('#practice_specialty').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'Multi-Specialty') {
                $('#multi_specialty_other_div').show();
                return true;
            }else{
                $('#multi_specialty_other_div').hide();
                return true;
            }
        });
    });

    $('#primary_services').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'Other') {
                $('#primary_services_other_div').show();
                return true;
            }else{
                $('#primary_services_other_div').hide();
                return true;
            }
        });
    });

    $('#current_website_accurate').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'No') {
                $('#needs_updated_div').show();
                return true;
            }else{
                $('#needs_updated_div').hide();
                return true;
            }
        });
    });

    $('#website_type').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'Microsite') {
                $('#microsite_website_div').show();
                return true;
            }else{
                $('#microsite_website_div').hide();
                return true;
            }
        });
    });

    $('#primary_selling').on('change', function () {
        $.each($(this).find('option:selected'), function (index, item) {
            var selected = $(item).val();
            if (selected == 'Other') {
                $('#primary_selling_other_div').show();
                return true;
            }else{
                $('#primary_selling_other_div').hide();
                return true;
            }
        });
    });

        $('#available_treatment').on('change', function () {
            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).val();
                if (selected == 'Other') {
                    $('#available_treatment_other_div').show();
                    return true;
                }else{
                    $('#available_treatment_other_div').hide();
                    return true;
                }
            });
        });

        $('#consultation').on('change', function () {
            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).val();
                if (selected == 'Fee (amount)') {
                    $('#consultation_price_div').show();
                    return true;
                }else{
                    $('#consultation_price_div').hide();
                    return true;
                }
            });
        });

        $('#financing_options').on('change', function () {
            $.each($(this).find('option:selected'), function (index, item) {
                var selected = $(item).val();
                if (selected == 'Other') {
                    $('#financing_options_other_div').show();
                    return true;
                }else{
                    $('#financing_options_other_div').hide();
                    return true;
                }
            });
        });

        $(".pjourneysavebtn").click(function(e){
        e.preventDefault();
        var currentRow=$(this).closest("tr");
        var clinicID = '{{ $clinic->id }}';
        var dayintervalValue=$(this).closest('tr').find('.pjourney_dayinterval').text();
        var texttemplateValue = $(this).closest('tr').find('.pjourney_text_template').val();
        var emailsubjectValue = $(this).closest('tr').find('.pjourney_email_subject').val();
        var emailtemplateValue = $(this).closest('tr').find('.pjourney_email_template').val();
        var url = '{{ route("admin.clinic.patientjourney", [$clinic->id]) }}';

        $.ajax({
           url:url,
           method:'POST',
           data:{
                  clinicid:clinicID,
                  dayinterval:dayintervalValue,
                  text_template:texttemplateValue,
                  email_subject:emailsubjectValue,
                  email_template:emailtemplateValue
                },
           success:function(response){
              if(response.success){
                  alert(response.message) //Message come from controller
              }else{
                  alert("Error")
              }
           },
           error:function(error){
              console.log(error)
           }
        });
    });

    });

});

</script>
@endsection
