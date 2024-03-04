<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title', 'Dashboard')</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/custom/js/mode.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/custom/js/swal2.min.js') }}"></script>
    <link href="{{ asset('assets/custom/css/global.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('assets/custom/img/favicon.ico') }}">
    <meta name="theme-color" content="#712cf9">

    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Untuk mode terang (light) */
        [data-bs-theme="light"] .navbar {
            background-color: #f8f9fa;
        },
        [data-bs-theme="light"] .nav-link,
        [data-bs-theme="light"] .carousel-caption p,
        [data-bs-theme="light"] .carousel-caption h3 {
            color: #000;
        }

        /* Untuk mode gelap (dark) */
        [data-bs-theme="dark"] .navbar {
            background-color: #292a2c;
        }
        [data-bs-theme="dark"] .nav-link,
        [data-bs-theme="dark"] .carousel-caption p,
        [data-bs-theme="dark"] .carousel-caption h3 {
            color: #fff;
        }

        /* Penyesuaian untuk teks "Log Out" dan switch dark mode */
        .navbar-nav .form-switch {
            margin-left: 10px;
        }

        .navbar-nav {
            --bs-nav-link-padding-x: 0;
            --bs-nav-link-padding-y: 0;
            --bs-nav-link-font-weight: ;
            --bs-nav-link-color: var(--bs-navbar-color);
            --bs-nav-link-hover-color: var(--bs-navbar-hover-color);
            --bs-nav-link-disabled-color: var(--bs-navbar-disabled-color);
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg border-body sticky-top">
        <div class="container-fluid mx-5">
            <a class="navbar-brand fw-bold" href="/">Gallery App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar collapse content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (auth()->check())
                        <li class="nav-item {{ request()->is('album*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('album') }}"><i class="fa-solid fa-images"></i>
                                My Albums</a>
                        </li>
                        <li class="nav-item {{ request()->is('photo*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('foto.index') }}"><i class="fa-solid fa-image"></i>
                                My Photos</a>
                        </li>
                    @endif
                    <!-- Dark mode switch -->
                    <li class="nav-item form-switch text-light">
                        <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                        <label class="form-check-label" for="darkModeSwitch">
                            <i class="fa-solid fa-sun" style="color: #272727" id="lightModeIcon"></i>
                            <i class="fa-solid fa-moon" id="darkModeIcon"></i>
                        </label>
                    </li>
                </ul>

                <!-- Move user name and profile picture to the right -->
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    @if (auth()->check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->profile_picture ? asset('storage/user_profile/' . Auth::user()->profile_picture) : asset('assets/default-avatar.jpg') }}"
                                    alt="Profile Picture" class="rounded-circle"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                                <span class="ms-2">{{ Auth::user()->Username }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i
                                            class="fa-solid fa-user"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                            class="fa-solid fa-right-from-bracket"></i> Log Out</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" aria-current="page"
                                href="{{ route('register.create') }}"><i
                                    class="fa-solid fa-user-plus"></i>&nbsp;Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" aria-current="page" href="{{ route('login') }}"><i
                                    class="fa-solid fa-right-to-bracket"></i>&nbsp;Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
</body>
