@extends('admin.layouts.main')

@push('title')
<title>Book Slot for Volunteer - Admin Panel</title>
@endpush

@push('css')
<style>
    :root {
        --primary-color: #2e7d32;
        --primary-light: #4caf50;
        --primary-dark: #1b5e20;
    }
    .volunteer-details-card {
        border-left: 4px solid var(--primary-color);
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .volunteer-details-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    .booking-form-card {
        border-left: 4px solid var(--primary-light);
        border-radius: 4px;
    }
    .booking-form-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-dark);
    }
    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }
    .slot-info {
        font-size: 0.9rem;
        color: #6c757d;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-top: 10px;
    }
    .slot-available {
        color: var(--primary-color);
        font-weight: bold;
    }
    .slot-full {
        color: #dc3545;
        font-weight: bold;
    }
    .staff-event {
        background-color: #fff3cd;
    }
    .volunteer-event {
        background-color: #e7f6e7;
    }
    /* Custom select with search */
    .searchable-select {
        position: relative;
        width: 100%;
    }
    .searchable-select .select-display {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: white;
        cursor: pointer;
        height: 38px;
        display: flex;
        align-items: center;
    }
    .searchable-select .options-container {
        display: none;
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: white;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-top: 5px;
    }
    .searchable-select .option {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        flex-direction: column;
    }
    .searchable-select .option:hover {
        background-color: #f8f9fa;
    }
    .searchable-select .option.active {
        background-color: var(--primary-light);
        color: white;
    }
    .searchable-select .search-input {
        width: calc(100% - 24px);
        padding: 8px 12px;
        border: none;
        border-bottom: 1px solid #ced4da;
        margin: 0;
        border-radius: 4px 4px 0 0;
    }
    .searchable-select .no-results {
        padding: 8px 12px;
        color: #6c757d;
        font-style: italic;
    }
    .event-date {
        font-weight: bold;
        color: var(--primary-dark);
        font-size: 0.85rem;
    }
    .event-type {
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 3px;
        font-size: 0.75em;
        width: fit-content;
        margin-top: 3px;
    }
    .staff-badge {
        background-color: #ffc107;
        color: #856404;
    }
    .volunteer-badge {
        background-color: #28a745;
        color: white;
    }
    .event-slots {
        font-weight: bold;
        font-size: 0.85rem;
    }
    .available-slots {
        color: var(--primary-color);
    }
    .full-slots {
        color: #dc3545;
    }
    .event-details-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 3px;
    }
    .event-name {
        font-weight: 600;
        margin-bottom: 3px;
    }
    .highlight {
        background-color: #fffde7;
        font-weight: bold;
    }
    .search-hints {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
    }
</style>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Book Slot for Volunteer</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.volunteers.index') }}">Volunteers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Book Slot</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card volunteer-details-card">
                <div class="volunteer-details-header">
                    <h5 class="mb-0"><i class="ri-user-3-line me-2"></i>Volunteer Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded me-3">
                                    <i class="ri-user-line text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Name</p>
                                    <h6 class="mb-0">{{ $volunteer->name }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded me-3">
                                    <i class="ri-mail-line text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Email</p>
                                    <h6 class="mb-0">{{ $volunteer->email }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded me-3">
                                    <i class="ri-phone-line text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted">Phone</p>
                                    <h6 class="mb-0">{{ $volunteer->phone_number ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card booking-form-card">
                <div class="booking-form-header">
                    <h5 class="mb-0"><i class="ri-calendar-event-line me-2"></i>Book Event Slot</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.volunteers.store-booking', $volunteer->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="ngo_id" class="form-label">Select Event <span class="text-danger">*</span></label>
                            <div class="searchable-select" id="custom-select">
                                <div class="select-display" id="select-display">-- Type to search events --</div>
                                <div class="options-container" id="options-container">
                                    <input type="text" class="search-input" placeholder="Search by event name, date or type..." id="search-input">
                                    <div class="search-hints px-2">Try: "food drive", "15 aug", or "volunteer"</div>
                                    <div id="options-list"></div>
                                </div>
                                <select name="ngo_id" id="ngo_id" style="display: none;">
                                    <option value="">-- Select an event --</option>
                                    @foreach($ngos as $ngo)
                                    @php
                                        $bookedCount = \App\Models\VolunteerBooking::where('ngo_id', $ngo->id)
                                            ->whereIn('status', ['booked', 'checked_in'])
                                            ->count();
                                        $availableSlots = max(0, $ngo->volunteers_needed - $bookedCount);
                                    @endphp
                                    <option value="{{ $ngo->id }}"
                                        data-available="{{ $availableSlots }}"
                                        data-date="{{ date('d M Y', strtotime($ngo->date)) }}"
                                        data-start-time="{{ $ngo->start_time }}"
                                        data-end-time="{{ $ngo->end_time }}"
                                        data-total="{{ $ngo->volunteers_needed }}"
                                        data-for-staff="{{ $ngo->for_staff }}"
                                        data-role="{{ $ngo->role }}"
                                        data-program="{{ $ngo->program }}"
                                        data-address="{{ $ngo->address }}"
                                        class="{{ $ngo->for_staff ? 'staff-event' : 'volunteer-event' }}">
                                        {{ $ngo->name }}|{{ date('d M Y', strtotime($ngo->date)) }}|{{ $ngo->for_staff ? 'STAFF' : 'VOLUNTEER' }}|{{ $availableSlots }}/{{ $ngo->volunteers_needed }} slots
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2 slot-info" id="slot-details">
                                <div class="text-muted">Select an event to view details</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-primary px-4" id="book-button">
                                    <i class="ri-bookmark-line me-2"></i> Book Slot
                                </button>
                                <a href="{{ route('admin.volunteers.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="ri-arrow-go-back-line me-2"></i> Back to Volunteers
                                </a>
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
    document.addEventListener('DOMContentLoaded', function() {
        const customSelect = document.getElementById('custom-select');
        const selectDisplay = document.getElementById('select-display');
        const optionsContainer = document.getElementById('options-container');
        const searchInput = document.getElementById('search-input');
        const optionsList = document.getElementById('options-list');
        const hiddenSelect = document.getElementById('ngo_id');
        const slotDetails = document.getElementById('slot-details');
        const bookButton = document.getElementById('book-button');

        function highlightText(text, searchTerm) {
            if (!searchTerm) return text;

            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        function initOptions() {
            optionsList.innerHTML = '';
            const options = hiddenSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value) {
                    const optionElement = document.createElement('div');
                    optionElement.className = `option ${option.classList.contains('staff-event') ? 'staff-event' : 'volunteer-event'}`;

                    // Parse the option value
                    const parts = option.text.split('|');
                    const eventName = parts[0].trim();
                    const eventDate = parts[1].trim();
                    const eventType = parts[2].trim();
                    const eventSlots = parts[3].trim();

                    // Create structured HTML for the option
                    optionElement.innerHTML = `
                        <div class="event-name">${eventName}</div>
                        <div class="event-details-row">
                            <span class="event-date">${eventDate}</span>
                            <span class="event-type ${eventType === 'STAFF' ? 'staff-badge' : 'volunteer-badge'}">
                                ${eventType}
                            </span>
                            <span class="event-slots ${eventSlots.startsWith('0/') ? 'full-slots' : 'available-slots'}">
                                ${eventSlots}
                            </span>
                        </div>
                    `;

                    optionElement.dataset.value = option.value;

                    optionElement.addEventListener('click', function() {
                        hiddenSelect.value = this.dataset.value;
                        selectDisplay.innerHTML = this.innerHTML;
                        optionsContainer.style.display = 'none';
                        updateSlotDetails();
                    });

                    optionsList.appendChild(optionElement);
                }
            });
        }

        function filterOptions() {
            const searchTerm = searchInput.value.toLowerCase();
            const options = optionsList.querySelectorAll('.option');
            let hasVisibleOptions = false;

            options.forEach(option => {
                // Get all searchable text content
                const eventName = option.querySelector('.event-name')?.textContent.toLowerCase() || '';
                const eventDate = option.querySelector('.event-date')?.textContent.toLowerCase() || '';
                const eventType = option.querySelector('.event-type')?.textContent.toLowerCase() || '';

                // Search in all fields
                if (eventName.includes(searchTerm) ||
                    eventDate.includes(searchTerm) ||
                    eventType.includes(searchTerm)) {

                    // Highlight matching text
                    if (searchTerm) {
                        const nameElement = option.querySelector('.event-name');
                        const dateElement = option.querySelector('.event-date');

                        nameElement.innerHTML = highlightText(nameElement.textContent, searchTerm);
                        dateElement.innerHTML = highlightText(dateElement.textContent, searchTerm);
                    }

                    option.style.display = 'block';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            const noResults = document.getElementById('no-results');
            if (!hasVisibleOptions) {
                if (!noResults) {
                    const noResultsElement = document.createElement('div');
                    noResultsElement.className = 'no-results';
                    noResultsElement.id = 'no-results';
                    noResultsElement.textContent = 'No matching events found';
                    optionsList.appendChild(noResultsElement);
                }
            } else if (noResults) {
                noResults.remove();
            }
        }

        function updateSlotDetails() {
            const selectedOption = hiddenSelect.options[hiddenSelect.selectedIndex];
            if (!selectedOption || selectedOption.value === '') {
                slotDetails.innerHTML = '<div class="text-muted">Select an event to view details</div>';
                bookButton.disabled = true;
                return;
            }

            const availableSlots = parseInt(selectedOption.getAttribute('data-available'));
            const eventDate = selectedOption.getAttribute('data-date');
            const startTime = selectedOption.getAttribute('data-start-time');
            const endTime = selectedOption.getAttribute('data-end-time');
            const totalSlots = selectedOption.getAttribute('data-total');
            const forStaff = selectedOption.getAttribute('data-for-staff') === '1';
            const role = selectedOption.getAttribute('data-role');
            const program = selectedOption.getAttribute('data-program');
            const address = selectedOption.getAttribute('data-address');

            let slotHTML = `
                <div class="mb-2">
                    <strong>Event:</strong> ${selectedOption.text.split('|')[0].trim()}
                </div>
                <div class="mb-2">
                    <strong>Date:</strong> ${eventDate}
                </div>
                <div class="mb-2">
                    <strong>Time:</strong> ${startTime} - ${endTime}
                </div>
                <div class="mb-2">
                    <strong>Address:</strong> ${address}
                </div>
                <div class="mb-2">
                    <strong>Role:</strong> ${role}
                </div>
                <div class="mb-2">
                    <strong>Program:</strong> ${program}
                </div>
                <div class="mb-2">
                    <strong>Type:</strong> <span class="${forStaff ? 'text-danger' : 'text-success'}">${forStaff ? 'STAFF ONLY' : 'VOLUNTEER EVENT'}</span>
                </div>
                <div class="mb-2">
                    <strong>Availability:</strong> `;

            if (availableSlots > 0) {
                slotHTML += `<span class="slot-available">${availableSlots} slots available</span> out of ${totalSlots}`;
            } else {
                slotHTML += `<span class="slot-full">No slots available</span> (${totalSlots} total)`;
            }

            slotHTML += `</div>`;

            slotDetails.innerHTML = slotHTML;

            // Disable book button if no slots available or event is for staff
            if (availableSlots <= 0 || forStaff) {
                bookButton.disabled = true;
                if (availableSlots <= 0) {
                    slotHTML += `<div class="alert alert-warning mt-2 p-2">This event has no available slots left!</div>`;
                }
                if (forStaff) {
                    slotHTML += `<div class="alert alert-danger mt-2 p-2">This is a staff-only event!</div>`;
                }
                slotDetails.innerHTML = slotHTML;
            } else {
                bookButton.disabled = false;
            }
        }

        // Toggle dropdown
        selectDisplay.addEventListener('click', function(e) {
            e.stopPropagation();
            optionsContainer.style.display = optionsContainer.style.display === 'block' ? 'none' : 'block';
            if (optionsContainer.style.display === 'block') {
                searchInput.focus();
            }
        });

        // Search functionality
        searchInput.addEventListener('input', filterOptions);

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            optionsContainer.style.display = 'none';
        });

        // Prevent dropdown close when clicking inside
        optionsContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Initialize
        initOptions();
        updateSlotDetails();

        // Handle select change
        hiddenSelect.addEventListener('change', function() {
            const selectedOption = hiddenSelect.options[hiddenSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                // Create display version similar to the options
                const parts = selectedOption.text.split('|');
                const eventName = parts[0].trim();
                const eventDate = parts[1].trim();
                const eventType = parts[2].trim();
                const eventSlots = parts[3].trim();

                selectDisplay.innerHTML = `
                    <div class="event-name">${eventName}</div>
                    <div class="event-details-row">
                        <span class="event-date">${eventDate}</span>
                        <span class="event-type ${eventType === 'STAFF' ? 'staff-badge' : 'volunteer-badge'}">
                            ${eventType}
                        </span>
                        <span class="event-slots ${eventSlots.startsWith('0/') ? 'full-slots' : 'available-slots'}">
                            ${eventSlots}
                        </span>
                    </div>
                `;
            }
            updateSlotDetails();
        });
    });
</script>
@endpush
