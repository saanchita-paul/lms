<!DOCTYPE html>
<html>
<head>
    <title>CRTX Appointment</title>
</head>
<body>
    
    

    Hi {{$lead->first_name.' '.$lead->last_name}},
    <br/><br/>
    Your appointment has been successfully scheduled. Please review the details below to ensure everything is correct.
    It is essential you reply to this email to confirm your appointment.
    <br/><br/>

    <u><b>Personal Details :- </b></u>
    <br>
    Name - {{$lead->first_name.' '.$lead->last_name}}  <br>
    Email - {{$lead->email}}  <br>
    Phone - {{$lead->phone}}  <br>
    <br>
    <u><b>Appointment Details :- </b></u>
    <br>
    Practitioner - {{$clinic->dr_name}}  <br>
    Date/Time - {{ $appointment->date }} at {{ $appointment->time }} <br>
    @if(!empty($appointment->patient_type))Patient - {{$appointment->patient_type}}  <br>@endif
    @if(!empty($appointment->services))Service - {{$appointment->services->name}}  <br>@endif
    @if(!empty($appointment->comments))Comments/Requests - {{$appointment->comments}}  <br>@endif
    <br><br>

    If you want to make changes to your scheduled appointment please click below. <br>
    @if(!empty($appointment->id))
        <a href="{{url('/crtx/schedule-appointment/'.$clinic->id.'/sa-step-1?id='.$appointment->id)}}">Change appointment details</a>
    @else
        <a href="{{url('/crtx/schedule-appointment/'.$clinic->id.'/sa-step-1')}}">Change appointment details</a>
    @endif
    <br><br>
    <ul>
        <li>You will receive reminders via email and text message as your appointment date approaches. These reminders are to help ensure that you do not miss your scheduled time.</li>
        <li>Please make every effort to attend your appointment. Missing an appointment can delay your treatment and affect your dental health. If you need to reschedule, please contact us at least 24 hours in advance to avoid any cancellation fees and to help us provide timely care to other patients.</li>
    </ul>
    <br><br>


    Regards,<br>
    Client Success Representative<br>
    Team CRTX
</body>
</html>
