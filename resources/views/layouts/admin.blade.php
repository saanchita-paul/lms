{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset


@php
// confiData variable layoutClasses array in Helper.php file.
$configData = Helper::applClasses();
@endphp
<!DOCTYPE html>
<html class="loading"
  lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
  data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="apple-touch-icon" href="/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon/favicon-32x32.png">

    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="{{ asset('css/external/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/buttons.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/select.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ asset('css/external/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/external/perfect-scrollbar.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/materialize-stepper.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" >
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    {{-- Include core + vendor Styles --}}
    @include('panels.styles')
    @yield('styles')

  
</head>

{{-- @isset(config('custom.custom.mainLayoutType'))
@endisset --}}
@if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
@include(($configData['mainLayoutType'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':
'layouts.verticalLayoutMaster')
@else
{{-- if mainLaoutType is empty or not set then its print below line  --}}
<h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
@endif

</html>