<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Volunteer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            display: block;
            margin: 0 auto 2rem;
            max-width: 120px;
            height: auto;
        }

        .auth-card,
        .success-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 2rem;
            background-color: white;
        }

        .success-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .success-icon {
            color: #2e5e2f;
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }

        .success-title {
            color: #2e5e2f;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #495057;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .auth-container {
            padding: 2rem 0;
        }

        .form-title {
            color: #2e5e2f;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #2e5e2f;
            box-shadow: 0 0 0 0.25rem rgba(46, 94, 47, 0.25);
        }

        .btn-custom,
        .btn-home {
            background-color: #2e5e2f;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-custom:hover,
        .btn-home:hover {
            background-color: #264e28;
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
        }

        .form-footer a {
            color: #2e5e2f;
            font-weight: 500;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            margin-top: -0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        /* Background colors */
        .bg-auth {
            background-color: #f3f4f6;
        }

        .bg-success-page {
            background-color: #f8f9fa;
        }
    </style>
    @stack('styles')
</head>

<body class="@yield('body-class')">
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.is-invalid').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.remove();
                }
            });
        });
    </script>
    <script>
        function togglePassword(id, ele) {
            const input = document.getElementById(id);
            const icon = ele.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
    @stack('scripts')
</body>

</html>
