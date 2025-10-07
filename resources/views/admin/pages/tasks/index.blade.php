@extends('admin.layouts.main')

@push('title')
<title>Task Management - LindaBen CMS</title>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .search-box {
        position: relative;
        width: 250px;
    }
    .search-box .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .table-container {
        position: relative;
        height: calc(100vh - 250px);
        overflow-y: auto;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th, td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    tbody tr:nth-child(even) {
        background-color: rgba(0,0,0,0.02);
    }

    tbody tr:hover {
        background-color: rgba(0,0,0,0.05);
    }

    .status-select {
        width: 120px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .select2-container {
        z-index: 9999 !important;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Task Management</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tasks</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <div class="search-box">
            <input type="text" class="form-control" id="taskSearch" placeholder="Search tasks...">
            <i class="ri-search-line search-icon"></i>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
            <i class="ri-add-line"></i> Add New
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="tasksTable">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th width="120">Status</th>
                                    <th width="180" class="pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr class="task-row">
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                    <td>
                                        <span class="badge
                                            @if($task->priority == 'high') bg-danger
                                            @elseif($task->priority == 'medium') bg-warning
                                            @else bg-success
                                            @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>

                                    <td>
                                        @foreach($task->assignments as $assignment)
                                            <span class="badge bg-primary me-1">
                                                {{ $assignment->user->name }}
                                                <form action="{{ route('admin.tasks.unassign', ['task' => $task->id, 'user' => $assignment->user_id]) }}"
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-white p-0 ms-1"
                                                            onclick="return confirm('Are you sure you want to unassign this task?')">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </form>
                                            </span>
                                        @endforeach
                                        <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                           data-bs-target="#assignModal" data-task-id="{{ $task->id }}">
                                            <i class="ri-user-add-line"></i>
                                        </a>
                                    </td>

                                    <td>
                                        <select class="form-select status-select" data-task-id="{{ $task->id }}">
                                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </td>
                                    <td class="table-actions text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.tasks.edit', $task->id) }}"
                                                class="btn btn-warning btn-sm action-btn-sm text-white">
                                                <i class="ri-edit-line"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-btn btn-sm action-btn-sm">
                                                    <i class="ri-delete-bin-line"></i> Delete
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

<!-- Assign Task Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select Volunteer</label>
                        <select class="form-select select2" id="user_id" name="user_id" required>
                            <option value="">Select Volunteer</option>
                            @foreach($volunteers as $volunteer)
                                <option value="{{ $volunteer->id }}">{{ $volunteer->name }} ({{ $volunteer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2();

    // Search functionality
    $('#taskSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.task-row').each(function() {
            const rowText = $(this).text().toLowerCase();
            if(rowText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Status change handler
    $('.status-select').change(function() {
        const taskId = $(this).data('task-id');
        const newStatus = $(this).val();

        $.ajax({
            url: `/admin/tasks/${taskId}/status`,
            method: 'POST',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'Error updating status');
                $(this).val($(this).data('previous-value'));
            }
        });
    });

    // Assign modal setup
    $('#assignModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var taskId = button.data('task-id');
        var form = $('#assignForm');
        form.attr('action', `/admin/tasks/${taskId}/assign`);

        // Initialize select2 in modal
        $('.select2').select2({
            dropdownParent: $('#assignModal')
        });
    });
});
</script>
@endpush
