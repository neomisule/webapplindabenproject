<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Booking Reminder</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="background-color: #2e5e2f; color: white; padding: 20px; border-radius: 8px 8px 0 0;">
                            <h2 style="margin: 0; font-size: 24px; font-weight: 600;">Upcoming Volunteer Booking</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 25px;">
                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">Dear {{ $booking->volunteer->name }},</p>

                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">This is a friendly reminder of your upcoming volunteer booking scheduled on <strong>{{ \Carbon\Carbon::parse($booking->booking_date) }}</strong> from <strong>{{ $booking->start_time }}</strong> to <strong>{{ $booking->end_time }}</strong> with <strong>{{ $booking->ngo->name }}</strong>.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse; margin: 20px 0;">
                                <tr>
                                    <td style="background-color: #f0f7f0; padding: 15px; border-left: 4px solid #2e5e2f; font-weight: 600; color: #225522;">
                                        Please ensure to arrive on time and bring any necessary items for your volunteering activity.
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">If you booked this less than 24 hours ago, this is your immediate confirmation email.</p>

                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">For any queries or assistance, feel free to contact us.</p>

                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">Thank you for your dedication and willingness to help!</p>

                            <p style="font-size: 16px; margin-bottom: 16px; color: #333;">
                                Best regards,<br>
                                {{ config('app.name') }} Team
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="margin-top: 25px; font-size: 12px; text-align: center; color: #777; border-top: 1px solid #eee; padding: 15px 25px 0;">
                            <p style="margin: 0 0 8px 0;">This is an automated message. Please do not reply directly to this email.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
