<!DOCTYPE html>
<html>
<head>
    <title>Weekly Roundup: This Week’s Overview at {{ $practiceName }}</title>
</head>
<body>
    <p>Hi Team,</p>
    <p>Wrap up your week with our comprehensive summary of the key activities and highlights from the past week at {{ $practiceName }}.</p>
    
    <p>This Week’s Summary:</p>
    <ul>
        <li>New Leads: {{ $totalNewLeadsThisWeek }}</li>
        <li>Appointments Scheduled: {{ $totalAppointmentsScheduledThisWeek }}</li>
        <li>Important Follow-Ups Completed: {{ $totalAppointmentsScheduledThisWeek }}</li>
    </ul>
    
    <p>For a more detailed review and to manage upcoming tasks, please  <a href="{{ url('/crtx/account/signin/' ) }}"> sign in to your CRTX dashboard.</a></p>
    
    <p>Thank you for another week of outstanding dedication and service to our patients!</p>
    
    <p>Best regards,<br>
    Team CRTXName</p>
</body>
</html>