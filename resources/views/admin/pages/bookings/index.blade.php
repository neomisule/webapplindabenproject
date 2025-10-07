@extends('admin.layouts.main')

@push('title')
<title>All Bookings - Admin Dashboard</title>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">All Bookings</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bookings</li>
            </ol>
        </nav>
    </div>
    <!-- <div class="d-flex gap-2">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="ri-download-line"></i> Export
        </a>
    </div> -->
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">NGO</label>
                                <select name="ngo_id" class="form-select">
                                    <option value="">All NGOs</option>
                                    @foreach($ngos as $ngo)
                                    <option value="{{ $ngo->id }}" {{ request('ngo_id') == $ngo->id ? 'selected' : '' }}>
                                        {{ $ngo->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Volunteer</th>
                                    <th>NGO</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Working Hour</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div>
                                                <h6 class="mb-0">{{ $booking->volunteer->name }}</h6>
                                                <small class="text-muted">{{ $booking->volunteer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $booking->ngo->name }}</td>
                                    <td>{{ date('d M Y', strtotime($booking->booking_date)) }}</td>
                                    <td>
                                        {{ date('h:i A', strtotime($booking->start_time)) }} -
                                        {{ date('h:i A', strtotime($booking->end_time)) }}
                                    </td>
                                    <td>
                                        {{ $booking->checkin->total_working_hours ?? 'N/A' }}
                                    </td>
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
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line"></i> View
                                            </a>
                                            @if($booking->status == 'booked')
                                            <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="ri-close-line"></i> Cancel
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($bookings->hasPages())
                    <div class="p-3 border-top">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<!-- <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Bookings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.bookings.export') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select" required>
                            <option value="">Select Format</option>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Columns to Export</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="id" id="col_id" checked>
                                    <label class="form-check-label" for="col_id">ID</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="volunteer_name" id="col_volunteer" checked>
                                    <label class="form-check-label" for="col_volunteer">Volunteer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="ngo_name" id="col_ngo" checked>
                                    <label class="form-check-label" for="col_ngo">NGO</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="booking_date" id="col_date" checked>
                                    <label class="form-check-label" for="col_date">Date</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="time_slot" id="col_time" checked>
                                    <label class="form-check-label" for="col_time">Time Slot</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="status" id="col_status" checked>
                                    <label class="form-check-label" for="col_status">Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="columns[]" value="working_hours" id="col_working_hours" checked>
                                    <label class="form-check-label" for="col_working_hours">Working Hours</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="ngo_id" value="{{ request('ngo_id') }}">
                    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                    <button type="submit" class="btn btn-primary">Export</button>
                </form>
            </div>
        </div>
    </div>
</div> -->
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any date pickers if needed
        $('[data-toggle="datepicker"]').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
@endpush
