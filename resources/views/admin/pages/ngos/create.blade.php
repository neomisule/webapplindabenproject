@extends('admin.layouts.main')

@push('title')
<title>Add NGO/Event - Admin Panel</title>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .recurrence-option {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }

    .day-checkbox {
        display: inline-block;
        margin-right: 15px;
        min-width: 100px;
    }

    .form-section {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 38px;
    }

    #staff_options {
        display: none;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Add NGO/Event</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ngos.index') }}">NGOs/Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ngos.store') }}">
                        @csrf

                        <div class="form-section">
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Event Name*</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="volunteers_needed" class="form-label">Volunteers Needed*</label>
                                    <input type="number" class="form-control" id="volunteers_needed"
                                        name="volunteers_needed" value="{{ old('volunteers_needed', 5) }}"
                                        min="1" required>
                                    @error('volunteers_needed')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" class="form-control" id="role" name="role"
                                            value="{{ old('role') }}">
                                        @error('role')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="program" class="form-label">Program</label>
                                        <input type="text" class="form-control" id="program" name="program"
                                            value="{{ old('program') }}">
                                        @error('program')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address*</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="mb-3">Date & Time</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="date" class="form-label">Start Date*</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ old('date') }}" required>
                                    @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="start_time" class="form-label">Start Time*</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time"
                                        value="{{ old('start_time', '09:00') }}" required>
                                    @error('start_time')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="end_time" class="form-label">End Time*</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time"
                                        value="{{ old('end_time', '17:00') }}" required>
                                    @error('end_time')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3">Staff Booking Options</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="for_staff"
                                        name="for_staff" value="1" {{ old('for_staff') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="for_staff">
                                        <strong>This event is exclusively for staff</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">
                            <h5 class="mb-3">Recurrence Options</h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_recurring"
                                            name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">
                                            <strong>This is a recurring event</strong>
                                        </label>
                                    </div>
                                </div>

                                <div id="recurrence_options" class="col-md-12 mb-3"
                                    style="{{ old('is_recurring') ? '' : 'display: none;' }}">
                                    <div class="recurrence-option">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Recurrence Pattern*</label>
                                                <select class="form-control" name="recurrence_pattern" id="recurrence_pattern">
                                                    <option value="daily">Daily</option>
                                                    <option value="weekly" selected>Weekly</option>
                                                    <option value="monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3" id="weekly_days">
                                                <label class="form-label">Repeat On*</label><br>
                                                @php
                                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                $oldDays = old('recurrence_days', [1]); // Default Monday
                                                @endphp
                                                @foreach($days as $index => $day)
                                                <div class="day-checkbox">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="recurrence_days[]" id="day_{{ strtolower($day) }}"
                                                        value="{{ $index + 1 }}"
                                                        {{ in_array($index + 1, $oldDays) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="day_{{ strtolower($day) }}">{{ $day }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Duration*</label>
                                                <select class="form-control" name="recurrence_duration">
                                                    <option value="3_months" selected>3 Months</option>
                                                    <option value="6_months">6 Months</option>
                                                    <option value="1_year">1 Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section" id="staff_options">
                            <h5 class="mb-3">Staff Booking Options</h5>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Pre-book Staff Members (Optional)</label>
                                    <select class="form-control select2" name="staff_ids[]" id="staff_ids" multiple>
                                        @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ in_array($staff->id, old('staff_ids', [])) ? 'selected' : '' }}>
                                            {{ $staff->name }} ({{ $staff->email }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Selected staff will be automatically booked for all recurring events</small>
                                    @error('staff_ids')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Save Event
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select staff members",
            allowClear: true
        });

        $('#is_recurring').change(function() {
            const isChecked = this.checked;
            $('#recurrence_options').toggle(isChecked);
            $('#staff_options').toggle(isChecked);

            if (!isChecked) {
                $('#for_staff').prop('checked', false);
                $('#staff_ids').val(null).trigger('change');
            }
        }).trigger('change');

        $('#recurrence_pattern').change(function() {
            const isWeekly = this.value === 'weekly';
            $('#weekly_days').toggle(isWeekly);
        }).trigger('change');

        $('#for_staff').change(function() {
            if (!this.checked) {
                $('#staff_ids').val(null).trigger('change');
            }
        });
    });
</script>
@endpush
