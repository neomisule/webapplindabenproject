@extends('admin.layouts.main')

@push('title')
<title>Volunteer Management - LindaBen CMS</title>
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
        <h3 class="dark">Volunteer Management</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Volunteers</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.volunteers.create') }}" class="btn btn-primary">
    <i class="ri-user-add-line me-1"></i> Add New Volunteer
</a>
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
                                    <th width="180" class="pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($volunteers as $volunteer)
                                <tr>
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td><div class="d-flex flex-column">
                                            <span>{{ $volunteer->name }}</span>
                                            @if($volunteer->hasRole('volunteer'))
                                            <div class="d-flex gap-2 mt-1">
                                                <span class="badge bg-danger">Strikes: {{ $volunteer->strikes ?? 0 }}</span>
                                                <span class="badge bg-success">Points: {{ $volunteer->points ?? 0 }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $volunteer->email }}</td>
                                    <td>{{ $volunteer->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        @if($volunteer->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                        @else
                                        <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="table-actions text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.volunteers.tasks', $volunteer->id) }}"
                                                class="btn btn-primary btn-sm"
                                                title="View Tasks">
                                                <i class="ri-task-line"></i> Tasks
                                            </a>
                                            <a href="{{ route('admin.volunteers.book-slot', $volunteer->id) }}"
                                                class="btn btn-success btn-sm btn-sm"
                                                title="Book Slot">
                                                <i class="ri-calendar-line"></i> Book Slot
                                            </a>
                                            <a href="{{ route('admin.volunteers.bookings', $volunteer->id) }}"
                                                class="btn btn-info btn-sm text-white"
                                                title="View Bookings">
                                                <i class="ri-calendar-line"></i> Bookings
                                            </a>

                                            @if($volunteer->status == 0)
                                            <form action="{{ route('admin.volunteers.approve', $volunteer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm approve-btn btn-sm"
                                                    title="Approve Volunteer">
                                                    <i class="ri-check-line"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <a href="{{ route('admin.volunteers.edit', $volunteer->id) }}"
                                                class="btn btn-warning btn-sm text-white"
                                                title="Edit Volunteer">
                                                <i class="ri-edit-line"></i>
                                            </a>

                                            <form action="{{ route('admin.volunteers.destroy', $volunteer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-btn btn-sm"
                                                    title="Delete Volunteer">
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
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '.approve-btn', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Approve Volunteer',
            text: "Are you sure you want to approve this volunteer?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
