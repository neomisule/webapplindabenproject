@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 12px;">
                <div class="card-header py-3 position-relative" style="background-color: #2e5e2f;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\" fill=\"white\"><circle cx=\"25\" cy=\"25\" r=\"3\"/><circle cx=\"75\" cy=\"75\" r=\"3\"/><circle cx=\"25\" cy=\"75\" r=\"3\"/><circle cx=\"75\" cy=\"25\" r=\"3\"/></svg></div>
                    <div class="position-relative d-flex align-items-center">
                        <div class="bg-white p-2 rounded-circle flex-shrink-0">
                            <i class="ri-checkbox-circle-line fs-4" style="color: #2e5e2f;"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 text-dark">Check-In Confirmed</h5>
                            <p class="mb-0 text-dark-50 small">Your volunteer session has started</p>
                        </div>
                    </div>
                </div>

                <div class="card-body text-center px-4 py-5">
                    <div class="mb-4 position-relative">
                        <div class="position-absolute top-50 start-50 translate-middle" style="z-index: 0;">
                            <div class="rounded-circle" style="width: 120px; height: 120px; background-color: rgba(46, 94, 47, 0.1);"></div>
                        </div>
                        <i class="ri-checkbox-circle-fill text-success position-relative" style="font-size: 5rem; z-index: 1;"></i>
                    </div>

                    <h3 class="mb-4" style="color: #2e5e2f;">You're Successfully Checked In!</h3>

                    <div class="checkin-details bg-light p-4 rounded-3 mb-4 text-start" style="border: 1px solid rgba(46, 94, 47, 0.2);">
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
                                    <i class="ri-time-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Check-In Time</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">{{ $checkin->checkin_time->format('d M Y, h:i A') }}</p>
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
                                    <i class="ri-timer-line me-2" style="color: #2e5e2f;"></i>
                                    <h6 class="text-muted mb-0 small">Shift Time</h6>
                                </div>
                                <p class="fw-semibold mb-0 ps-4">
                                    {{ date('h:i A', strtotime($checkin->booking->start_time)) }} -
                                    {{ date('h:i A', strtotime($checkin->booking->end_time)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mb-4">
                        <i class="ri-information-line me-1"></i> You can now proceed with your volunteer work. Don't forget to check out when finished.
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('volunteer.dashboard') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                            <i class="ri-home-line me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('volunteer.checkout', $checkin->id) }}" class="btn px-4 rounded-pill" style="background-color: #2e5e2f; color: white;">
                            <i class="ri-logout-circle-line me-2"></i> Check Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .checkin-details {
        transition: all 0.3s ease;
    }

    .checkin-details:hover {
        box-shadow: 0 4px 12px rgba(46, 94, 47, 0.1);
        border-color: rgba(46, 94, 47, 0.3) !important;
    }

    .btn-outline-secondary {
        border-color: #dee2e6;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: black;
    }

    .btn[style*="background-color: #2e5e2f"]:hover {
        background-color: #1e4e1f !important;
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .checkin-details {
            padding: 1rem;
        }
    }
</style>
@endpush
