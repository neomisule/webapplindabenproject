@extends('admin.layouts.main')

@push('title')
<title>Admin Dashboard</title>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css" rel="stylesheet">
<style>
    .stat-card {
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card i {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
    }
    .stat-label {
        font-size: 1rem;
        color: #6c757d;
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 20px;
    }
    .top-performers {
        list-style-type: none;
        padding: 0;
    }
    .top-performers li {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .top-performers li:last-child {
        border-bottom: none;
    }
    .popular-location {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
    <div>
        <p class="fw-medium fs-20 mb-0">Hey, Admin &#128075;</p>
        <p class="text-muted mb-0">Here's what's happening with your platform today.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="exportDashboardData()">
            <i class="ri-download-line me-1"></i> Export Data
        </button>
    </div>
</div>

<div class="row">
    <!-- User Stats -->
    <div class="col-md-4">
        <div class="stat-card bg-primary bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-team-fill text-primary me-3"></i>
                <div>
                    <div class="stat-value text-primary">{{ $staffCount }}</div>
                    <div class="stat-label">Staff Members</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-user-heart-fill text-success me-3"></i>
                <div>
                    <div class="stat-value text-success">{{ $volunteerCount }}</div>
                    <div class="stat-label">Volunteers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-info bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-user-3-fill text-info me-3"></i>
                <div>
                    <div class="stat-value text-info">{{ $userCount }}</div>
                    <div class="stat-label">Users</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slot Stats -->
    <div class="col-md-4">
        <div class="stat-card bg-warning bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-calendar-event-fill text-warning me-3"></i>
                <div>
                    <div class="stat-value text-warning">{{ $totalSlots }}</div>
                    <div class="stat-label">Total Slots</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-purple bg-opacity-10">
            <div class="d-flex align-items-center text-light">
                <i class="ri-calendar-check-fill text-light me-3"></i>
                <div>
                    <div class="stat-value text-light">{{ $upcomingSlots }}</div>
                    <div class="stat-label text-light">Upcoming Slots</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-secondary bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-calendar-close-fill text-secondary me-3"></i>
                <div>
                    <div class="stat-value text-secondary">{{ $completedSlots }}</div>
                    <div class="stat-label">Completed Slots</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="col-md-4">
        <div class="stat-card bg-danger bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-bookmark-fill text-danger me-3"></i>
                <div>
                    <div class="stat-value text-danger">{{ $totalBookings }}</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-info bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-bookmark-check-fill text-info me-3"></i>
                <div>
                    <div class="stat-value text-info">{{ $upcomingBookings }}</div>
                    <div class="stat-label">Upcoming Bookings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-bookmark-2-fill text-success me-3"></i>
                <div>
                    <div class="stat-value text-success">{{ $completedBookings }}</div>
                    <div class="stat-label">Completed Bookings</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Stats -->
    <div class="col-md-4">
        <div class="stat-card bg-primary bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-task-fill text-primary me-3"></i>
                <div>
                    <div class="stat-value text-primary">{{ $totalTasks }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-warning bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-time-fill text-warning me-3"></i>
                <div>
                    <div class="stat-value text-warning">{{ $pendingTasks }}</div>
                    <div class="stat-label">Pending Tasks</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success bg-opacity-10">
            <div class="d-flex align-items-center">
                <i class="ri-checkbox-circle-fill text-success me-3"></i>
                <div>
                    <div class="stat-value text-success">{{ $completedTasks }}</div>
                    <div class="stat-label">Completed Tasks</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush
