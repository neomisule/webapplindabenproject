@extends('roles.volunteer.layouts.main')

@section('main-section')
<div class="container-fluid py-3">
    <!-- Breadcrumb Section -->
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h3 class="dark">Volunteer Check-In</h3>
                <nav>
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('volunteer.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Check-In</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('volunteer.bookings') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-arrow-left-line me-1"></i> Back to Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row justify-content-center mt-3">
        <div class="col-xl-6 col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border: 1px solid rgba(46, 94, 47, 0.2);">
                <!-- Card Header -->
                <div class="card-header py-3" style="background-color: #f8f9fa; border-bottom: 1px solid rgba(46, 94, 47, 0.1);">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-white p-2 rounded-circle shadow-sm" style="border: 1px solid #2e5e2f;">
                            <i class="ri-login-circle-line fs-4" style="color: #2e5e2f;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" style="color: #2e5e2f;">Volunteer Check-In</h5>
                            <p class="mb-0 text-muted small">Confirm your attendance at the organization</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body px-4 py-4">
                    <form id="checkinForm" action="{{ route('volunteer.checkin.process') }}" method="POST">
                        @csrf

                        <!-- Booking Code Field -->
                        <div class="mb-4">
                            <label for="booking_code" class="form-label fw-semibold d-flex align-items-center">
                                <i class="ri-barcode-line me-2" style="color: #2e5e2f;"></i>
                                Booking Code
                            </label>

                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-ticket-2-line" style="color: #2e5e2f;"></i>
                                </span>
                                <input type="text"
                                    class="form-control form-control-lg"
                                    id="booking_code"
                                    name="booking_code"
                                    placeholder="Enter your booking code"
                                    required>
                            </div>

                            <div class="mt-2 text-muted small d-flex align-items-start">
                                <i class="ri-information-line me-2 mt-1" style="color: #6c757d;"></i>
                                <span>You'll find this code at your volunteer location or in your booking confirmation</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg py-3 text-white fw-semibold" style="background-color: #2e5e2f;">
                                <i class="ri-checkbox-circle-line me-2"></i> Check In Now
                                <i class="ri-arrow-right-line ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light py-3" style="border-radius: 0 0 12px 12px;">
                    <div class="d-flex align-items-center">
                        <i class="ri-alert-line me-2" style="color: #2e5e2f;"></i>
                        <p class="mb-0 small text-muted">
                            Please check in only when you physically arrive at the organization's location
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkinForm');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-2"></i> Processing...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(async response => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;

            const data = await response.json();

            if (!response.ok) {
                // Handle validation errors
                if (response.status === 422 && data.errors) {
                    throw { errors: data.errors };
                }
                // Handle other error responses
                throw { message: data.message || 'Request failed' };
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    position: 'center',
                    showConfirmButton: true,
                    timer: 3000,
                    customClass: {
                        popup: 'border-radius-12',
                        confirmButton: 'btn btn-custom'
                    }
                });
            }
        })
        .catch(error => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;

            let errorMessage = 'An error occurred during check-in';

            // Handle validation errors
            if (error.errors && error.errors.booking_code) {
                errorMessage = error.errors.booking_code[0];
            }
            // Handle other error messages
            else if (error.message) {
                errorMessage = error.message;
            }
            // Handle network errors
            else if (error instanceof TypeError) {
                errorMessage = 'Network error occurred. Please check your connection.';
            }

            Swal.fire({
                icon: 'error',
                title: 'Check-In Failed',
                text: errorMessage,
                position: 'center',
                showConfirmButton: true,
                customClass: {
                    popup: 'border-radius-12',
                    confirmButton: 'btn btn-custom'
                }
            });
        });
    });
});
</script>
@endpush

@push('css')
<style>
   .card {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(46, 94, 47, 0.1);
    }

    .form-control-lg {
        padding: 1rem;
        font-size: 1rem;
        border-left: 0 !important;
    }

    .input-group-text {
        background-color: #fff;
        border-right: 0 !important;
    }

    .btn-lg {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-lg:hover {
        background-color: #1e4e1f !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 94, 47, 0.2);
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item.active {
        color: #2e5e2f;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .card-header {
            padding: 1rem;
        }

        .form-control-lg {
            padding: 0.75rem;
            font-size: 0.95rem;
        }

        .btn-lg {
            padding: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-body {
            padding: 1.5rem;
        }
    }

    .swal2-popup {
        border-radius: 12px !important;
    }

    .swal2-confirm.btn-custom {
        background-color: #2e5e2f !important;
    }

    .swal2-confirm.btn-custom:hover {
        background-color: #1e4e1f !important;
    }
</style>
@endpush
