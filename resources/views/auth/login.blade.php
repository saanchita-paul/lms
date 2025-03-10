{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','User Login')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/login.css')}}">
@endsection

{{-- page content --}}
@section('content')

@php
// confiData variable layoutClasses array in Helper.php file.
$configData = Helper::applClasses();
@endphp

<div id="login-page" class="row justify-content-center">
    <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card">

        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row">

                <div class="input-field col s12 center-align">
                    <img class="hide-on-med-and-down center-align" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" />
                    <img class="show-on-medium-and-down hide-on-med-and-up col s12 center-align" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />
                </div>

                @if(session('message'))
                 <div class="input-field col s12">
                    <div class="alert alert-info" role="alert">
                       tetetss {{ session('message') }}
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="input-field col s12">
                        <div id="api-error" class="red-text" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <div class="row margin">
                <div class="input-field col s12">
                  <i class="material-icons prefix pt-2">person_outline</i>
                  <input id="email" type="email" class=" {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                    value="{{ old('email', null) }}" required autocomplete="email" autofocus>
                  <label for="email" class="center-align">{{ __('Email') }}</label>
                  @if($errors->has('email'))
                  <small class="red-text ml-7" >
                    {{ $errors->first('email') }}
                  </small>
                  @endif
                </div>
            </div>

            <div class="row margin">
                <div class="input-field col s12">
                  <i class="material-icons prefix pt-2">lock_outline</i>
                  <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                    name="password" required autocomplete="current-password">
                  <label for="password">{{ __('Password') }}</label>
                  @if($errors->has('password'))
                  <small class="red-text ml-7" >
                    {{ $errors->first('password') }}
                  </small>
                  @endif
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l12 ml-2 mt-1">
                  <p>
                    <label>
                      <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                      <span>{{ trans('global.remember_me') }}</span>
                    </label>
                  </p>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <button type="button" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12"
                            id="submit-btn"
                            onclick="return submit_data();">
                        {{ trans('global.login') }}
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6 m6 l6">
                    @if(Route::has('password.request'))
                        <p class="margin medium-small">
                        <a href="{{ route('password.request') }}">
                            {{ trans('global.forgot_password') }}
                        </a><br>
                        </p>
                    @endif

                </div>
            </div>
        </form>

    </div>
</div>
@endsection
<script type="text/javascript">
    function submit_data() {
        // Disable button
        $('#submit-btn').attr('disabled', true).css("pointer-events", "none", "cursor", "default");

        // Hide any previous errors
        $('#api-error').hide();

        $.ajax({
            url: $('.login-form').attr('action'),
            type: 'POST',
            data: $('.login-form').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                // Redirect on success
                window.location.href = response.redirect || '/dashboard';
            },
            error: function(xhr) {
                // Re-enable button
                $('#submit-btn').attr('disabled', false).css("pointer-events", "auto", "cursor", "pointer");

                // Show error message
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    $('#api-error').html(xhr.responseJSON.message).show();
                } else {
                    $('#api-error').html('An error occurred. Please try again.').show();
                }
            }
        });

        // Prevent form from submitting normally
        return false;
    }
</script>
