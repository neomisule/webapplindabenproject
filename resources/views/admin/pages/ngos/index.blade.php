@extends('admin.layouts.main')

@push('title')
<title>Slot Management - LindaBen CMS</title>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Base styles */
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

    /* Table styles */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th,
    td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    tbody tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Date range filter styles */
    .date-range-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-range-filter {
        position: relative;
    }

    .date-range-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1;
        min-width: 300px;
        padding: 15px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .date-range-dropdown.show {
        display: block;
    }

    .date-range-options {
        list-style: none;
        padding: 0;
        margin: 0 0 15px 0;
    }

    .date-range-options li {
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 0.25rem;
        transition: background-color 0.2s;
    }

    .date-range-options li:hover {
        background-color: #f8f9fa;
    }

    .date-range-options li.active {
        background-color: #e9ecef;
        font-weight: 500;
    }

    .custom-range-inputs {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .custom-range-inputs .form-control {
        flex: 1;
        min-width: 0;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .clear-range-btn {
        margin-top: 15px;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .clear-filter-btn {
        display: none;
        padding: 0.375rem;
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
    }

    .clear-filter-btn:hover {
        color: #bb2d3b;
    }

    .flatpickr-calendar {
        z-index: 1071 !important;
        width: auto !important;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 38px;
        padding: 5px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: white;
        padding: 0 5px;
        margin-top: 3px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255, 255, 255, 0.7);
        margin-right: 5px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: white;
    }

    .select2-container .select2-search--inline .select2-search__field {
        margin-top: 5px;
    }

    .table-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Slot Management</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Events</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <form id="statusFilterForm" method="GET" action="{{ url()->current() }}" class="d-inline-block">
            <select id="statusFilter" name="status_filter" class="form-select">
                <option value="upcoming" {{ request('status_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="complete" {{ request('status_filter') == 'complete' ? 'selected' : '' }}>Complete</option>
                <option value="" {{ request('status_filter') == '' ? 'selected' : '' }}>All</option>
            </select>

            @foreach(request()->except('status_filter', 'page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
        <div class="search-box">
            <input type="text" class="form-control" id="slotSearch" placeholder="Search slots...">
            <i class="ri-search-line search-icon"></i>
        </div>
        <div class="date-range-container">
            <div class="date-range-filter">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dateRangeDropdownBtn">
                    <i class="ri-calendar-line"></i>
                    @if(request('date_range') == 'today')
                    Today
                    @elseif(request('date_range') == 'tomorrow')
                    Tomorrow
                    @elseif(request('date_range') == 'this_week')
                    This Week
                    @elseif(request('date_range') == 'next_week')
                    Next Week
                    @elseif(request('date_range') == 'this_month')
                    This Month
                    @elseif(request('date_range') == 'next_month')
                    Next Month
                    @elseif(request('date_range') == 'custom' && request('start_date') && request('end_date'))
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d M') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M') }}
                    @else
                    Date Filter
                    @endif
                </button>
                <div class="date-range-dropdown" id="dateRangeDropdown">
                    <ul class="date-range-options">
                        <li data-range="today" class="{{ request('date_range') == 'today' ? 'active' : '' }}">Today</li>
                        <li data-range="tomorrow" class="{{ request('date_range') == 'tomorrow' ? 'active' : '' }}">Tomorrow</li>
                        <li data-range="this_week" class="{{ request('date_range') == 'this_week' ? 'active' : '' }}">This Week</li>
                        <li data-range="next_week" class="{{ request('date_range') == 'next_week' ? 'active' : '' }}">Next Week</li>
                        <li data-range="this_month" class="{{ request('date_range') == 'this_month' ? 'active' : '' }}">This Month</li>
                        <li data-range="next_month" class="{{ request('date_range') == 'next_month' ? 'active' : '' }}">Next Month</li>
                        <li data-range="custom" class="{{ request('date_range') == 'custom' ? 'active' : '' }}">Custom Range</li>
                    </ul>
                    <div class="custom-range-inputs" id="customRangeInputs" style="{{ request('date_range') == 'custom' ? 'display:flex;' : 'display:none;' }}">
                        <input type="text" class="form-control datepicker" id="startDate" name="start_date"
                            value="{{ request('start_date') ?? '' }}" placeholder="Start Date" data-input>
                        <input type="text" class="form-control datepicker" id="endDate" name="end_date"
                            value="{{ request('end_date') ?? '' }}" placeholder="End Date" data-input>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm clear-range-btn w-100 mt-2" id="clearRangeBtn">Clear Filter</button>
                </div>
            </div>
            <button class="clear-filter-btn" id="clearFilterBtn" title="Clear date filter">
                <i class="ri-close-circle-fill fs-5"></i>
            </button>
        </div>
        <a href="{{ route('admin.ngos.create') }}" class="btn btn-primary">
            <i class="ri-add-line"></i> Add New
        </a>
    </div>
</div>

<div class="mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="slotsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Program</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Volunteers Needed</th>
                                    <th width="120">Status</th>
                                    <th width="220" class="pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ngos as $slot)
                                <tr class="slot-row" data-date="{{ strtotime($slot->date) }}">
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td>{{ $slot->name }}</td>
                                    <td>{{ $slot->role }}</td>
                                    <td>{{ $slot->program }}</td>
                                    <td>{{ date('d M Y', strtotime($slot->date)) }}</td>
                                    <td>{{ date('h:i A', strtotime($slot->start_time)) }} - {{ date('h:i A', strtotime($slot->end_time)) }}</td>
                                    <td>{{ $slot->volunteers_needed }}</td>
                                    <td>
                                        <span class="badge bg-{{ $slot->status ? 'success' : 'secondary' }}">
                                            {{ $slot->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="table-actions text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.ngos.tasks', $slot->id) }}"
                                                class="btn btn-secondary btn-sm"
                                                title="View and manage tasks">
                                                <i class="ri-task-line"></i> View Tasks
                                            </a>
                                            <button class="btn btn-info btn-sm book-staff-btn"
                                                data-slot-id="{{ $slot->id }}"
                                                data-slot-name="{{ $slot->name }}"
                                                data-slot-date="{{ $slot->date }}"
                                                data-slot-start-time="{{ $slot->start_time }}"
                                                data-slot-end-time="{{ $slot->end_time }}">
                                                <i class="ri-user-add-line"></i> Book
                                            </button>
                                            <a href="{{ route('admin.ngos.edit', $slot->id) }}"
                                                class="btn btn-warning btn-sm text-white">
                                                <i class="ri-edit-line"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.ngos.destroy', $slot->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger delete-btn btn-sm">
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

<!-- Book Staff Modal -->
<div class="modal fade" id="bookStaffModal" tabindex="-1" aria-labelledby="bookStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookStaffModalLabel">Book Volunteer for -> <span id="modalNgoName" class="text-danger"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookStaffForm" method="POST" action="{{ route('admin.ngos.bookStaff') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ngo_id" id="modalNgoId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="text" class="form-control" id="modalNgoDate" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Time</label>
                            <input type="text" class="form-control" id="modalNgoTime" readonly>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="bookForRecurring" name="is_recurring" value="1">
                        <label class="form-check-label" for="bookForRecurring">
                            Book for all recurring events of this type
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Select Members</label>
                        <select class="form-control select2-staff" name="staff_ids[]" id="staffSelect" multiple="multiple">
                            @foreach($members as $members)
                            <option value="{{ $members->id }}">{{ $members->name }} ({{ $members->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Book</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.getElementById('statusFilter').addEventListener('change', function() {
        document.getElementById('statusFilterForm').submit();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#staffSelect').select2({
            placeholder: "Select Staff Members",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#bookStaffModal') // Important for modals
        });
        const datepickerOptions = {
            dateFormat: 'Y-m-d',
            allowInput: true,
            static: false,
            position: 'auto',
            appendTo: document.body,
            onOpen: function(selectedDates, dateStr, instance) {
                document.querySelectorAll('.flatpickr-input:not(#' + instance.element.id + ')').forEach(input => {
                    if (input._flatpickr) input._flatpickr.close();
                });
                $('#dateRangeDropdown').addClass('show');
                setTimeout(() => {
                    instance._positionCalendar();
                }, 10);
            }
        };

        const startDatePicker = flatpickr('#startDate', datepickerOptions);
        const endDatePicker = flatpickr('#endDate', datepickerOptions);

        const dateRangeDropdownBtn = document.getElementById('dateRangeDropdownBtn');
        const dateRangeDropdown = document.getElementById('dateRangeDropdown');
        const dateRangeOptions = document.querySelectorAll('.date-range-options li');
        const customRangeInputs = document.getElementById('customRangeInputs');
        const clearRangeBtn = document.getElementById('clearRangeBtn');
        const clearFilterBtn = document.getElementById('clearFilterBtn');
        const slotRows = document.querySelectorAll('.slot-row');

        const urlParams = new URLSearchParams(window.location.search);
        const hasDateFilter = urlParams.has('date_range') ||
            (urlParams.has('start_date') && urlParams.has('end_date'));

        if (hasDateFilter) {
            clearFilterBtn.style.display = 'block';
        }

        dateRangeDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dateRangeDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dateRangeDropdown.contains(e.target) && e.target !== dateRangeDropdownBtn) {
                dateRangeDropdown.classList.remove('show');
            }
        });

        dateRangeOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const range = this.getAttribute('data-range');

                dateRangeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                if (range === 'custom') {
                    customRangeInputs.style.display = 'flex';
                    $('#dateRangeDropdown').addClass('show');
                    setTimeout(() => {
                        document.getElementById('startDate').focus();
                    }, 100);
                } else {
                    customRangeInputs.style.display = 'none';
                    submitFilterForm(range);
                }
            });
        });

        document.querySelectorAll('#startDate, #endDate').forEach(input => {
            input.addEventListener('change', function(e) {
                e.stopPropagation();

                if (startDatePicker.selectedDates[0] && endDatePicker.selectedDates[0]) {
                    submitFilterForm('custom');
                }
            });
        });

        clearRangeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            clearDateFilter();
        });

        clearFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearDateFilter();
        });

        function clearDateFilter() {
            dateRangeOptions.forEach(opt => opt.classList.remove('active'));
            customRangeInputs.style.display = 'none';
            startDatePicker.clear();
            endDatePicker.clear();
            clearFilterBtn.style.display = 'none';
            window.location.href = window.location.pathname;
        }

        function submitFilterForm(range) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = window.location.pathname;

            if (range === 'custom' && startDatePicker.selectedDates[0] && endDatePicker.selectedDates[0]) {
                const startInput = document.createElement('input');
                startInput.type = 'hidden';
                startInput.name = 'start_date';
                startInput.value = startDatePicker.selectedDates[0].toISOString().split('T')[0];
                form.appendChild(startInput);

                const endInput = document.createElement('input');
                endInput.type = 'hidden';
                endInput.name = 'end_date';
                endInput.value = endDatePicker.selectedDates[0].toISOString().split('T')[0];
                form.appendChild(endInput);
            }

            const rangeInput = document.createElement('input');
            rangeInput.type = 'hidden';
            rangeInput.name = 'date_range';
            rangeInput.value = range;
            form.appendChild(rangeInput);

            document.body.appendChild(form);
            form.submit();

            clearFilterBtn.style.display = range ? 'block' : 'none';
        }

        const searchInput = document.getElementById('slotSearch');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.slot-row');

            rows.forEach(row => {
                if (row.style.display === 'none') return;

                let matchFound = false;
                const columns = row.querySelectorAll('td:not(.table-actions)');

                columns.forEach(column => {
                    if (column.textContent.toLowerCase().includes(searchTerm)) {
                        matchFound = true;
                    }
                });

                row.style.display = matchFound ? '' : 'none';
            });
        });

        document.querySelectorAll('.book-staff-btn').forEach(button => {
            button.addEventListener('click', function() {
                const slotId = this.getAttribute('data-slot-id');
                const slotName = this.getAttribute('data-slot-name');
                const slotDate = this.getAttribute('data-slot-date');
                const startTime = this.getAttribute('data-slot-start-time');
                const endTime = this.getAttribute('data-slot-end-time');

                document.getElementById('modalNgoName').textContent = slotName;
                document.getElementById('modalNgoId').value = slotId;
                document.getElementById('modalNgoDate').value = new Date(slotDate).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    weekday: 'long'
                });
                document.getElementById('modalNgoTime').value =
                    formatTime(startTime) + ' - ' + formatTime(endTime);

                const bookStaffModal = new bootstrap.Modal(document.getElementById('bookStaffModal'));
                bookStaffModal.show();
            });
        });

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const date = new Date();
            date.setHours(hours);
            date.setMinutes(minutes);
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
    @if(session('toast'))
        @if(session('toast.type') === 'error')
            Swal.fire({
                icon: 'error',
                title: '{{ session('toast.title') }}',
                text: '{{ session('toast.message') }}',
                confirmButtonText: 'OK'
            });
        @elseif(session('toast.type') === 'success')
            Swal.fire({
                icon: 'success',
                title: '{{ session('toast.title') }}',
                text: '{{ session('toast.message') }}',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
    @endif
});

</script>
@endpush
