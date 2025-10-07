<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer List | LindaBen</title>
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
        }

        .header-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-color);
        }

        .volunteer-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--secondary-color);
        }

        .volunteer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-back {
            background-color: var(--text-light);
            color: white;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #546e7a;
            color: white;
            transform: translateY(-2px);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-booked {
            background-color: rgba(46, 125, 50, 0.1);
            color: var(--primary-dark);
        }

        .badge-checkedin {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
        }

        .badge-completed {
            background-color: rgba(96, 125, 139, 0.1);
            color: var(--text-light);
        }

        .ngo-title {
            color: var(--primary-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 8px;
            display: inline-block;
        }

        .info-label {
            color: var(--text-light);
            font-weight: 500;
        }

        .info-value {
            color: var(--text-dark);
            font-weight: 600;
        }

        .empty-state {
            background-color: rgba(233, 245, 233, 0.5);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .volunteer-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <a href="{{ route('volunteers.select') }}" class="btn btn-back mb-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to Selection
                </a>

                <div class="header-card p-4 mb-4">
                    <h3 class="ngo-title mb-3">{{ $ngo->name }}</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-2"><span class="info-label">Date:</span>
                                <span class="info-value">{{ date('d M Y', strtotime($ngo->date)) }}</span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><span class="info-label">Time:</span>
                                <span class="info-value">
                                    {{ date('h:i A', strtotime($ngo->start_time)) }} - {{ date('h:i A', strtotime($ngo->end_time)) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><span class="info-label">Location:</span>
                                <span class="info-value">{{ $ngo->address }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($volunteers->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-users empty-icon"></i>
                        <h4 class="mb-3" style="color: var(--primary-dark)">No Volunteers Found</h4>
                        <p class="text-muted">No volunteers have registered for this organization yet.</p>
                    </div>
                @else
                    <h4 class="mb-4" style="color: var(--primary-dark)">Registered Volunteers ({{ $volunteers->count() }})</h4>

                    <div class="row">
                        @foreach($volunteers as $volunteer)
                        <div class="col-md-6 mb-4">
                            <div class="volunteer-card p-4 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="mb-0" style="color: var(--primary-dark)">
                                        <i class="fas fa-user-circle me-2" style="color: var(--secondary-color)"></i>
                                        {{ $volunteer->volunteer->name }}
                                    </h5>
                                    <span class="badge-status
                                        @if($volunteer->status == 'booked') badge-booked
                                        @elseif($volunteer->status == 'checked_in') badge-checkedin
                                        @else badge-completed @endif">
                                        {{ ucfirst(str_replace('_', ' ', $volunteer->status)) }}
                                    </span>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-6">
                                        <p class="mb-2"><span class="info-label">Booking Code:</span></p>
                                        <p class="info-value">{{ $volunteer->booking_code }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-2"><span class="info-label">Shift Time:</span></p>
                                        <p class="info-value">
                                            {{ date('h:i A', strtotime($volunteer->start_time)) }} -
                                            {{ date('h:i A', strtotime($volunteer->end_time)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
