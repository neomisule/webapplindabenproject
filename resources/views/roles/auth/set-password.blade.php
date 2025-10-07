@extends('roles.auth.layouts.main')


@section('title', 'Set Password')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Set Your Password</h2>

        <form method="POST" action="{{ route('volunteer.password.setup', $token) }}">
            @csrf

            <div class="mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="New Password" required>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <input type="password" class="form-control"
                    name="password_confirmation" placeholder="Confirm New Password" required>
            </div>

            <button type="submit" class="btn btn-custom w-100">SET PASSWORD</button>
        </form>
    </div>
</div>
@endsection
