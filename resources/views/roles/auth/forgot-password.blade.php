@extends('roles.auth.layouts.main')

@section('title', 'Forgot Password')
@section('body-class', 'bg-auth')

@section('content')
<div class="auth-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Volunteer Logo" class="logo">

    <div class="auth-card">
        <h2 class="form-title">Reset Your Password</h2>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="Enter your email address"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-custom w-100">SEND RESET LINK</button>

            <div class="form-footer mt-3 text-center">
                <small>
                    <a href="{{ route('login') }}">Back to Login</a>
                </small>
            </div>
        </form>
    </div>
</div>

<style>
.alert-success {
    background-color: #d1fae5;
    border-color: #a7f3d0;
    color: #065f46;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 20px;
}
</style>
@endsection
