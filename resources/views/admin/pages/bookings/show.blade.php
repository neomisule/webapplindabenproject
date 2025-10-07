@extends('admin.layouts.main')

@push('title')
<title>Booking Details - Admin Dashboard</title>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Booking Details</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Booking Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Booking Code</th>
                                            <td><code>{{ $booking->booking_code }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'booked' => 'bg-primary',
                                                        'checked_in' => 'bg-info',
                                                        'checked_out' => 'bg-success',
                                                        'cancelled' => 'bg-secondary'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $statusClasses[$booking->status] ?? 'bg-secondary' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Booking Date</th>
                                            <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Time Slot</th>
                                            <td>
                                                {{ Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                                {{ Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $booking->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">NGO Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">NGO Name</th>
                                            <td>{{ $booking->ngo->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{ $booking->ngo->address ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact</th>
                                            <td>{{ $booking->ngo->contact_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Volunteers Needed</th>
                                            <td>{{ $booking->ngo->volunteers_needed }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Volunteer Information</h5>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="avatar-lg">
                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                        {{ substr($booking->volunteer->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $booking->volunteer->name }}</h5>
                                    <p class="mb-0 text-muted">{{ $booking->volunteer->email }}</p>
                                    <p class="mb-0 text-muted">{{ $booking->volunteer->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($booking->checkin)
                        <div class="col-md-6">
                            <h5 class="mb-3">Check-in/Check-out Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Check-in Time</th>
                                            <td>
                                                @if($booking->checkin->checkin_time)
                                                    {{ $booking->checkin->checkin_time->format('d M Y h:i A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @if($booking->checkin->checkout_time)
                                        <tr>
                                            <th>Check-out Time</th>
                                            <td>{{ $booking->checkin->checkout_time->format('d M Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lunch Break</th>
                                            <td>
                                                @if($booking->checkin->lunch_duration > 0)
                                                    @php
                                                        $minutes = $booking->checkin->lunch_duration;
                                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                                    @endphp
                                                @else
                                                    No lunch break
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total Working Hours</th>
                                            <td>
                                                @if($booking->checkin->total_working_hours)
                                                    @php
                                                        $time = explode(':', $booking->checkin->total_working_hours);
                                                        $hours = (int)$time[0];
                                                        $minutes = (int)$time[1];

                                                        $display = [];
                                                        if ($hours > 0) {
                                                            $display[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
                                                        }
                                                        if ($minutes > 0) {
                                                            $display[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                                        }

                                                        echo implode(' ', $display) ?: '0 minutes';
                                                    @endphp
                                                @else
                                                    Not calculated
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
