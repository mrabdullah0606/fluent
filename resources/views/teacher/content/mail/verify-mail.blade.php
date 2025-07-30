<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .code {
            background-color: #007bff;
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            letter-spacing: 8px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $data['title'] }}</h1>
        </div>

        <p>Hello {{ $data['name'] ?? 'User' }},</p>

        <p>Thank you for registering! Please use the verification code below to complete your registration:</p>

        <div class="code">
            {{ $data['code'] }}
        </div>

        <p>This code will expire in 15 minutes for security reasons.</p>

        <p>If you didn't create an account, please ignore this email.</p>

        <div class="footer">
            <p>Thank you,<br>Your App Team</p>
        </div>
    </div>
</body>

</html>
