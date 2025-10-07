@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="page-title-box">
                <h3 class="page-title text-dark fw-bold mb-0">
                    <i class="ri-bookmark-line me-2"></i> Book Slot
                </h3>
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('volunteer.dashboard') }}" class="text-muted">
                                <i class="ri-home-line"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">
                            <i class="ri-bookmark-line"></i> Book Slot
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end h-100 align-items-center">
                <div class="filter-container">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="ri-calendar-line text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="filter-date" placeholder="Select date" value="{{ request('filter_date') }}">
                        @if(request('filter_date'))
                        <a href="{{ route('volunteer.book-slot') }}" class="btn btn-clear">
                            <i class="ri-close-line"></i> Clear
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        @if(count($slots) > 0)
        @foreach($slots as $slot)
        <div class="col-xl-6 col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 slot-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="w-75">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="ri-community-line me-2"></i> {{ $slot['organization'] }}
                            </h5>
                            <p class="text-muted small mb-2">
                                <i class="ri-map-pin-line me-1"></i> {{ $slot['address'] }}
                            </p>
                        </div>
                        <span class="badge bg-status-{{ $slot['available_slots'] > 0 ? 'available' : 'full' }} rounded-pill px-3 py-2">
                            {{ $slot['available_slots'] }} Available
                        </span>
                    </div>

                    <div class="slot-details">
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="ri-time-line me-2"></i> Time
                            </span>
                            <span class="detail-value fw-bold">
                                {{ $slot['time'] }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="ri-calendar-line me-2"></i> Date
                            </span>
                            <span class="detail-value fw-bold">
                                {{ $slot['date'] }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="ri-book-2-line me-2"></i> Program
                            </span>
                            <span class="detail-value fw-bold">
                                {{ $slot['program'] }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="ri-user-3-line me-2"></i> Role
                            </span>
                            <span class="detail-value fw-bold">
                                {{ $slot['role'] }}
                            </span>
                        </div>
                    </div>

                    @if(!$slot['is_booked'] && $slot['available_slots'] > 0)
                    <div class="d-grid mt-4">
                        <form action="{{ route('volunteer.book-slot.book') }}" method="post" id="booking-form-{{ $slot['id'] }}">
                            @csrf
                            <input type="hidden" name="slot_id" value="{{ $slot['id'] }}">

                            <!-- Booking Options -->
                            <div class="booking-options mb-3">
                                <div class="form-check">
                                    <input class="form-check-input booking-type" type="radio"
                                           name="booking_type" id="full-time-{{ $slot['id'] }}"
                                           value="full_time" checked>
                                    <label class="form-check-label" for="full-time-{{ $slot['id'] }}">
                                        <strong>Book Full Time Slot</strong><br>
                                        <small class="text-muted">{{ $slot['time'] }}</small>
                                    </label>
                                </div>


                                <div class="form-check mt-2">
                                    <input class="form-check-input booking-type" type="radio"
                                           name="booking_type" id="custom-time-{{ $slot['id'] }}"
                                           value="custom_time">
                                    <label class="form-check-label" for="custom-time-{{ $slot['id'] }}">
                                        <strong>Choose My Own Time</strong><br>
                                        <small class="text-muted">Select your preferred hours</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Custom Time Selection (Hidden by default) -->

                            <div class="custom-time-container mb-3" style="display: none;">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Start Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="ri-time-line"></i>
                                            </span>
                                            <input type="time" class="form-control start-time"
                                                   name="custom_start_time"
                                                   min="{{ date('H:i', strtotime($slot['start_time'])) }}"
                                                   max="{{ date('H:i', strtotime($slot['end_time'])) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="ri-time-line"></i>
                                            </span>
                                            <input type="time" class="form-control end-time"
                                                   name="custom_end_time"
                                                   min="{{ date('H:i', strtotime($slot['start_time'])) }}"
                                                   max="{{ date('H:i', strtotime($slot['end_time'])) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="ri-information-line"></i>
                                        Available from {{ date('h:i A', strtotime($slot['start_time'])) }} to {{ date('h:i A', strtotime($slot['end_time'])) }}
                                    </small>
                                </div>
                                <div class="time-validation-error text-danger small mt-2"></div>
                            </div>

                            <button type="submit" class="btn btn-book w-100 py-2">
                                <i class="ri-bookmark-line me-2"></i>
                                Confirm Booking
                            </button>
                        </form>
                    </div>
                    @elseif($slot['is_booked'])
                    <div class="alert alert-booked mt-3 mb-0">
                        <i class="ri-checkbox-circle-line me-2"></i> You've booked this slot
                    </div>
                    @else
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="ri-error-warning-line me-2"></i> This slot is fully booked
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="card border-0 shadow-sm empty-state-card">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon">
                        <i class="ri-calendar-close-line"></i>
                    </div>
                    <h5 class="mt-3 mb-2">No slots available</h5>
                    <p class="text-muted mb-4">There are no available slots for the selected criteria</p>
                    @if(request('filter_date'))
                    <a href="{{ route('volunteer.book-slot') }}" class="btn btn-outline-book px-4">
                        <i class="ri-refresh-line me-2"></i> Reset Filters
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    :root {
        --book-color: #2e5e2f;
        --book-color-hover: #244a25;
    }

    .slot-card {
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .slot-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(46, 94, 47, 0.1);
    }

    .slot-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        margin: 15px 0;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        align-items: center;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: #6c757d;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }

    .detail-value {
        font-weight: 600;
        color: #343a40;
    }

    .btn-book {
        background-color: var(--book-color);
        border-color: var(--book-color);
        color: white;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-book:hover {
        background-color: var(--book-color-hover);
        border-color: var(--book-color-hover);
        color: white;
    }

    .btn-book:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        opacity: 1;
    }

    .btn-outline-book {
        color: var(--book-color);
        border-color: var(--book-color);
    }

    .btn-outline-book:hover {
        background-color: var(--book-color);
        color: white;
    }

    .bg-status-available {
        background-color: var(--book-color) !important;
    }

    .bg-status-full {
        background-color: #dc3545 !important;
    }

    .btn-clear {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #dc3545;
    }

    .btn-clear:hover {
        background-color: #f1f1f1;
    }

    .empty-state-card {
        background: #f8f9fa;
        border-radius: 10px;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }

    .flatpickr-day.has-slots {
        position: relative;
    }

    .flatpickr-day.has-slots::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 5px;
        height: 5px;
        background-color: var(--book-color);
        border-radius: 50%;
    }

    .flatpickr-day.has-slots:hover::after {
        background-color: white;
    }

    .flatpickr-day.selected.has-slots::after {
        background-color: white;
    }

    .time-validation-error {
        display: none;
        font-size: 0.8rem;
    }

    .alert-booked {
        background-color: rgba(46, 94, 47, 0.1);
        border-color: rgba(46, 94, 47, 0.2);
        color: var(--book-color);
    }

    .booking-options .form-check {
        padding-left: 0;
        margin-bottom: 8px;
    }

    .booking-options .form-check-input {
        margin-left: 0;
        margin-right: 10px;
        margin-top: 0.3rem;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datesWithSlots = @json($datesWithSlots);

        // Initialize date picker
        const flatpickrInstance = flatpickr("#filter-date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                if (datesWithSlots.includes(dateStr)) {
                    dayElem.classList.add("has-slots");
                }
            },
            onChange: function(selectedDates, dateStr) {
                if (dateStr) {
                    window.location.href = "{{ route('volunteer.book-slot') }}?filter_date=" + dateStr;
                }
            }
        });

        @if(request('filter_date'))
        flatpickrInstance.setDate("{{ request('filter_date') }}");
        @endif

        // Toggle between full time and custom time booking
        document.querySelectorAll('.booking-type').forEach(radio => {
            radio.addEventListener('change', function() {
                const form = this.closest('form');
                const customTimeContainer = form.querySelector('.custom-time-container');

                if (this.value === 'custom_time') {
                    customTimeContainer.style.display = 'block';

                    // Set default values for time inputs
                    const startInput = form.querySelector('.start-time');
                    const endInput = form.querySelector('.end-time');
                    const minHours = {{ $slot['min_hours_per_volunteer'] ?? 2 }};

                    if (!startInput.value) {
                        startInput.value = "{{ date('H:i', strtotime($slot['start_time'])) }}";
                    }

                    if (!endInput.value) {
                        const defaultEndTime = new Date(`2000-01-01 ${startInput.value}`);
                        defaultEndTime.setHours(defaultEndTime.getHours() + minHours);
                        endInput.value = defaultEndTime.toTimeString().substring(0, 5);
                    }
                } else {
                    customTimeContainer.style.display = 'none';
                }
            });
        });

        // Form submission validation with SweetAlert confirmation
        document.querySelectorAll('form[id^="booking-form-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(form);
                const bookingType = form.querySelector('input[name="booking_type"]:checked').value;
                const slotId = form.querySelector('input[name="slot_id"]').value;

                // Validate custom time if selected
                if (bookingType === 'custom_time') {
                    const startInput = form.querySelector('.start-time');
                    const endInput = form.querySelector('.end-time');
                    const errorElement = form.querySelector('.time-validation-error');
                    const minHours = {{ $slot['min_hours_per_volunteer'] ?? 2 }};

                    // Reset error
                    errorElement.style.display = 'none';

                    if (!startInput.value || !endInput.value) {
                        errorElement.textContent = 'Please select both start and end times';
                        errorElement.style.display = 'block';
                        return;
                    }

                    const startTime = new Date(`2000-01-01T${startInput.value}`);
                    const endTime = new Date(`2000-01-01T${endInput.value}`);
                    const durationHours = (endTime - startTime) / (1000 * 60 * 60);

                    if (endTime <= startTime) {
                        errorElement.textContent = 'End time must be after start time';
                        errorElement.style.display = 'block';
                        return;
                    }

                    if (durationHours < minHours) {
                        errorElement.textContent = `Minimum booking duration is ${minHours} hours`;
                        errorElement.style.display = 'block';
                        return;
                    }
                }

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Confirm Booking?',
                    text: 'Are you sure you want to book this slot?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2e5e2f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, book it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        form.submit();
                    }
                });
            });
        });

        // Time input validation
        document.querySelectorAll('.start-time, .end-time').forEach(input => {
            input.addEventListener('change', function() {
                const form = this.closest('form');
                const startInput = form.querySelector('.start-time');
                const endInput = form.querySelector('.end-time');
                const errorElement = form.querySelector('.time-validation-error');
                const minHours = {{ $slot['min_hours_per_volunteer'] ?? 2 }};

                if (startInput.value && endInput.value) {
                    const startTime = new Date(`2000-01-01T${startInput.value}`);
                    const endTime = new Date(`2000-01-01T${endInput.value}`);
                    const durationHours = (endTime - startTime) / (1000 * 60 * 60);

                    errorElement.style.display = 'none';

                    if (endTime <= startTime) {
                        errorElement.textContent = 'End time must be after start time';
                        errorElement.style.display = 'block';
                        endInput.value = '';
                    } else if (durationHours < minHours) {
                        errorElement.textContent = `Minimum booking duration is ${minHours} hours`;
                        errorElement.style.display = 'block';
                        endInput.value = '';
                    }
                }
            });
        });
    });
</script>
@endpush
