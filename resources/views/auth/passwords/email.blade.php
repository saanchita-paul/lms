{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','User Forgot Password')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/forgot.css')}}">
@endsection
@php
// confiData variable layoutClasses array in Helper.php file.
$configData = Helper::applClasses();
@endphp
{{-- page content --}}
@section('content')
<div id="forgot-password" class="row">
    
    <div class="col s12 m6 l4 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card">
        {{-- success status --}}
        @if (session('status'))
        <div class="card-alert card green lighten-5">
          <div class="card-content green-text">
            <p>{{ session('status') }}</p>
          </div>
          <button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        @endif
        <div class="input-field col s12 center-align">
        <img class="hide-on-med-and-down center-align" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" />
        <img class="show-on-medium-and-down hide-on-med-and-up col s12 center-align" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />
    </div>
        <form class="login-form" method="POST" action="{{ route('password.email') }}">
          @csrf

          <div class="row hide">
            <div class="input-field col s12">
              <h5 class="ml-4">Forgot Password</h5>
              <p class="ml-4 hide">You can reset your password</p>
              <p class="ml-4"> Please contact your account manager </p>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <i class="material-icons prefix pt-2">person_outline</i>
              <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                value="{{ old('email') }}" autocomplete="email" autofocus>
              <label for="email" class="center-align">Email</label>
              @if($errors->has('email'))
                <small class="red-text ml-7" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </</small>>
              @endif
              
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <button type="submit"
                class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 mb-1">Reset
                Password</button>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s6 m6 l6">
              <p class="margin medium-small"><a href="{{ route('login')}}">Login</a></p>
            </div>
            
          </div>
        </form>
      </div>
    </div>
@endsection


{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/ui-alerts.js')}}"></script>
@endsection
