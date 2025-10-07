<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset Successful</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Successful</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>

            <p>Your password has been successfully reset for your Volunteer System account.</p>

            <p>If you did not make this change, please contact our support team immediately.</p>

            <p>Thank you for using our platform!</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Volunteer System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
