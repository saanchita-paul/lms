<!DOCTYPE html>
<html>
<head>
    <title>Clinic Data Updated</title>
</head>
<body>
    <h1>Clinic Data Updated</h1>
    
    <p>We want to inform you that the clinic data for {{ $clinicName }} has been updated.</p>
    <p>Username associated with this clinic: {{ $UserName }}!</p>
    <p>Your email associated with this clinic: {{ $userEmail }}</p>
    <p>Your IP Address during this update: {{ $userIpAddress }}</p>

    <p>Below are the details of the changes made:</p>

    @foreach ($changedFields as $fieldName => $values)
        <ul>
            <li><strong>{{ $fieldName }}:</strong></li>
            <ul>
                <li>Old Value: {{ $values['old'] }}</li>
                <li>New Value: {{ $values['new'] }}</li>
            </ul>
        </ul>
    @endforeach

    <p>If you have any further queries or concerns, feel free to reach out.</p>
    <p>Thank you for your attention to this matter.<br>Best regards,<br>Microsite Team</p>    
</body>
</html>

