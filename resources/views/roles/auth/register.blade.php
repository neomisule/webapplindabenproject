@extends('roles.auth.layouts.main')


@section('title', 'Volunteer Register')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Create Volunteer Account</h2>

        <form method="POST" action="{{ route('volunteer.register') }}">
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
                <input type="number" class="form-control @error('phone_number') is-invalid @enderror"
                    name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-custom w-100">SIGN UP</button>

            <div class="form-footer">
                <small>Have an account Staff or Volunteer? <a href="{{ route('login') }}">Log in</a></small>
            </div>
        </form>
    </div>
</div>
@endsection
