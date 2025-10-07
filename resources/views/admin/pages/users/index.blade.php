@extends('admin.layouts.main')

@push('title')
<title>User Management - LindaBen CMS</title>
@endpush

@push('css')
<style>
    .task-status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .task-priority-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .task-summary-card {
        border-left: 4px solid;
        border-radius: 0.25rem;
    }

    .task-summary-card.completed {
        border-left-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }

    .task-summary-card.in-progress {
        border-left-color: #17a2b8;
        background-color: rgba(23, 162, 184, 0.1);
    }

    .task-summary-card.pending {
        border-left-color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
    }

    .task-table th {
        white-space: nowrap;
    }

    .task-table td {
        vertical-align: middle;
    }

    .task-stats {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .task-stat {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        min-width: 120px;
    }

    .task-stat .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        display: block;
        line-height: 1.2;
    }

    .task-stat .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">User Management</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th width="120">Status</th>
                                    <th width="120">Volunteer</th>
                                    <th width="220" class="pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td><div class="d-flex flex-column">
                                            <span>{{ $user->name }}</span>
                                            @if($user->hasRole('volunteer'))
                                            <div class="d-flex gap-2 mt-1">
                                                <span class="badge bg-danger">Strikes: {{ $user->strikes ?? 0 }}</span>
                                                <span class="badge bg-success">Points: {{ $user->points ?? 0 }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.users.toggleStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <input type="hidden" name="new_status" value="{{ $user->status ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm toggle-btn status-badge
                                                {{ $user->status ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $user->status ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        @if($user->hasRole('volunteer'))
                                        <span class="badge bg-success">Yes</span>
                                        @else
                                        <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="table-actions text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($user->hasRole('volunteer'))
                                            <!-- Volunteer Actions -->
                                            <a href="{{ route('admin.volunteers.tasks', $user->id) }}"
                                                class="btn btn-primary btn-sm"
                                                title="View Tasks">
                                                <i class="ri-task-line"></i> Tasks
                                            </a>
                                            <a href="{{ route('admin.volunteers.book-slot', $user->id) }}"
                                                class="btn btn-success btn-sm"
                                                title="Book Slot">
                                                <i class="ri-calendar-line"></i> Book Slot
                                            </a>
                                            <a href="{{ route('admin.volunteers.bookings', $user->id) }}"
                                                class="btn btn-info btn-sm text-white"
                                                title="View Bookings">
                                                <i class="ri-calendar-check-line"></i> Bookings
                                            </a>

                                            <!-- Remove Volunteer Role -->
                                            <form action="{{ route('admin.users.remove-volunteer', $user->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm remove-volunteer-btn"
                                                    title="Remove Volunteer Role">
                                                    <i class="ri-user-unfollow-line"></i>
                                                </button>
                                            </form>
                                            @else
                                            @if($user->volunteer_requested_at && !$user->volunteer_approved_at)
                                            <!-- Approve Volunteer Request Button -->
                                            <form action="{{ route('admin.users.approve-request', $user->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm approve-request-btn accept-volunteer-btn"
                                                    title="Approve Volunteer Request">
                                                    <i class="ri-check-double-line"></i> Accept Request
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('admin.users.add-volunteer', $user->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm add-volunteer-btn"
                                                    title="Add Volunteer Role">
                                                    <i class="ri-user-add-line"></i> Add Role
                                                </button>
                                            </form>
                                            @endif
                                            @endif

                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm text-white"
                                                title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                                    title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
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

@push('js')
<script>
    $(document).ready(function() {
        // For the accept volunteer role
        $(document).on('click', '.accept-volunteer-btn', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Add Volunteer Role',
                text: "Are you sure you want to make this user a volunteer?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, add role!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Delete confirmation
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Add volunteer role confirmation
        $('.add-volunteer-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Add Volunteer Role',
                text: "Are you sure you want to make this user a volunteer?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, add role!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Remove volunteer role confirmation
        $('.remove-volunteer-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Remove Volunteer Role',
                text: "Are you sure you want to remove volunteer role from this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
