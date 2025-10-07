@extends('admin.layouts.main')

@push('title')
<title>Checkout Requests - Admin</title>
@endpush

@section('main-section')
<div class="page-header">
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <h3 class="dark">Checkout Requests</h3>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout Requests</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Volunteer</th>
                                    <th>Organization</th>
                                    <th>Check-In Time</th>
                                    <th>Check-Out Time</th>
                                    <th>Working Hours</th>
                                    <th>Lunch Duration</th>
                                    <th>Requested At</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                @php
                                    $checkinTime = \Carbon\Carbon::parse($request->checkin->checkin_time);
                                    $checkoutTime = \Carbon\Carbon::parse($request->checkout_time);
                                    $totalWorkingSeconds = $checkinTime->diffInSeconds($checkoutTime);
                                    $lunchDuration = $request->lunch_duration ?? 0;
                                    $netWorkingSeconds = max(0, $totalWorkingSeconds - ($lunchDuration * 60));
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $request->volunteer->name }}</td>
                                    <td>{{ $request->checkin->booking->ngo->name }}</td>
                                    <td>{{ $checkinTime->format('d M Y, h:i A') }}</td>
                                    <td>{{ $checkoutTime->format('d M Y, h:i A') }}</td>
                                    <td>{{ sprintf('%dh %02dm %02ds', floor($netWorkingSeconds/3600), floor(($netWorkingSeconds%3600)/60), $netWorkingSeconds%60) }}</td>

                                    <td>{{ $lunchDuration }} minutes</td>
                                    <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="table-actions text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="{{ route('admin.checkout-requests.approve', $request->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="ri-check-line"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.checkout-requests.reject', $request->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="ri-close-line"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
