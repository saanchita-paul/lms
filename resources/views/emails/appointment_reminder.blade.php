<!DOCTYPE html>
<html>
<head>
    <title>CRTX Appointment</title>
</head>
<body>

<b>Hi {{$lead->first_name.' '.$lead->last_name}}</b>,
<br/><br/>
This is a friendly reminder that your appointment with {{$clinic->dr_name}} is coming up. Please review the details below to ensure everything is correct:
<br/><br/>
<u><b>Appointment Details :- </b></u>
<br>
<ul>
    <li><b>Date/Time</b>: {{ \Carbon\Carbon::createFromFormat('Y-m-d', $appointment->date)->format('m/d/Y') }} at {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->time)->format('h:i A') }}</li>
    <li><b>Practitioner</b>: {{$clinic->dr_name}}</li>
    @if(!empty($appointment->services))<li><b>Service</b>: {{$appointment->services->name}}</li>@endif
    {{--<li><b>Location</b>: Virtual (Join here: <a href="/crtx/video-consultations">https://mycrtx.com/crtx/video-consultations</a>)</li>--}}
</ul>
<br>
For any questions, feel free to contact us at {{$clinic->office_number}}.<br><br>
We look forward to seeing you soon!<br>
<b>{{$clinic->clinic_name}}</b>
</body>
</html>
