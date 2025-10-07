@extends('roles.auth.layouts.main')


@section('title', 'Volunteer Login')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">User Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <input type="password" class="form-control @error('password') is-invalid @enderror pe-5"
                    name="password" placeholder="Password" required id="password">
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

            <button type="submit" class="btn btn-custom w-100">SIGN IN</button>

            <div class="form-footer">
                <small>Don't have an account? <a href="{{ route('user.register') }}">Sign up</a></small>
            </div>
        </form>
    </div>


</div>
@endsection
