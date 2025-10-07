<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .button { display: inline-block; padding: 12px 24px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>

            <p>We received a request to reset your password for your Volunteer System account. Click the button below to reset your password:</p>

            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </p>

            <p>Or copy and paste this link in your browser:</p>
            <p style="word-break: break-all;"><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>

            <p><strong>This link will expire in {{ $expiryHours }} hours.</strong></p>

            <p>If you didn't request a password reset, please ignore this email. Your password will remain unchanged.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Volunteer System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
