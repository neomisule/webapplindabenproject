@extends('roles.auth.layouts.main')

@section('title', 'Reset Password')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Set New Password</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="Enter your email address"
                    value="{{ $email ?? old('email') }}" required readonly>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror pe-5"
                    id="password" name="password" placeholder="Enter new password" required>
                <button type="button" class="me-2 btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none text-muted show-password-button p-0"
                    onclick="togglePassword('password', this)" id="button-addon2">
                    <i class="fa fa-eye-slash"></i>
                </button>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password-confirm" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control pe-5"
                    id="password-confirm" name="password_confirmation"
                    placeholder="Confirm new password" required>
                <button type="button" class="me-2 btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none text-muted show-password-button p-0"
                    onclick="togglePassword('password-confirm', this)">
                    <i class="fa fa-eye-slash"></i>
                </button>
            </div>

            <button type="submit" class="btn btn-custom w-100">RESET PASSWORD</button>

            <div class="form-footer mt-3 text-center">
                <small>
                    <a href="{{ route('login') }}">Back to Login</a>
                </small>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

<style>
.alert-danger {
    background-color: #fee2e2;
    border-color: #fecaca;
    color: #dc2626;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 20px;
}
</style>
@endsection
