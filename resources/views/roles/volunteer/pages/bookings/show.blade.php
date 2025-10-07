@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">Booking Details</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.bookings') }}">My Bookings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Booking Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                @if($booking->checkin && $booking->checkin->checkoutRequest)
                @if($booking->checkin->checkoutRequest->status == 'pending')
                <span class="badge bg-info rounded-pill px-3 py-2 fw-medium">
                    Checkout Pending Approval
                </span>
                @elseif($booking->checkin->checkoutRequest->status == 'approved')
                <span class="badge bg-success rounded-pill px-3 py-2 fw-medium">
                    Checkout Approved
                </span>
                @elseif($booking->checkin->checkoutRequest->status == 'rejected')
                <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium">
                    Checkout Rejected
                </span>
                @endif
                @else
                <span class="badge bg-{{
                        $booking->status == 'booked' ? 'primary' :
                        ($booking->status == 'checked_in' ? 'warning' :
                        ($booking->status == 'checked_out' ? 'success' : 'danger'))
                    }} rounded-pill px-3 py-2 fw-medium">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-12 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom rounded-top">
                <h5 class="mb-0 fw-bold">Volunteering Details</h5>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Organization</h6>
                                <p class="fw-semibold mb-0">{{ $booking->ngo->name }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Address</h6>
                                <p class="fw-semibold mb-0">{{ $booking->ngo->address ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Booking Status</h6>
                                <span class="badge bg-{{
                                    $booking->status == 'booked' ? 'primary' :
                                    ($booking->status == 'checked_in' ? 'warning' :
                                    ($booking->status == 'checked_out' ? 'success' : 'danger'))
                                }} rounded-pill fw-medium">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Volunteering Date</h6>
                                <p class="fw-semibold mb-0">{{ $booking->booking_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted small mb-1">Shift Time</h6>
                                <p class="fw-semibold mb-0">
                                    {{ Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                    {{ Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($booking->checkin)
                    <div class="volunteer-checkin-details border-top pt-4 mt-4">
                        <h5 class="mb-4 fw-bold">Check-In/Out Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Check-In Time</h6>
                                    <p class="fw-semibold mb-0">
                                        @if($booking->checkin->checkin_time)
                                        {{ $booking->checkin->checkin_time->format('d M Y, h:i A') }}
                                        @else
                                        Not recorded
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($booking->checkin->checkoutRequest)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Checkout Request Status</h6>
                                    <p class="fw-semibold mb-0">
                                        @if($booking->checkin->checkoutRequest->status == 'pending')
                                        <span class="badge bg-info">Pending Approval</span>
                                        @elseif($booking->checkin->checkoutRequest->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Requested Checkout Time</h6>
                                    <p class="fw-semibold mb-0">
                                        {{ $booking->checkin->checkoutRequest->checkout_time }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($booking->checkin->checkout_time)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                @if($booking->checkin->lunch_duration > 0)
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Lunch Break</h6>
                                    <p class="fw-semibold mb-0">
                                        @php
                                        $minutes = $booking->checkin->lunch_duration;
                                        echo $minutes . ' minute' . ($minutes > 0 ? 's' : '');
                                        @endphp
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-1">Total Working Hours</h6>
                                    <p class="fw-semibold mb-0">
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
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-light rounded-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="{{ route('volunteer.bookings') }}" class="btn btn-outline-secondary rounded-pill w-100">
                                <i class="ri-arrow-left-line me-2"></i> Back to Bookings
                            </a>
                        </div>

                        @if($booking->status == 'booked')
                        <div class="col-md-6">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <a href="{{ route('volunteer.checkin') }}" class="btn rounded-pill flex-grow-1" style="background-color: #2e5e2f; border-color: #2e5e2f; color: white;">
                                    <i class="ri-login-circle-line me-2"></i> Check In
                                </a>
                                <form action="{{ route('volunteer.bookings.cancel', $booking->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill w-100" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="ri-close-circle-line me-2"></i> Cancel Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                        @elseif($booking->status == 'checked_in' && !$booking->checkin->checkout_time && (!$booking->checkin->checkoutRequest || $booking->checkin->checkoutRequest->status == 'rejected'))
                        <div class="col-md-6">
                            <a href="{{ route('volunteer.checkout', $booking->checkin->id) }}" class="btn btn-warning rounded-pill w-100">
                                <i class="ri-logout-circle-line me-2"></i>
                                @if($booking->checkin->checkoutRequest && $booking->checkin->checkoutRequest->status == 'rejected')
                                Request Checkout Again
                                @else
                                Check Out
                                @endif
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
