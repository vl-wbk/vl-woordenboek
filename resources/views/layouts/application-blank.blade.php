<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? null }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Special+Elite&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-md navbar-dark bg-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    @if (Auth::check() && auth()->user()->can('access-backend'))
                        <li class="nav-item">
                            <a href="{{ url('admin') }}" class="nav-link">
                                <x-heroicon-s-arrows-right-left class="icon me-1" /> Beheersconsole
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <x-heroicon-s-language class="icon me-1" /> Random woord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <x-heroicon-o-chart-bar class="icon me-1" /> Statistieken
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="infoDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <x-heroicon-s-information-circle class="icon me-1" /> Info
                        </a>

                        <div class="dropdown-menu bg-white shadow-sm dropdown-menu">
                            <a class="dropdown-item">
                                <x-tabler-info-square-rounded class="icon text-muted me-1" /> Project informatie
                            </a>

                            <a href="{{ route('terms-of-service') }}" class="dropdown-item">
                                <x-tabler-gavel class="icon text-muted me-1" /> Disclaimer
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item">
                                <x-tabler-book-2 class="icon text-muted me-1" /> Gebruikershandleiding
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="https://www.forum.chimpy.be" class="nav-link" target="_blank"">
                            <x-heroicon-o-chat-bubble-left-right class="icon me-1"/> Forum
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <x-heroicon-s-envelope class="icon me-1" /> Contact
                        </a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('filament.admin.auth.login'))
                            <li class="nav-item">
                                <a class="nav-link {{ active('login') }}" href="{{ route('login') }}">
                                    <x-tabler-login-2 class="icon me-1" /> {{ __('Login') }}
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ active('register') }}" href="{{ route('register') }}">
                                    <x-heroicon-o-user-plus class="me-1 icon"/> {{ __('Registreren') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a href="{{ route('profile.settings.security') }}" class="nav-link">
                                <x-heroicon-s-user-circle class="icon me-1" /> {{ Auth::user()->name }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <x-heroicon-s-power class="icon text-danger" />
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
    </nav>

    <main class="py-4 flex-shrink-0">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 bg-transparent">
        <div class="container">
            <span class="fw-bold text-body-secondary">
                &copy; {{ date('Y') }}, {{ config('app.name', 'Laravel') }}
            </span>

            <span class="float-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="https://github.com/Tjoosten/vl-woordenboek"
                            class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-github class="icon" />
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a href="" class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-bluesky class="icon" />
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a href="" class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-twitter class="icon" />
                        </a>
                    </li>
                </ul>
            </span>
        </div>
    </footer>
    </div>
</body>

</html>
