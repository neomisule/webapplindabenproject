<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Title -->
    <title>Admin Login</title>
    <script src="{{ asset('admin/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>
    <link id="style" href="{{ asset('admin/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/libs/node-waves/waves.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/libs/@simonwep/pickr/themes/nano.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/libs/choices.js/public/assets/styles/choices.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/libs/@tarekraafat/autocomplete.js/css/autoComplete.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .cke_notification_warning {
            display: none !important;
        }
    </style>
</head>
<body class="authentication-background authentication">
    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-8 col-12">
                <div class="card custom-card my-4">
                    <div class="card-body p-5">
                        <div class="mb-4 d-flex justify-content-center">
                            <a href="{{ route('admin.dashboard') }}">
                                <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-logo">
                                <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="logo" class="desktop-white">
                            </a>
                        </div>
                        <p class="h5 mb-2 text-center">Sign In</p>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf

                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="username" class="form-label text-default">User Name</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                           id="username" name="username" placeholder="user name"
                                           value="{{ old('username') }}" required autofocus>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="password" class="form-label text-default d-block">Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control create-password-input @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="password" required>
                                        <a href="javascript:void(0);" class="show-password-button text-muted"
                                           onclick="createpassword('password',this)" id="button-addon2">
                                            <i class="ri-eye-off-line align-middle"></i>
                                        </a>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin/js/show-password.js') }}"></script>
</body>

</html>
