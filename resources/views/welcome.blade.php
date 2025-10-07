<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | LindaBen Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #4caf50;
            --primary-dark: #1b5e20;
            --secondary-color: #ff9800;
            --text-dark: #263238;
            --text-light: #607d8b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: var(--text-dark);
        }

        .header {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Google Translate Widget Styling */
        #google_translate_element {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 999;
        }

        .goog-te-gadget {
            font-family: 'Poppins', sans-serif !important;
        }

        .goog-te-gadget-simple {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            padding: 5px 10px !important;
        }

        .goog-te-menu-value span {
            color: var(--primary-dark) !important;
        }

        .goog-te-menu-value img {
            display: none !important;
        }

        .goog-te-menu-value:before {
            content: '\f1ab';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-right: 8px;
            color: var(--primary-color);
        }

        .goog-te-menu2 {
            max-width: 300px !important;
            font-family: 'Poppins', sans-serif !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            border: none !important;
        }

        .goog-te-menu2-item div, .goog-te-menu2-item-selected div {
            padding: 8px 15px !important;
        }

        .goog-te-menu2-item-selected {
            background-color: var(--primary-light) !important;
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        #goog-gt-tt {
            display: none !important;
        }

        @media (max-width: 768px) {
            #google_translate_element {
                top: 70px;
                right: 10px;
            }

            .goog-te-gadget-simple {
                padding: 3px 6px !important;
                font-size: 14px !important;
            }
        }

        /* Rest of your existing styles */
        .logo img {
            height: 45px;
            transition: transform 0.3s;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .auth-buttons .btn {
            margin-left: 12px;
            padding: 8px 22px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-login {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-login:hover {
            background-color: rgba(46, 125, 50, 0.1);
        }

        .btn-signup {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 5px rgba(46, 125, 50, 0.3);
        }

        .btn-signup:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(46, 125, 50, 0.3);
        }

        .btn-dashboard {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-dashboard:hover {
            background-color: #e65100;
            color: white;
        }

        .btn-logout {
            background-color: #f44336;
            color: white;
        }

        .btn-logout:hover {
            background-color: #d32f2f;
        }

        .welcome-content {
            padding: 60px 0;
            text-align: center;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            min-height: calc(100vh - 75px);
        }

        .welcome-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .welcome-subtitle {
            font-size: 1.3rem;
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: var(--text-dark);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
            padding: 20px 0;
        }

        .welcome-text p {
            margin-bottom: 25px;
        }

        .user-greeting {
            margin-right: 15px;
            font-weight: 500;
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
        }

        .user-greeting i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .auth-options {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .auth-option {
            background: white;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 320px;
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid var(--primary-color);
        }

        .auth-option:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .auth-option-title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--primary-dark);
            position: relative;
            padding-bottom: 10px;
        }

        .auth-option-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--secondary-color);
        }

        .auth-option-description {
            color: var(--text-light);
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .auth-option-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .hero-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .hero-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            transition: transform 0.3s;
        }

        .hero-icon:hover {
            transform: scale(1.2) rotate(10deg);
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }

            .welcome-subtitle,
            .welcome-text {
                font-size: 1.1rem;
                padding: 0 20px;
            }

            .auth-options {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }

            .auth-option {
                width: 90%;
                max-width: 350px;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <img src="{{ asset('admin/images/brand-logos/logo.png') }}" alt="LindaBen Volunteer Platform Logo">
                </div>
                <div class="auth-buttons">
                    @auth
                    <span class="user-greeting">
                        <i class="fas fa-user-circle"></i>
                        Welcome, {{ Auth::user()->name }}!
                    </span>

                    @php
                    $role = Auth::user()->roles()->first()?->name;
                    @endphp

                    @if ($role === 'volunteer')
                    <a href="{{ route('volunteer.dashboard') }}" class="btn btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    @elseif ($role === 'staff')
                    <a href="{{ route('staff.dashboard') }}" class="btn btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    @elseif ($role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    @endif

                    <form action="{{ route('logout') }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Google Translate Widget -->
    <div id="google_translate_element"></div>

    <!-- Welcome Content with Center Buttons -->
    <main class="welcome-content">
        <div class="container">
            <div class="hero-icons">
                <i class="fas fa-hands-helping hero-icon"></i>
                <i class="fas fa-heart hero-icon"></i>
                <i class="fas fa-users hero-icon"></i>
            </div>

            <h1 class="welcome-title">Welcome to LindaBen Platform</h1>

            <div class="welcome-text">
                <p>You're not just signing up, you're showing up for someone who needs you.
                Whether you're here to lend a hand or to find support, this is where compassion meets action.
                </p>
            </div>

            @auth
            <!-- Content for logged in users -->
            <div class="auth-options">
                <div class="auth-option">
                    <div class="auth-option-title">Continue Your Journey</div>
                    <p class="auth-option-description">Access your dashboard to manage your activities and contributions.</p>
                    <div class="auth-option-buttons">
                        @auth
                        @php
                        $role = Auth::user()->roles()->first()?->name;
                        @endphp

                        @if ($role === 'volunteer')
                        <a href="{{ route('volunteer.dashboard') }}" class="btn btn-signup">
                            <i class="fas fa-tachometer-alt"></i> Volunteer Dashboard
                        </a>
                        @elseif ($role === 'staff')
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-signup">
                            <i class="fas fa-tachometer-alt"></i> Staff Dashboard
                        </a>
                        @elseif ($role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-signup">
                            <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                        </a>
                        @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-signup">
                            <i class="fas fa-tachometer-alt"></i> User Dashboard
                        </a>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>

            @else
            <div class="auth-options">
                <div class="auth-option">
                    <div class="auth-option-title">For Staff / Volunteers</div>
                    <p class="auth-option-description">Join our growing family of volunteers who are changing lives with their time, their skills, and their care.
                        Every smile you bring, every hour you give, it matters more than you know.</p>
                    <div class="auth-option-buttons">
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="{{ route('volunteer.register') }}" class="btn btn-signup">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>

                <div class="auth-option">
                    <div class="auth-option-title">For Users</div>
                    <p class="auth-option-description">We're here for you! With resources, kindness, and care.
                        Explore our tools to nourish your body, mind, and spirit.</p>
                    <div class="auth-option-buttons">
                        <a href="{{ route('user.login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="{{ route('user.register') }}" class="btn btn-signup">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        observer.unobserve(entry.target);
                    }
                });
            }, options);

            document.querySelectorAll('.auth-option, .welcome-title, .welcome-text').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,gu,fr,de,es,pt,it,ru,zh-CN,ja,ar',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL,
                autoDisplay: false
            }, 'google_translate_element');

            // Remove Google branding after load
            setTimeout(function() {
                var branding = document.querySelector('.goog-logo-link');
                if(branding) branding.style.display = 'none';

                var poweredBy = document.querySelector('.goog-te-gadget');
                if(poweredBy) poweredBy.innerHTML = poweredBy.innerHTML.replace('Powered by', '');
            }, 1000);
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>
