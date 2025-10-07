<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Organization | LindaBen</title>
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
            background: linear-gradient(135deg, #f5f5f5 0%, #e8f5e9 100%);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .selection-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-top: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            max-width: 600px;
            margin: 0 auto;
        }

        .selection-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
            text-align: center;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .form-select {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 1rem;
        }

        .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
        }

        .btn-submit:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(46, 125, 50, 0.3);
        }

        .hero-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1.5rem;
            }

            .selection-card {
                margin: 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-icons text-center">
                    <i class="fas fa-hands-helping hero-icon"></i>
                </div>

                <div class="selection-card">
                    <div class="card-header">
                        <h2 class="card-title">Select Organization</h2>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('volunteers.list') }}" method="GET">
                            <div class="mb-4">
                                <label for="ngo_id" class="form-label fw-bold mb-3">Choose NGO/Organization</label>
                                <select class="form-select" id="ngo_id" name="ngo_id" required>
                                    <option value="">-- Select an Organization --</option>
                                    @foreach($ngos as $ngo)
                                        <option value="{{ $ngo->id }}">
                                            {{ $ngo->name }} ({{ date('d M Y', strtotime($ngo->date)) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-search me-2"></i> View Volunteers
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
