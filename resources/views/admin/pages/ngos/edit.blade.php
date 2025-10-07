@extends('admin.layouts.main')

@push('title')
<title>Edit NGO/Event - Admin Panel</title>
@endpush

@push('css')
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
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Edit NGO/Event</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ngos.index') }}">NGOs/Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ngos.update', $ngo->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-section">
                            <h5 class="mb-3">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Event Name*</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $ngo->name) }}" required>
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="volunteers_needed" class="form-label">Volunteers Needed*</label>
                                    <input type="number" class="form-control" id="volunteers_needed"
                                        name="volunteers_needed" value="{{ old('volunteers_needed', $ngo->volunteers_needed) }}"
                                        min="1" required>
                                    @error('volunteers_needed')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <input type="text" class="form-control" id="role" name="role"
                                            value="{{ old('role', $ngo->role) }}">
                                        @error('role')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="program" class="form-label">Program</label>
                                        <input type="text" class="form-control" id="program" name="program"
                                            value="{{ old('program', $ngo->program) }}">
                                        @error('program')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address*</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $ngo->address) }}</textarea>
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
                                        value="{{ old('date', $ngo->date) }}" required>
                                    @error('date')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="start_time" class="form-label">Start Time*</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time"
                                        value="{{ old('start_time', $ngo->start_time) }}" required>
                                    @error('start_time')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="end_time" class="form-label">End Time*</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time"
                                        value="{{ old('end_time', $ngo->end_time) }}" required>
                                    @error('end_time')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="mb-3">Recurrence Options</h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_recurring"
                                            name="is_recurring" value="1" {{ old('is_recurring', $ngo->is_recurring) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">
                                            <strong>This is a recurring event</strong>
                                        </label>
                                    </div>
                                </div>

                                <div id="recurrence_options" class="col-md-12 mb-3"
                                    style="{{ old('is_recurring', $ngo->is_recurring) ? '' : 'display: none;' }}">
                                    <div class="recurrence-option">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Recurrence Pattern*</label>
                                                <select class="form-control" name="recurrence_pattern" id="recurrence_pattern">
                                                    <option value="daily" {{ old('recurrence_pattern', $ngo->recurrence_pattern) == 'daily' ? 'selected' : '' }}>Daily</option>
                                                    <option value="weekly" {{ old('recurrence_pattern', $ngo->recurrence_pattern) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                    <option value="monthly" {{ old('recurrence_pattern', $ngo->recurrence_pattern) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3" id="weekly_days">
                                                <label class="form-label">Repeat On*</label><br>
                                                @php
                                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                $oldDays = old('recurrence_days', $ngo->recurrence_days ? json_decode($ngo->recurrence_days) : [1]);
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
                                                    <option value="3_months" {{ old('recurrence_duration', $ngo->recurrence_duration) == '3_months' ? 'selected' : '' }}>3 Months</option>
                                                    <option value="6_months" {{ old('recurrence_duration', $ngo->recurrence_duration) == '6_months' ? 'selected' : '' }}>6 Months</option>
                                                    <option value="1_year" {{ old('recurrence_duration', $ngo->recurrence_duration) == '1_year' ? 'selected' : '' }}>1 Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="mb-3">Additional Options</h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="for_staff"
                                            name="for_staff" value="1" {{ old('for_staff', $ngo->for_staff) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="for_staff">
                                            <strong>This event is for staff only</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Update Event
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
<script>
    document.getElementById('is_recurring').addEventListener('change', function() {
        document.getElementById('recurrence_options').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('recurrence_pattern').addEventListener('change', function() {
        const isWeekly = this.value === 'weekly';
        document.getElementById('weekly_days').style.display = isWeekly ? 'block' : 'none';
    });

    document.addEventListener('DOMContentLoaded', function() {
        const initialPattern = document.getElementById('recurrence_pattern').value;
        document.getElementById('weekly_days').style.display = initialPattern === 'weekly' ? 'block' : 'none';
    });

</script>
@endpush
