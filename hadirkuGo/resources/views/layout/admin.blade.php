<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HadirquGO')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SKEWGER5TD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SKEWGER5TD');
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #14274e;
            min-height: 100vh;
            color: #ffffff;
            padding-top: 1rem;
            width: 250px;
        }
        .sidebar h2 {
            font-size: 1.2rem; /* Reduced font size */
            font-weight: 600;
            color: #ffffff;
            text-align: center;
        }
        .sidebar a {
            color: #ffffff;
            font-size: 0.9rem; /* Reduced font size for sidebar links */
            font-weight: 500;
            padding: 0.6rem 1rem;
            transition: background 0.3s;
            display: flex;
            align-items: center;
        }
        .sidebar a i {
            font-size: 1rem;
            margin-right: 8px;
        }
        .sidebar a:hover, .sidebar .nav-link.active {
            background-color: #1c3b57;
            color: #ffffff;
        }
        .content {
            padding: 1rem 1.5rem; /* Reduced padding to bring content closer to the top */
            width: 100%;
        }
        .avatar {
            width: 35px; /* Slightly smaller avatar size */
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
            color: #14274e;
        }
        .navbar-light .navbar-nav .nav-link {
            color: #14274e;
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #1c3b57;
        }
        .dropdown-menu a {
            color: #14274e;
        }
        .dropdown-menu a:hover {
            background-color: #f0f0f0;
            color: #14274e;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3">
        <h2 class="mb-4">Panel HadirquGO</h2>

        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            @if(Route::has('admin.users.index'))
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                </li>
            @endif
            @if(Route::has('admin.reports.index'))
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Reports
                    </a>
                </li>
            @endif
            @if(Route::has('admin.profile'))
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
            @endif
            @if(Route::has('admin.settings'))
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i> Settings
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <header class="mb-3">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-top: -1rem;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">@yield('title', 'Panel HadirquGO')</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-bell"></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}" alt="User Avatar" class="avatar me-2">
                                    @else
                                        <i class="fas fa-user-circle me-2"></i>
                                    @endif
                                    {{ Auth::user()->name ?? 'Admin' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Content Section -->
        <main>
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@auth
    @include('partials.saiqu-chat')
@endauth

</body>
</html>
