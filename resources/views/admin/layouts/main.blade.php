<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Title -->
    @stack('title')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <link href="{{ asset('admin/css/treeselectjs.css') }}" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .cke_notification_warning {
            display: none !important;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('css')
</head>

<body class="">

    <div class="page">

        @include('admin.partials.header')

        @include('admin.partials.sidebar')

        <div class="main-content app-content">
            <div class="container-fluid">
                <div class="body flex-grow-1 px-3">
                    <div class="container-lg">
                        @yield('main-section')
                    </div>
                </div>
            </div>
        </div>

        @include('admin.partials.footer')
</body>

</html>
