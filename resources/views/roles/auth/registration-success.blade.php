@extends('roles.auth.layouts.main')


@section('title', 'Registration Submitted')
@section('body-class', 'bg-success-page')

@section('content')
<div class="success-container">
    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="Logo" class="logo">

    <div class="success-card">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
        </div>
        <h2 class="success-title">Registration Submitted Successfully!</h2>
        <p class="success-message">
            Thank you for registering as a volunteer. Your account is currently under review by our admin team.
            You will receive an email notification once your account is approved. This email will contain a link to set up your password and access your account.
        </p>
        <a href="/" class="btn btn-home">Return to Home</a>
    </div>
</div>
@endsection
