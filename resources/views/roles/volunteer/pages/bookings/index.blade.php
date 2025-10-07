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
    .badge-primary { background-color: #2e5e2f; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-success { background-color: #28a745; }
    .badge-danger { background-color: #dc3545; }
    .badge-info { background-color: #17a2b8; }
</style>
@endpush
@section('main-section')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">My Bookings</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Upcoming & Past Bookings</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->ngo->name }}</td>
                                    <td>{{ date('d M Y', strtotime($booking->booking_date)) }}</td>
                                    <td>{{ date('h:i A', strtotime($booking->start_time)) }} - {{ date('h:i A', strtotime($booking->end_time)) }}</td>
                                    <td>
                                        @if($booking->checkin && $booking->checkin->checkoutRequest)
                                            @if($booking->checkin->checkoutRequest->status == 'pending')
                                                <span class="badge badge-info">
                                                    Checkout Pending Approval
                                                </span>
                                            @elseif($booking->checkin->checkoutRequest->status == 'approved')
                                                <span class="badge badge-success">
                                                    Checkout Approved
                                                </span>
                                            @elseif($booking->checkin->checkoutRequest->status == 'rejected')
                                                <span class="badge badge-danger">
                                                    Checkout Rejected
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge badge-{{
                                                $booking->status == 'booked' ? 'primary' :
                                                ($booking->status == 'checked_in' ? 'warning' :
                                                ($booking->status == 'checked_out' ? 'success' : 'danger'))
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('volunteer.bookings.show', $booking->id) }}" class="btn btn-sm btn-custom">
                                                <i class="fa fa-eye"></i> View
                                            </a>

                                            @if($booking->status == 'booked')
                                            <form action="{{ route('volunteer.bookings.cancel', $booking->id) }}" method="POST">
                                                 @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-times"></i> Cancel
                                                </button>
                                            </form>
                                            @endif

                                            @if($booking->status == 'checked_in' && !$booking->checkin->checkout_time && !$booking->checkin->checkoutRequest)
                                            <a href="{{ route('volunteer.checkout', $booking->checkin->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-sign-out-alt"></i> Checkout
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
