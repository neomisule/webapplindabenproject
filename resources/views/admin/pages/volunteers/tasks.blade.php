@extends('admin.layouts.main')

@push('title')
<title>Volunteer Tasks - LindaBen CMS</title>
@endpush

@push('css')
<style>
    /* Same styles as in the main file */
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
        <h3 class="dark">Tasks for {{ $volunteer->name }}</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.volunteers.index') }}">Volunteers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tasks</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('admin.volunteers.index') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Volunteers
        </a>
        <a href="{{ route('admin.tasks.assign', $volunteer->id) }}" class="btn btn-primary ms-2">
            <i class="ri-add-line me-1"></i> Assign New Task
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @php
                    $assignments = $volunteer->taskAssignments()->with('task')->latest()->get();
                    $completedCount = $assignments->where('status', 'completed')->count();
                    $inProgressCount = $assignments->where('status', 'in_progress')->count();
                    $pendingCount = $assignments->where('status', 'pending')->count();
                    @endphp

                    @if($assignments->isEmpty())
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i> No tasks assigned to this volunteer yet.
                    </div>
                    @else
                    <div class="task-stats">
                        <div class="task-stat text-center">
                            <span class="stat-value">{{ $assignments->count() }}</span>
                            <span class="stat-label">Total Tasks</span>
                        </div>
                        <div class="task-stat text-center">
                            <span class="stat-value text-success">{{ $completedCount }}</span>
                            <span class="stat-label">Completed</span>
                        </div>
                        <div class="task-stat text-center">
                            <span class="stat-value text-info">{{ $inProgressCount }}</span>
                            <span class="stat-label">In Progress</span>
                        </div>
                        <div class="task-stat text-center">
                            <span class="stat-value text-secondary">{{ $pendingCount }}</span>
                            <span class="stat-label">Pending</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm task-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Task Details</th>
                                    <th width="120">Priority</th>
                                    <th width="120">Status</th>
                                    <th width="120">Assigned</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                <tr class="task-summary-card {{ str_replace('_', '-', $assignment->status) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $assignment->task->title }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($assignment->task->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge task-priority-badge
                                            @if($assignment->task->priority == 'high') bg-danger
                                            @elseif($assignment->task->priority == 'medium') bg-warning
                                            @else bg-primary @endif">
                                            {{ ucfirst($assignment->task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge task-status-badge
                                            @if($assignment->status == 'completed') bg-success
                                            @elseif($assignment->status == 'in_progress') bg-info
                                            @else bg-secondary @endif">
                                            {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $assignment->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">

                                            <form action="{{ route('admin.tasks.unassign', ['task' => $assignment->task_id, 'user' => $volunteer->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Unassign Task"
                                                    onclick="return confirm('Are you sure you want to unassign this task?')">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
