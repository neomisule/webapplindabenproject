@extends('roles.auth.layouts.main')

@section('title', 'Volunteer Approved')
@section('body-class', 'bg-success-page')

@section('content')
<div class="success-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Logo" class="logo">

    <div class="success-card">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </svg>
        </div>
        <h2 class="success-title">Volunteer Approved Successfully!</h2>
        <p class="success-message">
            You have successfully approved the volunteer account.
            The volunteer has been notified via email to set up their password and access their account.
        </p>
        <a href="{{ url('/') }}" class="btn btn-home">Return to Home</a>
    </div>
</div>
@endsection
