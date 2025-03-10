@extends('layouts.admin')

{{-- page title --}}
@section('title','Account Settings')

{{-- vendor styles --}}
@section('vendor-style')
<!-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}"> -->
<link rel="stylesheet" type="text/css" href="{{asset('vendors/vendors-rtl.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/quill/katex.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/quill/monokai-sublime.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/quill/quill.snow.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/quill/quill.bubble.css')}}"
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-account-settings.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-chat.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/perfect-scrollbar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datetimepicker.css')}}">
<style type="text/css">
/* Credit Card */
.credit-card {
    width: 360px;
    height: 450px;
    margin: 60px auto 60px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #fff;
    box-shadow: 1px 2px 3px 0 rgba(0,0,0,.10);
}
/* Title */
.title {
    font-size: 18px;
    margin: 0;
    color: #5e6977;
}
/* Common */
.card-number,
.cvv-input input,
.month select,
.year select {
    font-size: 14px;
    font-weight: bold;
    line-height: 14px;
}
 
.card-number,
.month select,
.year select {
    font-size: 14px;
    font-weight: bold;
    line-height: 14px;
}
 
.card-number,
.cvv-details,
.cvv-input input,
.month select,
.year select {
    opacity: 1;
    color: #000000;

}
/* Card Number */
.card-number {
    width: 100%;
    margin-bottom: 20px;
    padding-left: 20px;
    border: 2px solid #e1e8ee;
    border-radius: 6px;
}
/* Date Field */
.month select,
.year select {
    width: 145px;
    margin-bottom: 20px;
    padding-left: 20px;
    border: 2px solid #e1e8ee;
    border-radius: 6px;
    background: url('caret.png') no-repeat;
    background-position: 85% 50%;
    -moz-appearance: none;
    -webkit-appearance: none;
}
 
.month select {
    float: left;
}
 
.year select {
    float: right;
}
/* Card Verification Field */
.cvv-input input {
    float: left;
    width: 145px;
    padding-left: 20px;
    border: 2px solid #e1e8ee;
    border-radius: 6px;
    background: #fff;
}
 
.cvv-details {
    font-size: 12px;
    font-weight: 300;
    line-height: 16px;
    float: right;
}
 
.cvv-details p {
    margin-top: 6px;
}
/* Buttons Section */

.proceed-btn {
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    border-color: transparent;
    border-radius: 6px;
}
 
.proceed-btn {
    margin-bottom: 10px;
    background: #7dc855;
}
 

.proceed-btn a {
    text-decoration: none;
}
 
.proceed-btn a {
    color: #fff;
}
 

.form-header {
    height: 60px;
    padding: 20px 30px 0;
    border-bottom: 1px solid #e1e8ee;
}
 
.form-body {
    padding: 30px 30px 20px;
}

.hidesavebutton{
    display: none;
}
.showloader{
    display: none;
}
.shownotificationloader{
    display: none;
}
</style>
@endsection


@section('content')

<?php 
    $timezone = "America/New_York";
    if($crmCustomer->clinic->timezone != null || $crmCustomer->clinic->timezone !=  ''){
            $timezone = $crmCustomer->clinic->timezone;
    }    
?>

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

@if (\Session::has('error'))
    <div class="card-alert card red">
        <div class="card-content white-text">
          <p>{!! \Session::get('error') !!}</p>
        </div>
        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
<div class="col s12 m5">
    <div class="display-flex media">
      <a href="#" class="avatar">
        <img src="/images/user/userwhitet.png" alt="users view avatar" class="z-depth-4 circle" height="44" width="44">
      </a>
      <div class="media-body">
        <h5 class="media-heading">
          <span class="users-view-name"> {{$crmCustomer->first_name}} {{$crmCustomer->last_name}}
            <?php 
                    if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $crmCustomer->phone,  $matches ) )
                            {
                                echo $phone = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
                            } else{
                                echo $crmCustomer->phone;
                            }
            ?>
           </span>  
           <?php 
            $user = auth()->user()->roles; 
            $userrole = $user[0]['title'];
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes"  && $crmCustomer->phone_form == "Phone Call"){
            ?>  
            <a class="" id="markasspam" href="#" title="Mark as Spam" onclick="markasspam()"><i class="material-icons vertical-align-bottom white-text">block </i></a> 
           <?php } ?>   
        </h5>

        
      </div>
      <?php
        $user = auth()->user()->roles;
        $userrole = $user[0]['title']; 
        if($crmCustomer->has_sms == 1 ){?>
        <form action="" method="POST" class="mt-1 ml-1">
            <div class="switch" id="has_sms_switch">
            <label>
              Off
              <input type="checkbox" name="has_sms" id="has_sms" value="1" {{ $crmCustomer->has_sms || old('has_sms', 0) === 1 ? 'checked' : '' }} >
              <span class="lever"></span>
              On
            </label>
            </div>
        </form> 
      <?php } ?>
    </div>
</div>
<?php 
    if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
?>

<div class="col s12 m5">
    <div class="right"> 
        <h6 class="media-heading white-text">Office Number: {{$crmCustomer->clinic->office_number}}</h6>
    </div>
</div>
<?php } ?> 
<section class="basic-tabs mt-1 section users-edit">
  <div class="row">
    <div class="col s12">
      <!-- tabs  -->
      
        <ul class="tabs tab-demo z-depth-1 card-border-gray">
          <li class="tab col m2">
            <a href="#general" class="active">
              <i class="material-icons">brightness_low</i>
              <span>General</span>
            </a>
          </li>
          <li class="tab col m2">
            <a href="#sms">
              <i class="material-icons">sms</i>
              <span>SmS</span>
            </a>
          </li>

        <?php 
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
        ?>
            <li class="tab col m2">
                <a href="#creditcard">
                  <i class="material-icons">credit_card</i>
                  <span>Credit Card</span>
                </a>
            </li>

        <?php } ?>

        <?php 
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
        ?>
            <li class="tab col m2">
                <a href="#dihinformaiton">
                  <i class="material-icons">account_balance</i>
                  <span>DIH Account Details</span>
                </a>
            </li>

        <?php } ?>
          
          
          
        </ul>
      
    </div>
    <div class="col s12">
        <div id="general" class="card-panel col s12">
            <div class="card col s6">
                <form method="POST" id="formValidate" action="{{ route('admin.crm-customers.update', [$crmCustomer->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <?php
                $disabled = '';
                if($crmCustomer->convert_to_deal == 1){
                    $disabled = 'disabled';
                }    
                ?>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('clinic') ? 'is-invalid' : '' }}" name="clinic_id" id="clinic_id" {{$disabled}} required>
                            @foreach($clinics as $id => $clinic)
                                <option value="{{ $id }}" {{ (old('clinic_id') ? old('clinic_id') : $crmCustomer->clinic->id ?? '') == $id ? 'selected' : '' }}>{{ $clinic }}</option>
                            @endforeach
                        </select>
                        <label class="required" for="clinic_id">{{ trans('cruds.crmCustomer.fields.clinic') }}</label>
                        
                        @if($errors->has('clinic'))
                            <div class="invalid-feedback">
                                {{ $errors->first('clinic') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.clinic_helper') }}</span>
                    </div>
                    
                    <div class="form-group input-field col m6 s12">
                        <input class="form-control {{ $errors->has('badge') ? 'is-invalid' : '' }}" type="text" name="badge" id="badge" placeholder="Short note on card" value="{{ old('badge', $crmCustomer->badge) }}" >
                            @if($errors->has('badge'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('badge') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.crmCustomer.fields.badge_helper') }}</span>
                    </div>        
                    <!-- <div class="form-group input-field col m3 s12 right btn waves-effect waves-light gradient-45deg-purple-deep-orange switch-white">
                        <div class="switch form-check {{ $errors->has('convert_to_deal') ? 'is-invalid' : '' }}">
                            <label for="convert_to_deal">
                                <input type="checkbox" name="convert_to_deal" id="convert_to_deal" value="1" {{ $crmCustomer->convert_to_deal || old('convert_to_deal', 0) === 1 ? 'checked' : '' }} >
                                <span class="lever"></span>
                                {{ trans('cruds.crmCustomer.fields.convert_to_deal') }}
                            </label>
                        </div>
                        @if($errors->has('convert_to_deal'))
                            <div class="invalid-feedback">
                                {{ $errors->first('convert_to_deal') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.convert_to_deal_helper') }}</span>
                    </div> -->
                </div>

                <div class="row">
                    <div class="form-group input-field col m6 s12">
                            <label class="required" for="first_name">{{ trans('cruds.crmCustomer.fields.first_name') }}</label>
                            <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', $crmCustomer->first_name) }}" required>
                            @if($errors->has('first_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('first_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.crmCustomer.fields.first_name_helper') }}</span>
                    </div>    
                    <div class="form-group input-field col m6 s12">
                            <label for="last_name">{{ trans('cruds.crmCustomer.fields.last_name') }}</label>
                            <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $crmCustomer->last_name) }}" >
                            @if($errors->has('last_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('last_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.crmCustomer.fields.last_name_helper') }}</span>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('phone_form') ? 'is-invalid' : '' }}" name="phone_form" id="phone_form" {{$disabled}} >
                            <option value disabled {{ old('phone_form', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::PHONE_FORM_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('phone_form', $crmCustomer->phone_form) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="required">{{ trans('cruds.crmCustomer.fields.phone_form') }}</label>
                        
                        @if($errors->has('phone_form'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone_form') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.phone_form_helper') }}</span>
                    </div>

                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('source') ? 'is-invalid' : '' }}" name="source_id" id="source_id" {{$disabled}} >
                            @foreach($sources as $id => $source)
                                <option value="{{ $id }}" {{ (old('source_id') ? old('source_id') : $crmCustomer->source->id ?? '') == $id ? 'selected' : '' }}>{{ $source }}</option>
                            @endforeach
                        </select>
                        <label class="required" for="source_id">{{ trans('cruds.crmCustomer.fields.source') }}</label>
                        
                        @if($errors->has('source'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.source_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group  input-field col m6 s12">
                        <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status_id" id="status_id" >
                            @foreach($statuses as $id => $status)
                                <option value="{{ $id }}" {{ (old('status_id') ? old('status_id') : $crmCustomer->status->id ?? '') == $id ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <label class="required" for="status_id">{{ trans('cruds.crmCustomer.fields.status') }}</label>
                        
                        @if($errors->has('status'))
                            <div class="invalid-feedback">
                                {{ $errors->first('status') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.status_helper') }}</span>
                    </div>
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('three_plus_attempts') ? 'is-invalid' : '' }}" name="three_plus_attempts" id="three_plus_attempts" {{$disabled}}>
                            <option value disabled {{ old('three_plus_attempts', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::THREE_PLUS_ATTEMPTS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('three_plus_attempts', $crmCustomer->three_plus_attempts) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label>{{ trans('cruds.crmCustomer.fields.three_plus_attempts') }}</label>
                        
                        @if($errors->has('three_plus_attempts'))
                            <div class="invalid-feedback">
                                {{ $errors->first('three_plus_attempts') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.three_plus_attempts_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <label for="email">{{ trans('cruds.crmCustomer.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', $crmCustomer->email) }}" >
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.email_helper') }}</span>
                    </div>
                    
                    <div class="form-group input-field col m6 s12">
                        <label class="required" for="phone">{{ trans('cruds.crmCustomer.fields.phone') }}</label>
                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $crmCustomer->phone) }}" required>
                        @if($errors->has('phone'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.phone_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <label for="dob">{{ trans('cruds.crmCustomer.fields.dob') }}</label>
                        <input class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="text" name="dob" id="birthdate" value="{{ old('dob', $crmCustomer->dob) }}" >
                        @if($errors->has('dob'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dob') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.dob_helper') }}</span>
                    </div>
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('lead_type') ? 'is-invalid' : '' }}" name="lead_type" id="lead_type">
                            <option value disabled {{ old('lead_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::LEAD_TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('lead_type', $crmCustomer->lead_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label>{{ trans('cruds.crmCustomer.fields.lead_type') }}</label>
                        @if($errors->has('lead_type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('lead_type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.lead_type_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <label for="city">{{ trans('cruds.crmCustomer.fields.city') }}</label>
                        <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $crmCustomer->city) }}" >
                        @if($errors->has('city'))
                            <div class="invalid-feedback">
                                {{ $errors->first('city') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.city_helper') }}</span>
                    </div>
                    <div class="form-group input-field col m6 s12">
                        <label for="state">{{ trans('cruds.crmCustomer.fields.state') }}</label>
                        <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', $crmCustomer->state) }}" >
                        @if($errors->has('state'))
                            <div class="invalid-feedback">
                                {{ $errors->first('state') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.state_helper') }}</span>
                    </div>
                </div>
                
                <div class="row">
                    
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason">
                            <option value  {{ old('reason', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::REASON_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('reason', $crmCustomer->reason) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label>{{ trans('cruds.crmCustomer.fields.reason') }} ( {{ trans('cruds.crmCustomer.fields.reason_helper') }} )</label>
                        @if($errors->has('reason'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reason') }}
                            </div>
                        @endif
                        
                    </div>
                    
                    <div class="form-group input-field col m6 s12">
                        <label for="created_at">{{ trans('cruds.crmCustomer.fields.created_at') }}</label>
                        <?php 
                        if($timezone == "America/New_York"){
                         ?>  
                            <input class="form-control" type="text" value="{{ $crmCustomer->created_at->format('m/d/Y g:i A')  }} " readonly> 
                        <?php }else{ ?>
                            <input class="form-control" type="text" value="{{ $crmCustomer->created_at->timezone($timezone)->format('m/d/Y g:i A')  }} " readonly>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                    <label for="datetimepicker_mask">{{ trans('cruds.crmCustomer.fields.consultation_booked_date') }}</label>

                    <!-- <input class="datetime form-control {{ $errors->has('consultation_booked_date') ? 'is-invalid' : '' }}" type="text" name="consultation_booked_date" id="datetime" placeholder="" value="{{$crmCustomer->consultation_booked_date ? old('consultation_booked_date', (new \Carbon\Carbon($crmCustomer->consultation_booked_date))->format('m/d/Y g:i A')) : null }}" autocomplete="off"> -->

                    <input type="text" class="" value="{{$crmCustomer->consultation_booked_date ? old('consultation_booked_date', (new \Carbon\Carbon($crmCustomer->consultation_booked_date))->format('m/d/Y g:i A')) : null }}" id="datetimepicker_mask" name="consultation_booked_date"  autocomplete="off">

                    </div>
                    <div class="form-group input-field col m6 s12">
                        <label for="currency-demo" class="active">{{ trans('cruds.crmCustomer.fields.value') }}</label>
                        <div class="input-container">
                        <i class="material-icons dp48 icon">attach_money</i>
                        <input class="input-field {{ $errors->has('value') ? 'is-invalid' : '' }}" type="text" name="value" id="" value="{{ old('value', $crmCustomer->value) }}">
                        </div>
                        @if($errors->has('value'))
                            <div class="invalid-feedback">
                                {{ $errors->first('value') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.value_helper') }}</span>
                    </div>    
                </div>    
                <?php
                if($crmCustomer->convert_to_deal == 1){
                ?>
                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('deal_status') ? 'is-invalid' : '' }}" name="deal_status" id="deal_status">
                            <option value disabled {{ old('deal_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::DEAL_STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('deal_status', $crmCustomer->deal_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label>{{ trans('cruds.crmCustomer.fields.deal_status') }}</label>
                        @if($errors->has('deal_status'))
                            <div class="invalid-feedback">
                                {{ $errors->first('deal_status') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.won_lost_helper') }}</span>
                    </div>    
                    <div class="form-group input-field col m6 s12">
                        <select class="form-control {{ $errors->has('won_lost') ? 'is-invalid' : '' }}" name="won_lost" id="won_lost">
                            <option value disabled {{ old('won_lost', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CrmCustomer::WON_LOST_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('won_lost', $crmCustomer->won_lost) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label>{{ trans('cruds.crmCustomer.fields.won_lost') }}</label>
                        @if($errors->has('won_lost'))
                            <div class="invalid-feedback">
                                {{ $errors->first('won_lost') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.won_lost_helper') }}</span>
                    </div>
                </div>
                
                <?php
                }
                ?>

                <div class="row">
                    <div class="form-group input-field col m6 s12">
                        <input class="form-control {{ $errors->has('verbal_confirmation') ? 'is-invalid' : '' }}" type="text" name="verbal_confirmation" id="verbal_confirmation" value="{{ old('verbal_confirmation', $crmCustomer->verbal_confirmation) }}" maxlength="180" >
                        <label for="verbal_confirmation">{{ trans('cruds.crmCustomer.fields.verbal_confirmation') }}</label>
                        @if($errors->has('verbal_confirmation'))
                            <div class="invalid-feedback">
                                {{ $errors->first('verbal_confirmation') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.verbal_confirmation_helper') }}</span>
                    </div>    
                    <div class="form-group input-field col m6 s12">
                        <label for="informed_consult_fee">{{ trans('cruds.crmCustomer.fields.informed_consult_fee') }}</label>
                        <input class="form-control {{ $errors->has('informed_consult_fee') ? 'is-invalid' : '' }}" type="text" name="informed_consult_fee" id="informed_consult_fee" value="{{ old('informed_consult_fee', $crmCustomer->informed_consult_fee) }}" maxlength="180" >
                        @if($errors->has('informed_consult_fee'))
                            <div class="invalid-feedback">
                                {{ $errors->first('informed_consult_fee') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.informed_consult_fee_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group input-field col m12 s12">
                        <label for="description">{{ trans('cruds.crmCustomer.fields.description') }}</label>
                        <textarea class="budget mb-0 form-control input-field" name="description" id="description" rows="10" style="background-color: #FFF;min-height: 120px" id="description-editor" data-id="{{$crmCustomer->description}}" placeholder="">{{$crmCustomer->description}}</textarea>    
                    </div>
                </div> 

                <div class="row">
                    <div class="form-group input-field col m12 s12">
                        <label for="budget">{{ trans('cruds.crmCustomer.fields.budget') }}</label>
                        <textarea class="budget mb-0 form-control input-field" name="budget" id="budget" rows="10" style="background-color: #FFF;min-height: 100px" id="budget-editor" data-id="{{$crmCustomer->budget}}" placeholder="">{{$crmCustomer->budget}}</textarea>    
                    </div>
                </div> 

                <?php
                if($crmCustomer->won_lost == "Won"){
                ?>
                <div class="row">
                    
                    <div class="form-group input-field col m6 s12">
                        <label for="dob">{{ trans('cruds.crmCustomer.fields.won_lost_date') }}</label>
                        <input class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" type="text" name="won_lost_date" id="won_lost_date" value="{{ old('won_lost_date', Carbon\Carbon::parse($crmCustomer->won_lost_date)->format('m/d/Y g:i A')) }}" >
                        @if($errors->has('won_lost_date'))
                            <div class="invalid-feedback">
                                {{ $errors->first('won_lost_date') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.crmCustomer.fields.won_lost_date_helper') }}</span>
                    </div>


                </div>
                <?php } 
                ?>
                
                
                <div class="form-group center">
                    <div class="input-field text-right">
                        <?php 
                        if($crmCustomer->convert_to_deal != 1){
                        ?>
                        <a class="btn btn-danger savenote" onclick="convert_consult()"  id="convert_to_deal">Book Consultation</a>                       
                        <?php } ?>
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>

                <div class="row">
                    <?php 
                    if($crmCustomer->form_data != ''){
                        function jsonToTable ($data, $clinicid)
                        {
                            $table = '
                            <table class="json-table" width="100%">
                            ';
                            foreach ($data as $key => $value) {

                                if (strpos($key, 'state_') === false){
                                    $table .= '
                                    <tr valign="top">
                                    ';
                                    if ( ! is_numeric($key)) {
                                        if($key == "aoJ7SGkFEX3iQTQDW39L"){
                                            $key = "What Best Describes Your Condition?";
                                        }
                                        if($key == "umBqJAb1XVaKEjXw7o3U"){
                                            $key = "How Long Have You Been Missing Your Teeth?";
                                        }
                                        if($key == "B6RSCOTzefSQcCtmEfoX"){
                                            $key = "Do You Currently Have Any Of The Following Treatments?*";
                                        }
                                        if($key == "RXKU22Bw0u87QlyL7g2n"){
                                            $key = "Have You Experienced Any Type Of Insecurities Regarding The Way Your Teeth Look?";
                                        }
                                        if($key == "FVWrpdTqVZkQ3c1l9z7h"){
                                            $key = "Does Your Condition Have A Negative Impact On Your Ability To Eat or Chew Certain Foods?";
                                        }
                                        if($key == "dwnAJBzjTfnoGlv9oOMO"){
                                            $key = "What Is The Most Important Outcome You Are Seeking?";
                                        }
                                        if($key == "Ueib7xkVHurMSn6jPWw4"){
                                            $key = "What Is The Most Important Factor That Has Prevented You From Getting Treatment?";
                                        }
                                        if($key == "7KLtKMuZQpJmEHevAA8N"){
                                            $key = "What Is your Level of Urgency To Find Relief From Any Type Of Pain Or Discomfort That You May Be Feeling?";
                                        }
                                        if($key == "YZyNQW3EvaM0zo4pMnz8"){
                                            $key = "Have You Had Treatment Plans From Other Doctors For Dental Implants Recently?";
                                        }
                                        if($key == "lCI0qnypG4hJTHe4GzSJ"){
                                            $key = "Are You The Decision Maker In Regards To Your Dental & Healthcare?";
                                        }
                                        if($key == "gfewV5U6mBMYG65nJSZd"){
                                            $key = "Are You Interested in Learning About Our Easy Monthly Payment Plans? If So, What Dollar Range Would You Like To Pay Monthly";
                                        }
                                        if($key == "LcV33kmL6hCfTYhs7jnU"){
                                            $key = "Which Best Describes Your Current Credit Score?";
                                        }
                                        if($key == "sHwYj0ScWdb5qRCNEm3R"){
                                            $key = "Do You Require Any Transportation To & From Our Practice?";
                                        }
                                        if($key == "syWPxXDSp6c3vtxvVf6m"){
                                            $key = "Which Type of Consultation Do You Prefer?";
                                        }
                                        if($key == "a0PEW9LFbzzETq112eNf"){
                                            $key = "What Is Your Preferred Method Of Communication?";
                                        }

                                        if($clinicid != 67 || $clinicid != 107){

                                            if(stristr($key, "input_1") !== false ){
                                                $key = "How many teeth are you currently missing?";
                                            }
                                            if(stristr($key, "input_6") !== false){
                                                $key = "Are you currently wearing a denture?";
                                            }
                                            if(stristr($key, "input_28") !== false){
                                                $key = "What implant options interest you the most?";
                                            }
                                            if(stristr($key, "input_2") !== false){
                                                $key = "Are you in any pain or discomfort?";
                                            }
                                            if(stristr($key, "input_9") !== false){
                                                $key = "What has kept you from achieving the smile of your dreams?";
                                            }
                                            if(stristr($key, "input_31") !== false){
                                                $key = "Most Dental Implant procedures are not covered by insurance. However we offer many payment plans that make it quite affordable and offer low monthly rates. Are you interested in a payment plan?";
                                            }                                        
                                            if(stristr($key, "input_32") !== false){
                                                $key = "Payment plans are based on credit approval, you may also bring in a co-signer. If you could guess, what is your credit score?";
                                            } 
                                            if(stristr($key, "input_33") !== false){
                                                $key = "Based on your score, you may not qualify for financing. Do you have a friend, family member or spouse who will co-sign with you for financing?";
                                            }                                       
                                            if(stristr($key, "input_34") !== false){
                                                $key = "Unfortunately, it appears that you will not qualify for financing options for dental implants. Is there another way for you to pay for your perfect smile?";
                                            }                               
                                            if(stristr($key, "input_37") !== false){
                                                $key = "At this time it does not appear that you are a candidate for dental implant financing. However, our office could contact you to follow up. Would you like us to contact you to schedule a visit?";
                                            }                            
                                            if(stristr($key, "input_38") !== false){
                                                $key = "How soon would you like to get scheduled?";
                                            }
                                        
                                        }
                                        

                                        $table .= '
                                        <td width="300">
                                            <strong>'. $key .':</strong>
                                        </td>
                                        <td>
                                        ';
                                    } else {
                                        $table .= '
                                        <td colspan="2">
                                        ';
                                    }
                                    if (is_object($value) || is_array($value)) {
                                        $table .= jsonToTable($value,$clinicid);
                                    } else {
                                        $table .= $value;
                                    }
                                    $table .= '
                                        </td>
                                    </tr>
                                    ';
                                }
                            }
                            $table .= '
                            </table>
                            ';
                            return $table;
                        }

                        $form_data = str_replace("FormData:<b style='color:red'>Invalid Email Address received from Callrail</b>", "", $crmCustomer->form_data);
                        $form_data = substr($form_data , strpos($form_data , "{"));
                        $form_date = json_decode($form_data);
                        $clinicid  = $crmCustomer->clinic->id;
                        if (json_last_error() === JSON_ERROR_NONE) {
                            ?>
                            <ul class="collapsible" data-collapsible="accordion">
                                <li>
                                    <div class="collapsible-header gradient-45deg-light-blue-cyan white-text">
                                        <i class="material-icons">chrome_reader_mode</i>
                                        Form Details
                                    </div>
                                    <div class="collapsible-body"><?php echo jsonToTable($form_date, $clinicid ); ?></div>
                                </li>
                                  
                            </ul>
                        <?php    
                            
                        }    
                        
                    }    
                    ?>
                    
                    
                </div>
                    
                   
            </form>
            <div class="row">
                <?php 
                  if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" && ($crmCustomer->status->id == 12) ){
                    
                ?>
                    <form>
                        <input type="hidden" name="lead_id" id="sendemail_leadid" value="{{  $crmCustomer->id  }}">
                        
                        <div class="input-field text-right">
                            <div class="center"><a class="btn waves-effect waves-light gradient-45deg-indigo-purple gradient-shadow white-text sendemail" id="sendemail" >Send Notification Email <i class="material-icons right">email</i></a></div>
                        </div>
                        <input type="hidden" value="" id="sendemailid">
                    </form>
                    <div id="error-group" class="form-group" style="text-align: center;">
                        <div class="preloader-wrapper big active shownotificationloader" id="shownotificationloader">
                                <div class="spinner-layer spinner-blue-only">
                                  <div class="circle-clipper left">
                                    <div class="circle"></div>
                                  </div><div class="gap-patch">
                                    <div class="circle"></div>
                                  </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                  </div>
                                </div>
                        </div>
                    </div>

                <?php } ?>
            </div>
            </div> 

            <div class="col s6">
                <div class="panel panel-body border-top-teal">
                    <form>
                        <input type="hidden" name="lead_id" value="{{  $crmCustomer->id  }}">
                        <div class="form-group">
                            <h6>Take Notes</h6>
                                <div id="full-container">
                                    <div class="editor"></div>
                                </div>
                                <div class="compose-quill-toolbar">
                                    
                                </div>    
                        </div>
                        <div class="input-field text-right">
                            <div class="center"><a class="btn waves-effect waves-light savenote" id="savenote" >Save Note <i class="material-icons right">event_note</i></a></div>
                        </div>
                        <input type="hidden" value="" id="editnoteid">
                    </form>

                    <div class="collection streamline streamline-dotted m-t-lg list-feed">
                        <div class="notelisting">
                            <div></div>
                        </div>    
                        @foreach ($notes as $note)
                        <div class="collection-item card sl-item b-success notelisting" id="note-{{ $note->id }}">
                            <div class="sl-content card-content">
                               
                                {!! $note->note !!}
                                
                                

                                

                            </div>
                            <div class="card-action grey lighten-4">
                                <span class="pull-right sl-date">
                                <a href="#" onclick="editnote({{ $note->id }})" class="m-r-xs">
                                  <i class="material-icons">edit</i>
                                </a>
                                <a href="#" rel="tooltip" onclick="deletenote({{ $note->id }})" class="text-muted noteDelete" data-note-id="{{ $note->id }}" title="@langapp('delete')">
                                  <i class="material-icons">delete</i>
                                </a>
                                </span>
                            <small class="sl-footer text-muted small">

                                   <i class="material-icons vertical-align-bottom">access_time</i> {{ $note->created_at->timezone($timezone)->format('m/d/Y g:i A')  }} 
                                   <i class="material-icons">person</i> {{ $note->name }}
                            </small>
                            </div>
                        </div>
                        @endforeach
                       
                    </div>

                </div>
            </div>   

            
                

                
        </div> 
        
        
        
        <div id="sms"  class="card-panel col s12">

            <div class="chat-application">
              <div class="app-chat">
                <div class="content-area content-right">
                  <div class="app-wrapper">
                    <!-- Sidebar menu for small screen -->
                    <a href="#" data-target="chat-sidenav" class="sidenav-trigger hide-on-large-only">
                      <i class="material-icons">menu</i>
                    </a>
                    <!--/ Sidebar menu for small screen -->

                    <div class="card card card-default scrollspy border-radius-6 fixed-width">
                      <div class="card-content chat-content p-0">
                        <!-- Sidebar Area -->
                        <div class="sidebar-left sidebar-fixed animate fadeUp animation-fast">
                          <div class="sidebar animate fadeUp">
                            <div class="sidebar-content">
                              <div id="sidebar-list" class="sidebar-menu chat-sidebar list-group position-relative">
                                <div class="sidebar-list-padding app-sidebar sidenav" id="chat-sidenav">
                                  <!-- Sidebar Header -->
                                  <div class="sidebar-header">
                                    <div class="row valign-wrapper">
                                      <div class="col s2 media-image pr-0">
                                        <img src="{{asset('images/user/user.png')}}" alt="" class="circle z-depth-2 responsive-img">
                                      </div>
                                      <div class="col s10">
                                        <p class="m-0 blue-grey-text text-darken-4 font-weight-700">{{$crmCustomer->first_name}} {{$crmCustomer->last_name}} </p>
                                        <p class="m-0 info-text">{{$crmCustomer->phone}} </p>
                                      </div>
                                    </div>
                                    
                                  </div>
                                  <!--/ Sidebar Header -->
                                  <!-- Sidebar Search -->
                                  <div class=" animate fadeUp">
                                    <div class="chat-footer">
                                        <form action="javascript:void(0);" class="chat-input">
                                          @csrf
                                            <input type="hidden" id="lead_id" name="lead_id" value="{{ $crmCustomer->id }}">
                                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" id="lead_clinic_id" name="clinic_id" value="{{ $crmCustomer->clinic->id }}">
                                            <input type="hidden" id="is_sms" name="is_sms" value="1">
                                        
                                            
                                            <?php
                                                $user = auth()->user()->roles;
                                                $userrole = $user[0]['title'];
                                                $name = '';
                                                if($crmCustomer->clinic->leadcenteraccountmanager != ""){
                                                    $name = $crmCustomer->clinic->leadcenteraccountmanager;
                                                }else{
                                                    $name = auth()->user()->name; 
                                                }
                                                if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes"){
                                                $prior_text = "Hi, this is ".$name.", a Dental Implant Concierge from ".$crmCustomer->clinic->dr_name."’s office. I received your request for more information about dental implants. I will be calling you in the next few minutes to answer your questions, discuss possible solutions, or schedule an in-office consultation. I wanted to text message you first so you are aware of who is calling. My number is ".$crmCustomer->clinic->hotline_phone_number.". I look forward to speaking with you. \n\nTo continue to receive texts to answer your questions or schedule your appointment, we need your agreement.  By replying YES to this text, you agree to receive automated promotional messages. This agreement isn't a condition of any purchases. Terms and Privacy Policy can be found at https://microsite.com/TCPAterms.  You may receive up to 4 msgs/month. Please reply YES to Accept, STOP to end or HELP for help.";

                                                ?>
                                                <ul style="text-align:center;">
                                                <li class="btn-small btn-light-pink btn-outline button-exclusive" data-default="<?php echo $prior_text;?>"  id="prior-chat">
                                                           <span class="buttonIconTeam">Prior to Calling</span>
                                                </li>
                                                </ul>
                                            <?php }
                                                if($crmCustomer->clinic->priorsmstext != '' && $crmCustomer->clinic->lead_center != "Yes"){
                                                $prior_text = $crmCustomer->clinic->priorsmstext;

                                                ?>
                                                <ul style="text-align:center;">
                                                <li class="btn-small btn-light-pink btn-outline button-exclusive" data-default="<?php echo $prior_text;?>"  id="prior-chat">
                                                           <span class="buttonIconTeam">Prior to Calling</span>
                                                </li>
                                                </ul>


                                            <?php }
                                             ?>    
                                            <textarea class="message mb-0 form-control" name="chat" id="chat" rows="20" style="background-color: #eceff1;height: 200px" id="comment-editor" data-id="{{$crmCustomer->id}}" required placeholder="Type your message"></textarea>
                                            <div class="center"><a class="btn waves-effect waves-light send" id="sendsms" >Send<i class="material-icons right">send</i></a></div>
                                        </form>
                                      </div>
                                  </div>
                                  <!--/ Sidebar Search -->
                                  

                                  
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--/ Sidebar Area -->

                        <!-- Content Area -->
                        <div class="chat-content-area animate fadeUp">
                          

                          <!-- Chat content area -->
                          <div class="chat-area">
                            <div class="chats">
                              <div class="chats">
                                <div class="chat">
                                    <div class="chat-avatar">
                                    </div>    
                                </div>    
                                @foreach($chats as $message)

                                @if($message->inbound)
                                
                                <div class="chat">
                                  <div class="chat-avatar">
                                    <a class="avatar">
                                      <img src="{{asset('images/user/user.png')}}" class="circle" alt="avatar" />
                                    </a>
                                  </div>
                                  <div class="chat-body">
                                    
                                    <div class="chat-text">
                                      <p>{{$message->chat}}</p>
                                    </div>
                                    @if(!$message->read)
                                      <span class="badge badge pill red">New</span>
                                    @endif
                                    {{ $message->created_at->timezone($timezone)->format('m/d/Y g:i A') }}
                                    
                                  </div>
                                </div>
                                @else
                                <div class="chat chat-right">
                                  <div class="chat-avatar">
                                    <a class="avatar">
                                      <img src="{{asset('images/user/microsite-logo.png')}}" class="circle" alt="avatar" />
                                    </a>
                                  </div>
                                  <div class="chat-body">
                                    
                                    <div class="chat-text">
                                      <p>{{$message->chat}}</p>
                                    </div>
                                    <span class="m-l-lg">
                                        @if($message->delivered)
                                            <i class="fas fa-check-double" data-rel="tooltip" title="Delivered"></i>
                                        @else
                                            <i class="fas fa-exclamation-circle" data-rel="tooltip" title="Not Delivered"></i>
                                        @endif

                                        @if($message->user_id==1 && $message->inbound==0)
                                        {{ $message->created_at->format('m/d/Y g:i A') }}
                                        @else
                                        {{ $message->created_at->timezone($timezone)->format('m/d/Y g:i A') }}
                                        @endif
                                    </span>
                                    
                                    
                                  </div>
                                </div>
                                @endif

                                {{ $message->markRead() }}

                                @endforeach

                              </div>
                            </div>
                          </div>
                          <!--/ Chat content area -->

                          
                        </div>
                        <!--/ Content Area -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>  


        <?php 
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
        ?>
            <div id="creditcard"  class="card-panel col s12">
                <form action="" method="POST" class="credit-card">
                      <div class="form-header">
                        <h4 class="title">Credit card detail</h4>
                      </div>

                      <div class="form-body">
                        <!-- Card Number -->
                        <input type="text" class="card-number cc-number-input" maxlength="19" placeholder="Card Number" id="cardnumber">

                        <!-- Card Name -->
                        <input type="text" class="card-name" placeholder="Name on the Card" id="nameoncard">
                     
                        <!-- Date Field -->
                        <div class="date-field">
                          <div class="month" style="width:50%;float: left;">
                            <select name="Month" id="expdate_month">
                              <option value="january">January</option>
                              <option value="february">February</option>
                              <option value="march">March</option>
                              <option value="april">April</option>
                              <option value="may">May</option>
                              <option value="june">June</option>
                              <option value="july">July</option>
                              <option value="august">August</option>
                              <option value="september">September</option>
                              <option value="october">October</option>
                              <option value="november">November</option>
                              <option value="december">December</option>
                            </select>
                          </div>
                          <div class="year" style="width:50%;float:right;">
                            <select name="Year" id="expdate_year">
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                              <option value="2026">2026</option>
                              <option value="2027">2027</option>
                              <option value="2028">2028</option>
                              <option value="2029">2029</option>
                              <option value="2030">2030</option>
                              
                            </select>
                          </div>
                        </div>
                     
                        <!-- Card Verification Field -->
                        <div class="card-verification">
                          <div class="cvv-input">
                            <input type="text" maxlength="4" placeholder="CVV" id="cardcvv">
                          </div>
                          <div class="cvv-details">
                            <p>3 or 4 digits usually found on the signature strip</p>
                          </div>
                        </div>

                        <div class="card-zip">
                          <div class="zip-input">
                            <input type="number" maxlength="5" placeholder="Zip Code" id="zipcode">
                          </div>
                        </div>

                        
                     
                        <!-- Buttons -->
                        <button type="button" class="btn proceed-btn" id="creditcardsave">Save</button>
                        <div id="creditcard_error-group" style="text-align: center;">
                            <div class="preloader-wrapper big active showloader" id="showloader">
                                <div class="spinner-layer spinner-blue-only">
                                  <div class="circle-clipper left">
                                    <div class="circle"></div>
                                  </div><div class="gap-patch">
                                    <div class="circle"></div>
                                  </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                  </div>
                                </div>
                            </div>
                        </div> 
                      </div>
                      <input type="hidden" name="lead_id" id="sendcreditcardemail_leadid" value="{{  $crmCustomer->id  }}">
                      <input type="hidden" name="cr_id" id="sendcreditcardemail_cr_id" value="{{  $crmCustomer->clinic->callrail_company  }}">
                        
                    </form>
                       
            </div>    

        <?php } ?>

        <?php 
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
        ?>
            <div id="dihinformaiton"  class="card-panel col s12">
                <div class="form-header">
                    <h4 class="title">{{ trans('cruds.clinic.fields.dih_account_details') }}</h4>
                </div>
                <div class="form-group card-panel">
                    <span class="form-control">{!!$crmCustomer->clinic->dih_account_details!!}</span>
                </div>
            </div>    

        <?php } ?>


    </div>
  </div>
</section>    



@endsection

@section('scripts')


<script>
<?php 
            if(($userrole == 'Lead Center Associate' || $userrole == 'Admin' || $userrole == "Super Admin" || $userrole == 'Manager') && $crmCustomer->clinic->lead_center == "Yes" ){
        ?>
    let ccNumberInput = document.querySelector('.cc-number-input'),
        ccNumberPattern = /^\d{0,16}$/g,
        ccNumberSeparator = " ",
        ccNumberInputOldValue,
        ccNumberInputOldCursor,
        
        mask = (value, limit, separator) => {
            var output = [];
            for (let i = 0; i < value.length; i++) {
                if ( i !== 0 && i % limit === 0) {
                    output.push(separator);
                }
                
                output.push(value[i]);
            }
            
            return output.join("");
        },
        unmask = (value) => value.replace(/[^\d]/g, ''),
        checkSeparator = (position, interval) => Math.floor(position / (interval + 1)),
        ccNumberInputKeyDownHandler = (e) => {
            let el = e.target;
            ccNumberInputOldValue = el.value;
            ccNumberInputOldCursor = el.selectionEnd;
        },
        ccNumberInputInputHandler = (e) => {
            let el = e.target,
                    newValue = unmask(el.value),
                    newCursorPosition;
            
            if ( newValue.match(ccNumberPattern) ) {
                newValue = mask(newValue, 4, ccNumberSeparator);
                
                newCursorPosition = 
                    ccNumberInputOldCursor - checkSeparator(ccNumberInputOldCursor, 4) + 
                    checkSeparator(ccNumberInputOldCursor + (newValue.length - ccNumberInputOldValue.length), 4) + 
                    (unmask(newValue).length - unmask(ccNumberInputOldValue).length);
                
                el.value = (newValue !== "") ? newValue : "";
            } else {
                el.value = ccNumberInputOldValue;
                newCursorPosition = ccNumberInputOldCursor;
            }
            
            el.setSelectionRange(newCursorPosition, newCursorPosition);
            
            highlightCC(el.value);
        },
        highlightCC = (ccValue) => {
            let ccCardType = '',
                    ccCardTypePatterns = {
                        amex: /^3/,
                        visa: /^4/,
                        mastercard: /^5/,
                        disc: /^6/,
                        
                        genric: /(^1|^2|^7|^8|^9|^0)/,
                    };
            
            for (const cardType in ccCardTypePatterns) {
                if ( ccCardTypePatterns[cardType].test(ccValue) ) {
                    ccCardType = cardType;
                    break;
                }
            }
            
            let activeCC = document.querySelector('.cc-types__img--active'),
                    newActiveCC = document.querySelector(`.cc-types__img--${ccCardType}`);
            
            if (activeCC) activeCC.classList.remove('cc-types__img--active');
            if (newActiveCC) newActiveCC.classList.add('cc-types__img--active');
        };

ccNumberInput.addEventListener('keydown', ccNumberInputKeyDownHandler);
ccNumberInput.addEventListener('input', ccNumberInputInputHandler);
<?php } ?>



$(document).on('click', '#prior-chat', function(){
    $("#chat").val($(this).data('default'));
    setFunctionality();
    });

function setFunctionality()
{
    $("#chat").focus(function() {
    if (this.value === this.defaultValue) {
        this.value = '';
    }
  })
  .blur(function() {
    if (this.value === '') {
        this.value = this.defaultValue;
    }
});
}

$('#has_sms').change(function(){
    var mode= $(this).prop('checked');
    var leadid = "<?php echo $crmCustomer->id;?>";
    $.ajax({
      type:'POST',
      url:'{{ route("admin.crm-customers.hassms")}}',
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: {
                'has_sms': mode,
                'leadid': leadid,
            },
      cache: false,
      success:function(data)
      {
        $('#has_sms_switch').hide();
      }
    });

  });

jQuery(function ($) {        
  $('form').bind('submit', function () {
    $(this).find(':input').prop('disabled', false);
  });
});

function convert_consult(){
    var leadid = "<?php echo $crmCustomer->id;?>";
    if($('#datetimepicker_mask').val() == ''){
        //alert('Please add Consultation Booked Date');
        swal({
            title: 'Please add Consultation Booked Date',
            icon: 'warning'
        })
        return false;
    }

    swal({
            title: "Are you sure?",
            text: "Do you want to move this lead to consult?",
            icon: 'warning',
            buttons: {
                cancel: true,
                delete: 'Yes'
            }
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({
            type: 'POST',
            url: '{{ route("admin.crm-customers.moveStage",'')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                    'convert_to_deal': 1,
                    'leadid': "_"+leadid,
                    'status_id' : 12,
                    'consultation_booked_date' : $('#datetimepicker_mask').val(),
                    'verbal_confirmation' : $('#verbal_confirmation').val(),
                    'informed_consult_fee' : $('#informed_consult_fee').val(),
                    'budget' : $('#budget').val(),
                    'description': $('#description').val(),
                },
            cache: false,
            success: function (result) {
                console.log("It's done.");
                location.reload();
                },
            error: function() {
                console.log("Uh oh! There's an error!");
            }
            });
        } 
        });    
           
}


function markasspam(){
    var leadid = "<?php echo $crmCustomer->id;?>";
    swal({
            title: "Are you sure?",
            text: "Do you want to mark this lead as spam? Please don’t select this option unless you are sure, as this action cannot be undone.",
            icon: 'warning',
            buttons: {
                cancel: true,
                delete: 'Yes'
            }
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({
            type: 'POST',
            url: '{{ route("admin.crm-customers.markasspam",'')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                    'leadid': leadid,
                },
            cache: false,
            success: function (result) {
                console.log("It's done.");
                window.location.href = "{{ route('admin.crm-customers.index')}}";
                },
            error: function() {
                console.log("Uh oh! There's an error!");
            }
            });
        } 
        });    
           
}

function deletenote(noteid){
    var noteid = noteid;
    
    swal({
            title: "Are you sure?",
            text: "Do you want to delete this note?",
            icon: 'warning',
            buttons: {
                cancel: true,
                delete: 'Yes'
            }
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                type: 'DELETE',
                url: '{{ route("admin.crm-notes.destroy",'')}}/'+noteid,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                cache: false,
                success: function (result) {
                    console.log("It's done.");
                    $("#note-"+noteid).remove();
                },
                error: function() {
                    console.log("Uh oh! There's an error!");
                }
                });
            } 
    });            
    
}

function editnote(noteid){
    var noteid = noteid;
    //alert(noteid);
    $.ajax({
        type: 'GET',
        url: '/admin/crm-notes/'+noteid+'/edit',
        cache: false,
        success: function (result) {
            console.log("It's done.");
            console.log(result);
            $(".ql-editor").html(result['note']);
            $("#editnoteid").val(result['id']);
        },
        error: function() {
            console.log("Uh oh! There's an error!");
        }
    });
    
}


$(document).ready(function () {
        M.AutoInit();
        //var DateField = MaterialDateTimePicker.create($('#datetime'));

        $('#birthdate').datepicker({
            format:"mm/dd/yyyy",
            yearRange: [1900,new Date().getFullYear()],
            changeYear: true,
        });


        $('#won_lost_date').datetimepicker({
            format: 'm/d/Y h:i A',
            hours12:false,
            ampm: true, // FOR AM/PM FORMAT
            formatTime: 'A g:i',
            allowTimes:[
              'AM 7:00',  
              'AM 8:00',
              'AM 9:00',
              'AM 10:00',
              'AM 11:00',
              'PM 12:00',
              'PM 13:00',
              'PM 14:00',
              'PM 15:00',
              'PM 16:00',
              'PM 17:00',
              'PM 18:00',
              'PM 19:00',
              'PM 20:00'
            ]
        });


        //var datepicker = $.fn.datepicker.noConflict(); // return $.fn.datepicker to previously assigned value
        //$.fn.bootstrapDP = datepicker;

        $('.tooltipped').tooltip();
        
        $('tabs').tabs();

        var quill = new Quill('.editor','.edit-editor', {
            modules: {
                toolbar: ".compose-quill-toolbar"
            },
            placeholder: "Write a Note... ",
            theme: 'snow'
        });

        $(".chat-area").scrollTop($(".chat-area > .chats").height());

        $("#sendemail").click(function(e){
            var leadid = $("#sendemail_leadid").val();
            var informed_consult_fee = "<?php echo $crmCustomer->informed_consult_fee;?>";
            var verbal_confirmation = "<?php echo $crmCustomer->verbal_confirmation;?>";

            var first_name = "<?php echo $crmCustomer->first_name;?>";
            var last_name = "<?php echo $crmCustomer->last_name;?>";
            var phone = "<?php echo $crmCustomer->phone;?>";
            var dob = "<?php echo $crmCustomer->dob;?>";
            var lead_type = "<?php echo $crmCustomer->lead_type;?>";

            if(informed_consult_fee == ''){
               swal({
                    title: 'Please add a text for Informed of Consult Fee and Save',
                    icon: 'warning'
                })
                return false;
            }
            if(verbal_confirmation == ""){
               swal({
                    title: 'Please add a text for Verbal Confirmation and Save',
                    icon: 'warning'
                })
                return false;
            }

            if(first_name == ""){
               swal({
                    title: 'Please add First Name and Save',
                    icon: 'warning'
                })
                return false;
            }
            if(last_name == ""){
               swal({
                    title: 'Please add Last Name and Save',
                    icon: 'warning'
                })
                return false;
            }
            if(phone == ""){
               swal({
                    title: 'Please add Phone number and Save',
                    icon: 'warning'
                })
                return false;
            }
            if(dob == ""){
               swal({
                    title: 'Please add Date of Birth and Save',
                    icon: 'warning'
                })
                return false;
            }
            if(lead_type == ""){
               swal({
                    title: 'Please select lead type and Save',
                    icon: 'warning'
                })
                return false;
            }


            swal({
                title: "Are you sure?",
                text: "Do you want to send notification email?",
                icon: 'warning',
                buttons: {
                    cancel: true,
                    delete: 'Yes'
                }
            })
            .then((willDelete) => {
                if (willDelete) {
                $("#shownotificationloader").removeClass('shownotificationloader'); 
                $.ajax({
                type: 'POST',
                url: '{{ route("admin.crm-customers.sendemail",'')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                        
                        'leadid': leadid,
                        
                    },
                cache: false,
                success: function (result) {
                    console.log("It's done.");
                    $("#error-group").html(
                        '<div class="card-alert card green lighten-5">' +
                            '<div class="card-content green-text">'+
                            '<p>SUCCESS : '+result.message+'</p>'+
                            '</div>'+                
                        '</div>'
                        
                    );
                    $("#shownotificationloader").addClass('shownotificationloader');
                    //location.reload();
                    },
                error: function() {
                    alert("Uh oh! There's an error!");
                    $("#error-group").html(
                        '<div class="card-alert card red lighten-5">' +
                            '<div class="card-content red-text">'+
                            '<p>Error : Uh oh! There is an error!</p>'+
                            '</div>'+                
                        '</div>'
                        
                    );
                    $("#shownotificationloader").addClass('shownotificationloader');
                }
                });
            } 
            });
        });    


        $("#creditcardsave").click(function(e){
            var leadid      = $("#sendcreditcardemail_leadid").val();
            var cr_id       = $("#sendcreditcardemail_cr_id").val();
            var cardnumber  = $("#cardnumber").val();
            var nameoncard  = $("#nameoncard").val();
            var expdate     = $("#expdate_month").val() +'/'+ $("#expdate_year").val();
            var cvv         = $("#cardcvv").val();
            var zipcode     = $("#zipcode").val();
            var fname        = $("#first_name").val();
            var lname        = $("#last_name").val();
            var clinicname   = '<?php echo $crmCustomer->clinic->clinic_name; ?>';

            swal({
                title: "Are you sure?",
                text: "Do you want to send this email?",
                icon: 'warning',
                buttons: {
                    cancel: true,
                    delete: 'Yes'
                }
            })
            .then((willDelete) => {
                if (willDelete) {
                $("#creditcardsave").addClass('hidesavebutton');
                $("#showloader").removeClass('showloader');    
                $.ajax({
                type: 'POST',
                url: '{{ route("admin.crm-customers.sendcreditcardpdf",'')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                        
                        'leadid': leadid,
                        'cr_id': cr_id,
                        'card_number': cardnumber,
                        'name_on_card': nameoncard,
                        'exp_date' : expdate,
                        'cvv' : cvv,
                        'zipcode' : zipcode,
                        'name' : fname+ +lname,
                        'practicename' : clinicname,
                        
                    },
                cache: false,
                success: function (result) {
                    console.log("It's done.");
                    $("#creditcard_error-group").html(
                        '<div class="card-alert card green lighten-5">' +
                            '<div class="card-content green-text">'+
                            '<p>SUCCESS : '+result.message+'</p>'+
                            '</div>'+                
                        '</div>'
                        
                    );
                    $("#creditcardsave").removeClass('hidesavebutton');
                    $("#showloader").addClass('showloader');
                    //location.reload();
                    },
                error: function() {
                    alert("Uh oh! There's an error!");
                    $("#creditcard_error-group").html(
                        '<div class="card-alert card red lighten-5">' +
                            '<div class="card-content red-text">'+
                            '<p>Error : Uh oh! There is an error!</p>'+
                            '</div>'+                
                        '</div>'
                        
                    );
                    $("#creditcardsave").removeClass('hidesavebutton');
                    $("#showloader").addClass('showloader');
                }
                });
            } 
            });
        });

        $("#savenote").click(function(e){
            var note = $(".ql-editor").html();
            var noteid = $("#editnoteid").val();
            e.preventDefault();

            if(note == '<p><br></p>'){
               swal({
                    title: 'Please add a note',
                    icon: 'warning'
                })
                return false;
            }
            

            $("#savenote").addClass('hidesavebutton');
            if(noteid > 0){
                $.ajax({
                    type: 'PUT',
                    url: '{{ route("admin.crm-notes.update", '') }}/'+noteid,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'customer_id':'{{ $crmCustomer->id }}',
                        'note': note,
                    },
                    cache: false,
                    success: function (result) {
                        console.log("It's done.");
                        console.log(result);
                        location.reload();
                    },
                    error: function() {
                        console.log("Uh oh! There's an error!");
                        $("#savenote").removeClass('hidesavebutton');
                    }
                });
            }else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.crm-notes.store") }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'customer_id':'{{ $crmCustomer->id }}',
                        'note': note,
                    },
                    cache: false,
                    success: function (result) {
                        console.log("It's done.");
                        var html = '<div class="collection-item card sl-item b-success notelisting" id="note-'+result["id"]+'"><div class="sl-content card-content"><p>'+result["note"]+'</p></div><div class="card-action grey lighten-4"><span class="pull-right sl-date"><a href="#" onclick="editnote('+result["id"]+')" class="m-r-xs"><i class="material-icons">edit</i></a><a href="#" onclick="deletenote('+result["id"]+')" rel="tooltip" class="text-muted noteDelete" data-note-id="'+result["id"]+'" ><i class="material-icons">delete</i></a></span><small class="sl-footer text-muted small"><i class="material-icons vertical-align-bottom">access_time</i>Now <i class="material-icons">person</i> {{ Auth::user()->name }}</small></div></div>';
                        $(".notelisting:first-child").parent().prepend(html);
                        $(".ql-editor").empty();
                        $("#savenote").removeClass('hidesavebutton');
                    },
                    error: function() {
                        console.log("Uh oh! There's an error!");
                        $("#savenote").removeClass('hidesavebutton');
                    }
                });
            }
            
            
            
          });

        

        $("#sendsms").click(function(e){
            var message = $(".message").val();
            if (message != "") {
                  var html = '<div class="chat chat-right"><div class="chat-avatar"><a class="avatar"><img src="/images/user/microsite-logo.png" class="circle" alt="avatar" /></a></div><div class="chat-body"><div class="chat-text">' + "<p>" + message + "</p>" + "</div><span class='m-l-lg'><i class='material-icons'>today</i> Now</span></div></div>";
                  $(".chat:first-child").parent().prepend(html);
                  $(".message").val("");
            }
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.crm-chats.store") }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'user_id': $('#user_id').val(),
                    'clinic_id': $('#lead_clinic_id').val(),
                    'lead_id': $('#lead_id').val(),
                    'chat': message,
                    'is_sms': $('#is_sms').val(),
                },
                cache: false,
                success: function (result) {
                    console.log("It's done.");
                },
                error: function() {
                    console.log("Uh oh! There's an error!");
                }
            });
            
          });


        
        $("#formValidate").validate({
          
          submitHandler: function(form)
          {
             
            if($('select[name="status_id"]').val() == 9){
                if($('select[name="reason"]').val() == null || $('select[name="reason"]').val() == ''){
                    $('select[name="reason"]').trigger('change');
                    $('select[name="reason"]').attr("required","required");
                    $('select[name="reason"]').siblings('input').first().css( "border-bottom", "2px solid red" );
                    $('select[name="reason"]').siblings('input').first().css( "box-shadow", "none" );
                    swal({
                        title: 'Please select Lost Reason (Why patient did not schedule)',
                        icon: 'warning'
                    })
                    return false;
                }
            }
            return true;
          }  
        });

    });
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
                xhr.open('POST', '{{ route('admin.crm-customers.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $crmCustomer->id ?? 0 }}');
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

{{-- page scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-account-settings.js')}}"></script>
<script src="{{asset('js/scripts/app-chat.js')}}"></script>
<script src="{{asset('vendors/quill/katex.min.js')}}"></script>
<script src="{{asset('vendors/quill/highlight.min.js')}}"></script>
<script src="{{asset('vendors/quill/quill.min.js')}}"></script>
<script src="{{asset('js/scripts/form-editor.js')}}"></script>
<script src="{{asset('js/scripts/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('js/scripts/form-validation.js')}}"></script>
<script src="{{asset('js/scripts/extra-components-sweetalert.js')}}"></script>
<script src="{{asset('js/jquery.datetimepicker.js')}}"></script>

<script type="text/javascript">

var priorDate = moment().subtract(30, "days").format('YYYY/MM/DD');
$('#datetimepicker_mask').datetimepicker({
    format: 'm/d/Y h:i A',
    hours12:false,
    ampm: true, // FOR AM/PM FORMAT
    formatTime: 'A g:i',
    minDate: priorDate,
    allowTimes:[
      'AM 7:00',  
      'AM 8:00',
      'AM 9:00',
      'AM 10:00',
      'AM 11:00',
      'PM 12:00',
      'PM 13:00',
      'PM 14:00',
      'PM 15:00',
      'PM 16:00',
      'PM 17:00',
      'PM 18:00',
      'PM 19:00',
      'PM 20:00'
    ]
});
</script>
@endsection

