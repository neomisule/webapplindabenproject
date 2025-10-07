@extends('admin.layouts.main')

@push('title')
<title>{{ $volunteer->name }}'s Bookings - LindaBen CMS</title>
@endpush

@push('css')
<style>
    .action-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">{{ $volunteer->name }}'s Bookings</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.volunteers.index') }}">Volunteers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bookings</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.volunteers.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="ri-arrow-left-line me-2"></i> Back to Volunteers
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>NGO</th>
                                    <th>Booking Date</th>
                                    <th>Shift Time</th>
                                    <th>Booking Code</th>
                                    <th width="120">Status</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($volunteer->bookings as $booking)
                                <tr>
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $booking->ngo->name }}</td>
                                    <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td>
                                        {{ date('h:i A', strtotime($booking->start_time)) }} -
                                        {{ date('h:i A', strtotime($booking->end_time)) }}
                                    </td>
                                    <td>{{ $booking->booking_code }}</td>
                                    <td>
                                      <span class="badge bg-{{
                                            $booking->status == 'booked' ? 'primary' :
                                            ($booking->status == 'checked_in' ? 'warning' :
                                            ($booking->status == 'checked_out' ? 'success' : 'danger'))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($booking->status == 'booked')
                                        <form action="{{ route('admin.volunteers.bookings.cancel', [$volunteer->id, $booking->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm action-btn cancel-booking-btn" title="Cancel Booking">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No bookings found for this volunteer</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Cancel booking confirmation
        $('.cancel-booking-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Cancel Booking',
                text: "Are you sure you want to cancel this booking?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
