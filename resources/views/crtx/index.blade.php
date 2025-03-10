@section('title')
    CRTX | Get Started
@endsection
@extends('layouts.crtx')
@section('content')
    <div v-if="isAppointmentPage">
        {!!$callrail_installation_script ?? ''!!}
    </div>
<!-- backdrop & spinner -->
<div v-show="isLoading" id="backdrop">
<div class="ring">
    <figure>
        <svg version="1.1" x="0px" y="0px" height="125px" width="125px" viewBox="0 0 923 190"><g><path d="M34.28,59.34v68.31c0,10.11,8.23,18.35,18.35,18.35h170.66v21H52.63c-21.69,0-39.35-17.64-39.35-39.35V59.34c0-21.71,17.66-39.35,39.35-39.35h170.66v21H52.63C42.51,41,34.28,49.23,34.28,59.34z"></path><path d="M415.64,41h-147v126h-21v-147h168.01c23.17,0,42,18.85,42,42c0,23.15-18.83,42-42,42V83c11.58,0,21-9.43,21-21S427.22,41,415.64,41z M356.22,83l101.42,78.02V167h-26.68l-81.9-63h-38.42V83H356.22z"></path><path d="M680.87,19.99v21h-94.59v126h-21V41h-94.42v-21H680.87z"></path><path d="M900.62,29.74L836.86,93.5l63.76,63.76V167h-21v-1.04L817.66,104h-44.17l-61.96,61.96V167h-21v-9.74l63.76-63.76l-63.76-63.76v-9.74h21v1.04L773.49,83h44.17l61.96-61.96v-1.04h21V29.74z"></path></g></svg>
    </figure>
</div>
</div>
<router-view env="{{$env}}">
</router-view>
@endsection
