<!DOCTYPE html>
<html>
<head>
    <title>Bookings Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .title { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="title">
        <h2>Bookings Report</h2>
        <p>Generated on: {{ date('d M Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                @if(in_array('id', $columns))
                    <th>ID</th>
                @endif
                @if(in_array('volunteer_name', $columns))
                    <th>Volunteer Name</th>
                @endif
                @if(in_array('ngo_name', $columns))
                    <th>NGO Name</th>
                @endif
                @if(in_array('booking_date', $columns))
                    <th>Booking Date</th>
                @endif
                @if(in_array('time_slot', $columns))
                    <th>Time Slot</th>
                @endif
                @if(in_array('working_hours', $columns))
                    <th>Working Hours</th>
                @endif
                @if(in_array('status', $columns))
                    <th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>
                @if(in_array('id', $columns))
                    <td>{{ $booking->id }}</td>
                @endif
                @if(in_array('volunteer_name', $columns))
                    <td>{{ $booking->volunteer->name }}</td>
                @endif
                @if(in_array('ngo_name', $columns))
                    <td>{{ $booking->ngo->name }}</td>
                @endif
                @if(in_array('booking_date', $columns))
                    <td>{{ date('d M Y', strtotime($booking->booking_date)) }}</td>
                @endif
                @if(in_array('time_slot', $columns))
                    <td>
                        {{ date('h:i A', strtotime($booking->start_time)) }} -
                        {{ date('h:i A', strtotime($booking->end_time)) }}
                    </td>
                @endif
                @if(in_array('working_hours', $columns))
                    <td>{{ $booking->checkin->total_working_hours ?? 'N/A' }}</td>
                @endif
                @if(in_array('status', $columns))
                    <td>{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
