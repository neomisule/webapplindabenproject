@extends('roles.volunteer.layouts.main')

@push('css')
<style>
    .btn-custom {
        background-color: #2e5e2f;
        border-color: #2e5e2f;
        color: white;
    }

    .btn-custom:hover {
        background-color: #1e4e1f;
        border-color: #1e4e1f;
    }

    .summary-card {
        border-left: 4px solid #2e5e2f;
    }
</style>
@endpush

@section('main-section')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">Volunteer Check-Out</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.bookings') }}">My Bookings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-Out</li>
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
                            <i class="ri-logout-circle-line fs-4" style="color: #2e5e2f;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0 text-dark">Complete Your Check-Out</h5>
                            <p class="mb-0 text-dark-50 small">Record your volunteering details</p>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4 py-4">
                    <div class="summary-card bg-light p-4 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="ri-information-line me-2" style="color: #2e5e2f;"></i>
                            Your Check-In Summary
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
                        </div>
                    </div>

                    <form action="{{ route('volunteer.checkout.process', $checkin->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="ri-restaurant-line me-2" style="color: #2e5e2f;"></i>
                                Lunch Duration (minutes)
                            </label>
                            <input type="number" class="form-control @error('lunch_duration') is-invalid @enderror"
                                id="lunch_duration" name="lunch_duration"
                                min="0" max="240" value="0" required>
                            @error('lunch_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-flex align-items-center mt-2">
                                <i class="ri-information-line me-1"></i>
                                Enter total minutes taken for lunch (e.g., 2 for two minutes)
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold d-flex align-items-center">
                                <i class="ri-file-text-line me-2" style="color: #2e5e2f;"></i>
                                Additional Notes
                            </label>
                            <textarea class="form-control" id="notes" name="notes"
                                rows="3" placeholder="Share your volunteering experience or any important notes..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('volunteer.bookings') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="ri-arrow-left-line me-2"></i> Back to Bookings
                            </a>
                            <button type="submit" class="btn btn-custom rounded-pill px-4">
                                <i class="ri-checkbox-circle-line me-2"></i> Complete Check-Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('lunch_end').addEventListener('change', function() {
        const checkinTime = new Date('{{ $checkin->checkin_time }}');
        const now = new Date();
        const lunchStart = document.getElementById('lunch_start').value;
        const lunchEnd = this.value;

        if (lunchStart && lunchEnd) {
            const lunchDuration = (new Date(`2000-01-01T${lunchEnd}:00`) - new Date(`2000-01-01T${lunchStart}:00`)) / 60000;
            const workingMinutes = (now - checkinTime) / 60000;

            if (workingMinutes < lunchDuration) {
                alert('Warning: Your lunch duration appears longer than your working time. Please verify the times.');
            }
        }
    });
</script>
@endsection
