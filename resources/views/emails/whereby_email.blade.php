<!DOCTYPE html>
<html>
<head>
    <title>CRTX - Video Call Notification</title>
</head>
<body>
<p>Hi Team,</p>

<p>Great news! {{ $data['displayName'] }} is ready to join a video call.</p>

<p>You can join the video consultation here: <a href="{{ url('/crtx/video-consultations') }}">Join Video Call</a></p>

<p>Best regards,</p>
<p>Team CRTX</p>
</body>
</html>
