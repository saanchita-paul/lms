<!DOCTYPE html>
<html>
<head>
    <title>New Green Lead Identified</title>
</head>
<body>
    <p>Hi Team,</p>

    <p>Fantastic news! A new patient has just scheduled an appointment!</p>

    <p>Patient Details:</p>

    <ul>
        <li>Name : {{ $data['first_name'] }} {{ $data['last_name'] }}</li>
        <li>Appointment Date & Time : {{ $data['date'] }} {{ $data['time'] }} </li>
    </ul>

    <p>No further action is needed at this time, but feel free to review the patient's profile to familiarize yourself before their visit.</p>


    <p>Click Link To View Lead: <a href="{{ url('/crtx/patient-profile/' . $crmCustomer->id) }}">{{ $crmCustomer->first_name }} {{ $crmCustomer->last_name }}</a></p>

    <p>Best regards,</p>
    <p>Team CRTX</p>
</body>
</html>
