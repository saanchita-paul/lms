<!DOCTYPE html>
<html>
<head>
    <title>Weekly Roundup: This Week’s Overview at {{ $practiceName }}</title>
</head>
<body>
    <p>Hi Team,</p>
    <p>Here’s your daily roundup of activities at {{ $practiceName }}! Stay informed and prepared for what's coming next.</p>
    
    <p>Today’s Summary:</p>
    <ul>
        <li>New Leads: {{ $totalNewLeadsToday }}</li>
        <li>Appointments Scheduled: {{ $totalAppointmentsScheduledToday }}</li>
        <li>Important Follow-Ups Completed: {{ $totalFollowUpsCompletedToday }}</li>
    </ul>
    
    <p>For detailed information and to take any necessary actions, please <a href="{{ url('/crtx/account/signin/' ) }}"> sign in to your CRTX dashboard.</a></p>
    
    <p>Let’s keep up the momentum and ensure every lead and patient receives our best care!</p>
    
    <p>Best regards,<br>
    Team CRTXName</p>
</body>
</html>