<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CueMaster - Reservasi Meja Billiard</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <!-- Custom Premium Styles -->
    <style>
        :root {
            --cue-dark: #0f172a;
            --cue-darker: #020617;
            --cue-green: #059669;
            --cue-green-glow: rgba(5, 150, 105, 0.2);
            --cue-emerald: #10b981;
            --cue-slate: #1e293b;
            --cue-light-slate: #334155;
            --cue-gold: #fbbf24;
            --font-outfit: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-outfit);
            background-color: var(--cue-darker);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--cue-light-slate);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff !important;
            text-shadow: 0 0 10px var(--cue-green-glow);
        }

        .navbar-brand span {
            color: var(--cue-emerald);
        }

        .nav-link {
            font-weight: 500;
            color: #94a3b8 !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            text-shadow: 0 0 8px var(--cue-green-glow);
        }

        .btn-cue {
            background: linear-gradient(135deg, var(--cue-green) 0%, var(--cue-emerald) 100%);
            color: #fff;
            border: none;
            font-weight: 600;
            box-shadow: 0 4px 15px var(--cue-green-glow);
            transition: all 0.3s ease;
        }

        .btn-cue:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-cue-outline {
            border: 2px solid var(--cue-green);
            color: var(--cue-emerald);
            background: transparent;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cue-outline:hover {
            background: var(--cue-green);
            color: #fff;
        }

        .card-cue {
            background: var(--cue-dark);
            border: 1px solid var(--cue-light-slate);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-cue:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(5, 150, 105, 0.15);
            border-color: var(--cue-green);
        }

        .footer {
            background: var(--cue-dark);
            border-top: 1px solid var(--cue-light-slate);
            margin-top: auto;
            color: #64748b;
        }

        /* Status Badges */
        .badge-pending {
            background-color: var(--cue-gold);
            color: #000;
        }
        .badge-confirmed {
            background-color: var(--cue-green);
            color: #fff;
        }
        .badge-cancelled {
            background-color: #ef4444;
            color: #fff;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--cue-darker);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--cue-light-slate);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--cue-green);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-8-circle-fill me-2 text-success"></i>CUE<span>MASTER</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        @if(in_array(Auth::user()->role, ['admin', 'owner', 'kasir']))
                            <!-- Admin Navigation -->
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.tables') ? 'active' : '' }}" href="{{ route('admin.tables') }}">
                                    <i class="bi bi-grid-3x3-gap me-1"></i> Kelola Meja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.payments') ? 'active' : '' }}" href="{{ route('admin.payments') }}">
                                    <i class="bi bi-credit-card me-1"></i> Pembayaran
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.notifications') ? 'active' : '' }}" href="{{ route('admin.notifications') }}">
                                    <i class="bi bi-bell me-1"></i> Notifikasi
                                    @php
                                        // Count logs created in last 24h as notification count
                                        $notifCount = \App\Models\ReservationLog::where('created_at', '>=', now()->subDay())->count();
                                    @endphp
                                    @if($notifCount > 0)
                                        <span class="badge rounded-pill bg-danger">{{ $notifCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservations.calendar') ? 'active' : '' }}" href="{{ route('reservations.calendar') }}">
                                    <i class="bi bi-calendar3 me-1"></i> Kalender Jadwal
                                </a>
                            </li>
                        @else
                            <!-- Customer Navigation -->
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservations.index') ? 'active' : '' }}" href="{{ route('reservations.index') }}">
                                    <i class="bi bi-clock-history me-1"></i> Riwayat Reservasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservations.create') ? 'active' : '' }}" href="{{ route('reservations.create') }}">
                                    <i class="bi bi-plus-circle me-1"></i> Reservasi Baru
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservations.calendar') ? 'active' : '' }}" href="{{ route('reservations.calendar') }}">
                                    <i class="bi bi-calendar3 me-1"></i> Kalender Jadwal
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="d-flex align-items-center">
                        <span class="text-light me-3 small">
                            <i class="bi bi-person-fill text-success me-1"></i>{{ Auth::user()->name }} 
                            <span class="badge bg-secondary ms-1 small">{{ ucfirst(Auth::user()->role) }}</span>
                        </span>
                        
                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm border-0">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Daftar</a>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container py-4 flex-grow-1">
        <!-- Toast Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid var(--cue-green) !important;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #ef4444 !important;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer py-3 text-center">
        <div class="container">
            <span class="small">&copy; {{ date('Y') }} CueMaster Billiard Lounge - Tugas Pola Desain Perangkat Lunak (PDPL)</span>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS (Popper + Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    @yield('scripts')
</body>
</html>
