@extends('roles.volunteer.layouts.main')

@push('css')
<style>
    .success-icon {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
    }

    .success-icon-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(46, 94, 47, 0.1);
        border-radius: 50%;
    }

    .summary-card {
        border-left: 4px solid #2e5e2f;
        transition: all 0.3s ease;
    }

    .summary-card:hover {
        box-shadow: 0 4px 12px rgba(46, 94, 47, 0.1);
    }

    .hours-badge {
        background-color: #e8f5e9;
        color: #2e5e2f;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
    }
</style>
@endpush

@section('main-section')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">Check-Out Completed</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.bookings') }}">My Bookings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-Out Completed</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background-color: #2e5e2f; border-radius: 8px 8px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-white p-2 rounded-circle">
                            <i class="ri-checkbox-circle-line fs-4" style="color: #2e5e2f;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0 text-dark">Volunteering Completed</h5>
                            <p class="mb-0 text-dark-50 small">Thank you for your service</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 py-4 text-center">
                    <div class="success-icon">
                        <div class="success-icon-bg"></div>
                        <i class="ri-checkbox-circle-fill position-relative" style="font-size: 5rem; color: #2e5e2f;"></i>
                    </div>

                    <h3 class="mb-4" style="color: #2e5e2f;">Thank You for Volunteering!</h3>

                    <div class="volunteer-summary summary-card bg-light p-4 rounded-3 mb-4 mx-auto text-start" style="max-width: 600px;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-community-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Organization</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->booking->ngo->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-calendar-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Date</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->booking->booking_date->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-login-circle-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Check-In Time</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkin_time->format('h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-logout-circle-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Check-Out Time</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkout_time->format('h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-timer-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Total Duration</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">
                                    <span class="hours-badge">
                                        {{ $checkin->total_working_hours }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-restaurant-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Lunch Break</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">
                                    <span class="hours-badge">
                                        @php
                                        if($checkin->lunch_duration > 0) {
                                        $minutes = $checkin->lunch_duration;
                                        echo $minutes.' min';
                                        } else {
                                        echo 'No lunch break';
                                        }
                                        @endphp
                                    </span>
                                </p>
                            </div>
                            @if($checkin->notes)
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ri-file-text-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Your Notes</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <p class="text-muted mb-4 d-flex align-items-center justify-content-center">
                        <i class="ri-information-line me-2"></i>
                        Your volunteer hours have been recorded and submitted for review.
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('volunteer.dashboard') }}" class="btn btn-custom px-4 rounded-pill">
                            <i class="ri-home-line me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('volunteer.bookings') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                            <i class="ri-list-check-2 me-2"></i> My Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
