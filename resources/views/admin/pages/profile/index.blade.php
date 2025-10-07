@extends('admin.layouts.main')

@push('title')
<title>Profile - {{ config('app.name') }}</title>
@endpush

@section('main-section')
<div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
    <div>
        <h3 class="dark">Profile Settings</h3>
        <nav>
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Update Profile Information</h5>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST" id="profile-form">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name) }}" required readonly>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $user->email) }}" required readonly>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username', $user->username) }}" required readonly>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex mt-3">
                            <button type="button" class="btn btn-sm btn-outline-warning me-2" id="edit-profile-btn">Edit</button>
                            <button type="submit" class="btn btn-primary me-2" id="update-btn" disabled>Update Profile</button>
                            <button type="button" class="btn btn-secondary btn-sm" id="cancel-edit-btn" style="display: none;">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Change Password</h5>
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required>
                            @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control"
                                id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    const editBtn = document.getElementById('edit-profile-btn');
    const cancelBtn = document.getElementById('cancel-edit-btn');
    const updateBtn = document.getElementById('update-btn');
    const form = document.getElementById('profile-form');
    const inputs = form.querySelectorAll('input');


    let originalValues = {};
    inputs.forEach(input => {
        originalValues[input.name] = input.value;
    });

    editBtn.addEventListener('click', function() {
        inputs.forEach(input => input.removeAttribute('readonly'));
        updateBtn.disabled = false;
        cancelBtn.style.display = 'inline-block';
        editBtn.style.display = 'none';
    });

    cancelBtn.addEventListener('click', function() {
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.value = originalValues[input.name] ?? '';
        });
        updateBtn.disabled = true;
        cancelBtn.style.display = 'none';
        editBtn.style.display = 'inline-block';
    });
</script>
@endpush
