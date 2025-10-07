@extends('admin.layouts.main')

@push('title')
<title>Task Management - {{ $ngo->name }} | LindaBen CMS</title>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .task-card {
        border-left: 4px solid #007bff;
        transition: all 0.3s;
    }
    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .priority-high { border-left-color: #dc3545; }
    .priority-medium { border-left-color: #ffc107; }
    .priority-low { border-left-color: #28a745; }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .user-avatar-sm {
        width: 24px;
        height: 24px;
    }

    .assignment-card {
        border-left: 3px solid #6c757d;
    }
    .assignment-status-pending { border-left-color: #6c757d; }
    .assignment-status-in_progress { border-left-color: #17a2b8; }
    .assignment-status-completed { border-left-color: #28a745; }

    /* Select2 with avatars */
    .select2-container .select2-selection--multiple {
        min-height: 38px;
        padding-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        display: flex;
        align-items: center;
    }
    .select2-results__option {
        display: flex;
        align-items: center;
        padding: 8px 12px;
    }
    .select2-results__option img {
        margin-right: 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        order: 2;
        margin-left: 5px;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Task Management: {{ $ngo->name }}</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ngos.index') }}">NGOs</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tasks</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-primary btn-sm assign-tasks-btn"
            data-slot-id="{{ $ngo->id }}"
            data-slot-name="{{ $ngo->name }}">
            <i class="ri-task-line"></i> Assign Tasks
        </button>
        <a href="{{ route('admin.ngos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ri-arrow-left-line"></i> Back to NGOs
        </a>
    </div>
</div>

<div class="mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="border-0 shadow-sm">
                <div class="card-header mb-3 border-bottom">
                    <h5 class="mb-0">Tasks for {{ $ngo->name }}</h5>
                </div>
                <div class="card-body">
                    @if($tasks->isEmpty())
                    <div class="alert alert-info">No tasks found for this NGO.</div>
                    @else
                    @foreach($tasks as $task)
                    <div class="card task-card mb-3 priority-{{ $task->priority }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title">{{ $task->title }}</h5>
                                    <p class="card-text text-muted">{{ $task->description }}</p>
                                    <div class="d-flex gap-3">
                                        <span class="badge bg-{{
                                                $task->priority == 'high' ? 'danger' :
                                                ($task->priority == 'medium' ? 'warning' : 'success')
                                            }}">
                                            {{ ucfirst($task->priority) }} Priority
                                        </span>
                                        <span class="badge bg-secondary">
                                            {{ $task->assignments->count() }} Assignments
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary assign-user-btn"
                                        data-task-id="{{ $task->id }}"
                                        data-task-title="{{ $task->title }}">
                                        <i class="ri-user-add-line"></i> Assign Users
                                    </button>
                                </div>
                            </div>

                            @if($task->assignments->isNotEmpty())
                            <div class="mt-3">
                                <h6 class="mb-2">Assigned Users:</h6>
                                <div class="row">
                                    @foreach($task->assignments as $assignment)
                                    <div class="col-md-6 mb-2">
                                        <div class="card assignment-card assignment-status-{{ $assignment->status }}">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $assignment->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.$assignment->user->name }}"
                                                            alt="{{ $assignment->user->name }}"
                                                            class="user-avatar">
                                                        <div>
                                                            <h6 class="mb-0">{{ $assignment->user->name }}</h6>
                                                            <small class="text-muted">{{ $assignment->user->email }}</small>
                                                            <div>
                                                                <span class="badge bg-{{
                                                                        $assignment->status == 'completed' ? 'success' :
                                                                        ($assignment->status == 'in_progress' ? 'info' : 'secondary')
                                                                    }}">
                                                                    {{ str_replace('_', ' ', $assignment->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('admin.ngos.task-assignments.destroy', $assignment->id) }}"
                                                            method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to remove this assignment?')">
                                                                <i class="ri-user-unfollow-line"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <!-- User Statistics Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>User Task Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Total Tasks</th>
                                    <th>Pending</th>
                                    <th>In Progress</th>
                                    <th>Completed</th>
                                    <th>Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userStats as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.$user->name }}"
                                                 alt="{{ $user->name }}"
                                                 class="user-avatar-sm">
                                            <div>
                                                <div>{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->total_tasks }}</td>
                                    <td>{{ $user->pending_tasks }}</td>
                                    <td>{{ $user->in_progress_tasks }}</td>
                                    <td>{{ $user->completed_tasks }}</td>
                                    <td>
                                        @if($user->total_tasks > 0)
                                            {{ round(($user->completed_tasks / $user->total_tasks) * 100) }}%
                                        @else
                                            N/A
                                        @endif
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

<!-- Assign User Modal -->
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserModalLabel">Assign Users to <span id="modalTaskTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignUserForm" method="POST" action="{{ route('admin.ngos.assignTaskToUser', $ngo->id) }}">
                @csrf
                <input type="hidden" name="task_id" id="modalTaskId">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Users</label>
                        <select class="form-control select2-users" name="user_ids[]" multiple="multiple" required>
                            @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}"
                                    data-avatar="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.$user->name }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="submit-text">Assign Users</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Task Modal -->
<div class="modal fade" id="assignTasksModal" tabindex="-1" aria-labelledby="assignTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTasksModalLabel">Assign Tasks to <span id="modalSlotName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignTasksForm" method="POST" action="{{ route('admin.ngos.assignTask') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ngo_id" id="modalSlotId">

                    <div class="form-group mb-3">
                        <label class="form-label">Select Tasks</label>
                        <select class="form-control select2-tasks" name="task_ids[]" id="tasksSelect" multiple="multiple" required>
                            @foreach($alltask as $task)
                                <option value="{{ $task->id }}">{{ $task->title }} ({{ ucfirst($task->priority) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="submit-text">Assign Tasks</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize select2 for users with avatars
    $('.select2-users').select2({
        dropdownParent: $('#assignUserModal'),
        templateResult: formatUser,
        templateSelection: formatUserSelection,
        width: '100%',
        placeholder: "Select users",
        allowClear: true
    });

    function formatUser(user) {
        if (!user.id) return user.text;
        const $avatar = $('<img>', {
            class: 'user-avatar-sm me-2',
            src: user.element.getAttribute('data-avatar'),
            width: 24,
            height: 24
        });
        return $('<span>').append($avatar).append(user.text);
    }

    function formatUserSelection(user) {
        if (!user.id) return user.text;
        const $avatar = $('<img>', {
            class: 'user-avatar-sm me-2',
            src: user.element.getAttribute('data-avatar'),
            width: 20,
            height: 20
        });
        return $('<span>').append($avatar).append(user.text);
    }

    // Initialize select2 for tasks
    $('.select2-tasks').select2({
        placeholder: "Select tasks",
        allowClear: true,
        dropdownParent: $('#assignTasksModal'),
        width: '100%'
    });

    // Handle assign user button click
    document.querySelectorAll('.assign-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.getAttribute('data-task-id');
            const taskTitle = this.getAttribute('data-task-title');

            document.getElementById('modalTaskTitle').textContent = taskTitle;
            document.getElementById('modalTaskId').value = taskId;

            // Reset the form and select2
            $('#assignUserForm')[0].reset();
            $('.select2-users').val(null).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('assignUserModal'));
            modal.show();
        });
    });

    // Handle assign tasks button click
    document.querySelectorAll('.assign-tasks-btn').forEach(button => {
        button.addEventListener('click', function() {
            const slotId = this.getAttribute('data-slot-id');
            const slotName = this.getAttribute('data-slot-name');

            document.getElementById('modalSlotName').textContent = slotName;
            document.getElementById('modalSlotId').value = slotId;

            // Reset the form and select2
            $('#assignTasksForm')[0].reset();
            $('.select2-tasks').val(null).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('assignTasksModal'));
            modal.show();
        });
    });

    // Handle assign user form submission
    document.getElementById('assignUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        submitBtn.disabled = true;
        submitText.textContent = 'Assigning...';
        spinner.classList.remove('d-none');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message and reload
                toastr.success(data.message);
                $('#assignUserModal').modal('hide');
                window.location.reload();
            } else {
                toastr.error(data.message || 'Failed to assign users');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while assigning users');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitText.textContent = 'Assign Users';
            spinner.classList.add('d-none');
        });
    });

    // Handle assign tasks form submission
    document.getElementById('assignTasksForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        submitBtn.disabled = true;
        submitText.textContent = 'Assigning...';
        spinner.classList.remove('d-none');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message and reload
                toastr.success(data.message);
                $('#assignTasksModal').modal('hide');
                window.location.reload();
            } else {
                toastr.error(data.message || 'Failed to assign tasks');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while assigning tasks');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitText.textContent = 'Assign Tasks';
            spinner.classList.add('d-none');
        });
    });
});
</script>
@endpush
