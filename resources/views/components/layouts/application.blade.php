<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} || {{  $title ?? null }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-md navbar-dark bg-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="" class="nav-link">Random woord</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Statistieken</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="infoDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Info
                        </a>

                        <div class="dropdown-menu bg-white dropdown-menu">
                            <a class="dropdown-item">
                                <x-tabler-info-square-rounded class="icon text-muted me-1"/> Project informatie
                            </a>

                            <a class="dropdown-item">
                                <x-tabler-book-2 class="icon text-muted me-1"/> Gebruikershandleiding
                            </a>
                        </div>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('filament.admin.auth.login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('filament.admin.auth.login') }}">
                                    <x-tabler-login-2 class="icon me-1"/> {{ __('Login') }}
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Registreren') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4 flex-shrink-0">
            {{ $slot }}
        </main>

        <footer class="footer mt-auto py-3 bg-body-tertiary">
            <div class="container">
                <span class="fw-bold text-body-secondary">
                    &copy; {{ date('Y') }}, {{ config('app.name', 'Laravel') }}
                </span>

                <span class="float-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="" class="footer-icon-color text-decoration-none">
                                <x-tabler-brand-github class="icon"/>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="" class="footer-icon-color text-decoration-none">
                                <x-tabler-brand-bluesky class="icon"/>
                            </a>
                        </li>

                        <li class="list-inline-item">
                            <a href="" class="footer-icon-color text-decoration-none">
                                <x-tabler-brand-twitter class="icon"/>
                            </a>
                        </li>
                    </ul>
                </span>
            </div>
        </footer>
    </div>
</body>
</html>
