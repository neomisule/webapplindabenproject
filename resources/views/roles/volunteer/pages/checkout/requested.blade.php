@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">Check-Out Request Submitted</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.bookings') }}">My Bookings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-Out Request</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background-color: #2e5e2f; border-radius: 8px 8px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-white p-2 rounded-circle">
                            <i class="ri-time-line fs-4" style="color: #2e5e2f;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0 text-dark">Check-Out Request Submitted</h5>
                            <p class="mb-0 text-dark-50 small">Your request is pending admin approval</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <div class="alert alert-info"  style="background-color: #e8f3e8; border-color: #c6e0c6; color: #2e5e2f;">
                        <i class="ri-information-line me-2"></i>
                        Your check-out request has been submitted and is waiting for admin approval.
                        You'll be notified once it's processed.
                    </div>

                    <div class="summary-card bg-light p-4 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="ri-information-line me-2" style="color: #2e5e2f;"></i>
                            Request Details
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-community-line me-2" style="color: #2e5e2f;"></i>
                                    <span class="text-muted small">Organization</span>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->booking->ngo->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-time-line me-2" style="color: #2e5e2f;"></i>
                                    <span class="text-muted small">Check-In Time</span>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkin_time->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-restaurant-line me-2" style="color: #2e5e2f;"></i>
                                    <span class="text-muted small">Lunch Duration</span>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkoutRequest->lunch_duration }} minutes</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-time-line me-2" style="color: #2e5e2f;"></i>
                                    <span class="text-muted small">Check-Out Time</span>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkoutRequest->checkout_time }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-time-line me-2" style="color: #2e5e2f;"></i>
                                    <span class="text-muted small">Requested At</span>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkoutRequest->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('volunteer.bookings') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="ri-arrow-left-line me-2"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
