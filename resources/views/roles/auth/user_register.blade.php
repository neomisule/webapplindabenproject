@extends('roles.auth.layouts.main')

@section('title', 'Volunteer Register')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="user Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Create User Account</h2>

        <form method="POST" action="{{ route('user.register') }}">
            @csrf

            <div class="mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                    name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                @error('phone_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="Password" required>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <!-- Added Confirm Password Field -->
            <div class="mb-3">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation" placeholder="Confirm Password" required>
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-custom w-100">SIGN UP</button>

            <div class="form-footer">
                <small>Have an account? <a href="{{ route('user.login') }}">Log in</a></small>
            </div>
        </form>
    </div>
</div>
@endsection
