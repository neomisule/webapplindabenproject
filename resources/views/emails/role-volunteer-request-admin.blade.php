<!DOCTYPE html>
<html>
<head>
    <title>{{ ucfirst($type) }} Volunteer Request</title>
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
        .details {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #2e5e2f;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .action-buttons {
            margin: 25px 0;
            text-align: center;
        }
        .approve-btn {
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
            font-family: inherit;
            font-size: 16px;
        }
        .approve-btn:hover {
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
        .note {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .expiry-note {
            font-size: 12px;
            margin-top: 10px;
            color: #666;
            line-height: 1.4;
        }
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
        .details {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #2e5e2f;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .action-buttons {
            margin: 25px 0;
            text-align: center;
        }
        .approve-btn {
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
            font-family: inherit;
            font-size: 16px;
        }
        .approve-btn:hover {
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
        .note {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .expiry-note {
            font-size: 12px;
            margin-top: 10px;
            color: #666;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ ucfirst($type) }} Volunteer Request</h2>
        </div>
        <div class="content">
            <p class="greeting">Hello Admin,</p>
            <p class="note">A {{ $type }} has requested to become a volunteer and requires your approval:</p>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $user->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $user->phone_number ?? 'Not provided' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Request Date:</span>
                    <span class="detail-value">{{ $user->volunteer_requested_at }}</span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('staff.approve-volunteer', $user->volunteer_approval_token) }}" class="approve-btn">
                    Approve Request
                </a>
                <p class="expiry-note">
                    This link expires on {{ $user->volunteer_token_expires }}
                </p>
            </div>

            <div class="footer">
                <p>This is an automated notification. Please do not reply to this email.</p>
                <p>© {{ date('Y') }} Your Organization Name. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
