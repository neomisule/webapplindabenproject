@extends('roles.auth.layouts.main')

@section('title', 'Volunteer Login')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Staff / Volunteer Login</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 position-relative">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
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

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn btn-custom w-100">SIGN IN</button>

            <div class="form-footer mt-3">
                <small>Become a Volunteer? <a href="{{ route('volunteer.register') }}">Sign up</a></small>
            </div>

            <div class="form-footer mt-3 text-center">
                <small>
                    <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                </small>
            </div>
        </form>
    </div>
</div>

@endsection
