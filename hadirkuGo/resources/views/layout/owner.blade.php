<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HadirkuGO')</title>

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
            background-color: #153e75;
            min-height: 100vh;
            color: #ffffff;
            width: 250px;
            transition: all 0.3s;
        }
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #1a4c88;
        }
        .sidebar h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            text-align: center;
        }
        .sidebar .nav-link {
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #1a4c88;
            padding-left: 2rem;
        }
        .sidebar .nav-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
            text-align: center;
        }
        .main-content {
            min-height: 100vh;
            background-color: #f8f9fa;
            width: calc(100% - 250px);
        }
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            color: #153e75 !important;
            font-weight: 600;
        }
        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                z-index: 1030;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="wrapper d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2>Owner Panel</h2>
        </div>
        <div class="px-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.businesses.index') }}" class="nav-link {{ request()->routeIs('owner.businesses.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i> Manage Businesses
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <button class="navbar-toggler me-2" type="button" onclick="toggleSidebar()">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand d-lg-none" href="#">HadirkuGO</a>

                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown">
                        <a class="btn btn-link text-dark p-0" href="#" role="button" id="userMenu"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="User Avatar" class="avatar">
                                @else
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                @endif
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="container-fluid p-3 p-md-4">
            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.querySelector('.navbar-toggler');

        if (window.innerWidth < 992) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>

{{-- @auth
    @include('partials.saiqu-chat')
@endauth --}}

</body>
</html>