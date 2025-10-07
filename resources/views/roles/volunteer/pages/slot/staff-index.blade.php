@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="page-title-box">
                <h3 class="page-title text-dark fw-bold mb-0">
                    <i class="ri-user-star-line me-2"></i> Staff Volunteer Slots
                </h3>
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('volunteer.dashboard') }}" class="text-muted">
                                <i class="ri-home-line"></i> Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">
                            <i class="ri-user-star-line"></i> Staff Slots
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
                        <a href="{{ route('volunteer.staff-slots') }}" class="btn btn-clear">
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
            <div class="card border-0 shadow-sm h-100 slot-card staff-slot">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="w-75">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="ri-building-2-line me-2"></i> {{ $slot['organization'] }}
                            </h5>
                            <p class="text-muted small mb-2">
                                <i class="ri-map-pin-line me-1"></i> {{ $slot['address'] }}
                            </p>
                        </div>
                        <span class="badge bg-status-{{ $slot['available_slots'] > 0 ? 'available' : 'full' }} rounded-pill px-3 py-2">
                            {{ $slot['status'] }} Available
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
                                <i class="ri-user-star-line me-2"></i> Type
                            </span>
                            <span class="detail-value fw-bold text-primary">
                                Staff Only
                            </span>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        @if(auth()->user()->hasRole('staff'))
                        <form action="{{ route('volunteer.book-slot.book') }}" method="post">
                            @csrf
                            <input type="hidden" name="organization" value="{{ $slot['organization'] }}">
                            <input type="hidden" name="slot_id" value="{{ $slot['id'] }}">
                            <input type="hidden" name="date" value="{{ $slot['date'] }}">
                            <input type="hidden" name="time" value="{{ $slot['time'] }}">
                            <button type="submit" class="btn btn-staff-book w-100 py-2"
                                {{ $slot['available_slots'] <= 0 || $slot['is_booked'] ? 'disabled' : '' }}>
                                <i class="ri-user-star-line me-2"></i>
                                {{ $slot['is_booked'] ? 'Already Booked' : ($slot['available_slots'] <= 0 ? 'Fully Booked' : 'Book as Staff') }}
                            </button>
                        </form>
                        @else
                        <button class="btn btn-staff-disabled w-100 py-2" disabled>
                            <i class="ri-error-warning-line me-2"></i>
                            Staff Authorization Required
                        </button>
                        @endif
                    </div>
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
                    <h5 class="mt-3 mb-2">No staff slots available</h5>
                    <p class="text-muted mb-4">There are no available staff-only slots for the selected criteria</p>
                    @if(request('filter_date'))
                    <a href="{{ route('volunteer.staff-slots') }}" class="btn btn-outline-staff px-4">
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
        --staff-color: #2e5e2f;
        --staff-color-hover: #244a25;
        --staff-light: rgba(46, 94, 47, 0.1);
    }

    /* .staff-slot {
        border-left: 4px solid var(--staff-color);
        background-color: rgba(46, 94, 47, 0.03);
    } */

    .btn-staff-book {
        background-color: var(--staff-color);
        border-color: var(--staff-color);
        color: white;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-staff-book:hover {
        background-color: var(--staff-color-hover);
        border-color: var(--staff-color-hover);
        color: white;
    }

    .btn-staff-book:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        opacity: 1;
    }

    .btn-staff-disabled {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-outline-staff {
        color: var(--staff-color);
        border-color: var(--staff-color);
    }

    .btn-outline-staff:hover {
        background-color: var(--staff-color);
        color: white;
    }

    .staff-only-banner .alert {
        border-left: 4px solid var(--staff-color);
        background-color: rgba(46, 94, 47, 0.05);
        color: var(--staff-color);
    }

    /* Flatpickr Custom Styles */
    .flatpickr-day.has-slots {
        position: relative;
        /* background-color: rgba(46, 94, 47, 0.1); */
    }
    .flatpickr-day.has-slots::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 5px;
        height: 5px;
        background-color: var(--staff-color);
        border-radius: 50%;
    }
    .flatpickr-day.has-slots:hover {
        background-color: var(--staff-light);
    }
    .flatpickr-day.selected.has-slots {
        background-color: var(--staff-color);
        border-color: var(--staff-color);
    }
    .flatpickr-day.selected.has-slots::after {
        background-color: white;
    }

    /* Existing styles */
    .slot-card {
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .slot-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px var(--staff-light);
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

    .bg-status-available {
        background-color: var(--staff-color) !important;
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

    @media (max-width: 768px) {
        .page-title-box {
            margin-bottom: 1.5rem;
        }
        .filter-container {
            width: 100%;
        }
        .input-group {
            flex-wrap: nowrap;
        }
        .input-group-text {
            padding: 0.375rem 0.75rem;
        }
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datesWithSlots = @json($datesWithSlots);

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
                    window.location.href = "{{ route('volunteer.staff-slots') }}?filter_date=" + dateStr;
                }
            }
        });

        // Set initial date if filter is active
        @if(request('filter_date'))
            flatpickrInstance.setDate("{{ request('filter_date') }}");
        @endif
    });
</script>
@endpush
