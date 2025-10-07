<!DOCTYPE html>
<html>
<head>
    <title>Your Volunteer Account Has Been Approved</title>
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
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 25px;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        .action-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2e5e2f;
            color: white !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .action-btn:hover {
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
        .highlight {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .expiry-note {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Volunteer Account Approved</h2>
        </div>
        <div class="content">
            <p>Hello {{ $volunteer->name }},</p>
            <p>We're pleased to inform you that your volunteer account has been approved by our admin team.</p>

            <div class="highlight">
                <p>To complete your registration, please set up your password:</p>
                <div class="button-container">
                    <a href="{{ route('volunteer.password.setup', $volunteer->setup_token) }}"
                       class="action-btn"
                       style="color: white; text-decoration: none;">
                        Set Up Password
                    </a>
                </div>
                <p class="expiry-note">This link will expire in 24 hours. If it expires, please contact support.</p>
            </div>

            <p>Once your password is set, you'll be able to log in to your volunteer dashboard and start contributing.</p>

            <p>Thank you for volunteering with us!</p>

            <div class="footer">
                <p>This is an automated message. Please do not reply to this email.</p>
                <p>If you didn't request this, please ignore this email.</p>
            </div>
        </div>
    </div>
</body>
</html>
