@extends('roles.auth.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="auth-card">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h3 class="mb-0">Become a Volunteer</h3>
                </div>

                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#2e5e2f" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                        </svg>
                    </div>

                    <div class="alert alert-info mb-4">
                        <p class="mb-2"><strong>Volunteer Benefits:</strong></p>
                        <ul class="mb-0">
                            <li>Take on more responsibilities</li>
                            <li>Help the community</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning mb-4">
                        <p class="mb-0">Your request will be reviewed by our admin team. You'll receive a notification once your application is processed.</p>
                    </div>

                  <form method="POST" action="{{ auth()->user()->hasRole('staff') ? route('staff.request.volunteer') : route('user.request.volunteer') }}" class="mt-4">
                        @csrf

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-success btn-lg py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill me-2" viewBox="0 0 16 16">
                                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                                </svg>
                                Submit Volunteer Request
                            </button>

                            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                </svg>
                                Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
