<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin: 20px 0;
        }
        .warning {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Verification Required</h2>

    <p>Hello {{ $name }},</p>

    <p>Your verification code for CRTX login is:</p>

    <div class="code">{{ $code }}</div>

    <p>This code will expire in 10 minutes.</p>

    <div class="warning">
        If you didn't request this code, please ignore this email and contact support if you have concerns.
    </div>

    <p>Best regards,<br>CRTX Team</p>
</div>
</body>
</html>
