<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Application Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #2e5e2f;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 25px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .message {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .highlight {
            background-color: #f0f7f0;
            padding: 15px;
            border-left: 4px solid #2e5e2f;
            margin: 20px 0;
        }
        .action-buttons {
            margin: 25px 0;
            text-align: center;
        }
        .login-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2e5e2f;
            color: white !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #264e28;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            text-align: center;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Volunteer Application Approved</h2>
        </div>
        <div class="content">
           <p class="greeting">Dear {{ $user->name }},</p>

            <p class="message">We're pleased to inform you that your request to become a volunteer has been approved by our administrator.</p>

            <div class="highlight">
                <p><strong>You now have access to both staff and volunteer features.</strong> You can switch between dashboards using the header menu after logging in.</p>
            </div>


            <p class="message">If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p class="message">Thank you for your willingness to contribute!</p>

            <p class="signature">
                Best regards,<br>
                {{ config('app.name') }} Team
            </p>

            <div class="footer">
                <p>This is an automated message. Please do not reply directly to this email.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
