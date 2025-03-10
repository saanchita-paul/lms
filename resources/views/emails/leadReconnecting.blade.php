<!DOCTYPE html>
<html>
<head>
    <title>Lead Reconnecting</title>
</head>
<body>
<p>Hi Team,</p>

<p>A Lead in stage {{$crmCustomer->status->name}} is reconnecting to CRTX! This is a good opportunity, as this Leads have the likelihood of booking and completing an appointment and proceeding with dental treatment. Let’s reach out to this lead immediately to maximize our chances of success.</p>

<p>Click Link To View Lead: <a href="{{ url('/crtx/patient-profile/' . $crmCustomer->id) }}">{{ $crmCustomer->first_name }} {{ $crmCustomer->last_name }}</a></p>

<p>Please note the CRTX automated text and email campaign has already started engaging with this lead to keep their interest piqued.</p>

<p>Let’s make the most of this opportunity!</p>

<p>Best regards,</p>
<p>Team CRTX</p>
</body>
</html>
