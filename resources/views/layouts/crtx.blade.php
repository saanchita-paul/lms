<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-BDF7FDJJY7"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-BDF7FDJJY7'); </script>
	<title>@yield('title')</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{asset('crtxassets/images/favicon.ico')}}">
	<link rel="stylesheet" href="{{asset('crtxassets/css/vendor/bootstrap.min.css')}}">
	{{-- <link rel="stylesheet" href="{{asset('crtxassets/css/vendor/bootstrap.min.css.map')}}"> --}}
	<link rel="stylesheet" href="{{asset('crtxassets/css/vendor/jquery-ui.css')}}">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="{{asset('crtxassets/css/vendor/select2.min.css')}}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="{{asset('crtxassets/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('crtxassets/css/custom.css')}}">
	<link rel="stylesheet" href="{{asset('crtxassets/css/responsive.css')}}">
</head>
<body>
<div id="wrapperContainer">
    @yield('content')
</div><!--/#wrapper-->
<script src="{{asset('crtxassets/js/jquery.js')}}"></script>
<script src="{{asset('crtxassets/js/vendor/jquery-ui.js')}}"></script>
<script src="{{asset('crtxassets/js/vendor/bootstrap.min.js')}}"></script>
<script src="{{asset('crtxassets/js/vendor/select2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="{{asset('crtxassets/js/vendor/popper.min.js')}}"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.5/purify.min.js" integrity="sha512-KqUc2WMPF/gxte9xVjVE4TIt1LMUTidO3BrcItFg0Ro24I7pGNzgcXdnWdezNY+8T0/JEmdC79MuwYn+8UdOqw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('crtxassets/js/imask.js')}}"></script>
{{-- <script src="{{asset('crtxassets/js/general.js')}}"></script> --}}
<script src="{{ asset('js/app.js?ver=' . filemtime(public_path('js/app.js'))) }}"></script>


@yield('js')
</body>
</html>
