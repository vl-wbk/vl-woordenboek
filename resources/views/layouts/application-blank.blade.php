<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | {{ ucfirst($title) ?? null }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">



    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon//favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon//favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    {{--  Open graph protocol integration --}}
    @yield('openGraph')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @include('feed::links')
</head>

<body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-md navbar-dark bg-navbar shadow-sm">
        <div class="{{ $containerSize ?? 'container-fluid' }}">
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
                    @auth
                        @can('access-backend')
                            <li class="nav-item">
                                <a href="{{ url('admin') }}" class="nav-link">
                                    <x-heroicon-s-arrows-right-left class="icon me-1" /> Beheersconsole
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item">
                            <a href="{{ route('statistics') }}" class="nav-link">
                                <x-heroicon-o-presentation-chart-line class="icon"/> Statistieken
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item dropdown">
                        <a id="infoDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <x-heroicon-s-information-circle class="icon me-1" /> Info
                        </a>

                        <div class="dropdown-menu bg-white shadow-sm border-0 dropdown-menu">
                            @if (app(\App\Settings\ProjectInformationSettings::class)->pageActive)
                                <a href="{{ route('project-information')}}" class="dropdown-item">
                                    <x-tabler-info-square-rounded class="icon text-muted me-1" /> Project informatie
                                </a>
                            @endif

                            <a href="{{ route('terms-of-service') }}" class="dropdown-item">
                                <x-tabler-gavel class="icon text-muted me-1" /> Disclaimer
                            </a>

                            <a class="dropdown-item" href="https://vl-wbk.github.io/documentatie-portaal/" target="_blank">
                                <x-tabler-book-2 class="icon text-muted me-1" /> Gebruikershandleiding
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="https://www.forum.chimpy.be" class="nav-link" target="_blank"">
                            <x-heroicon-o-chat-bubble-left-right class="icon me-1"/> Forum
                        </a>
                    </li>

                    @if (\App\Models\Blog::count('id') > 0)
                        <li class="nav-item">
                            <a href="{{ route('news:index') }}" class="nav-link">
                                <x-heroicon-s-newspaper class="icon"/> Artikelen
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="mailto:contact@vlaamswoordenboek.be" class="nav-link">
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
        </div>
    </nav>

    {{--  TE VERWIJDEREN WANNEER DE PUBLIEKE FASE IS AFGELOPEN --}}
    <div class="alert alert-warning border-0 mb-0 rounded-0 shadow-sm py-2" role="alert">
        <strong><x-heroicon-s-exclamation-triangle class="icon me-1"/> Ter info:</strong>
        jdit is een bètaversie van het nieuwe Vlaamse Woordenboek om uitgebreid te testen. De data zijn van april 2025. Alle  <a href="{{ route('feedback:create') }}" target="_blank" class="alert-link">feedback</a> is welkom.
        welkom. De recentste artikelen vind je op het oude <a href="https://www.vlaamswoordenboek.be/" class="alert-link">vlaamswoordenboek.be</a>.
    </div>
    {{-- EINDE --}}

    <main class="{{ $paddingContent ?? 'py-4' }} flex-shrink-0">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 bg-transparent">
        <div class="{{ $containerSize ?? 'container' }}">
            <span class="fw-bold text-body-secondary">
                &copy; {{ date('Y') }}, {{ config('app.name', 'Laravel') }}
            </span>

            <span class="float-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="{{ route('feedback:create') }}" class="text-decoration-none">
                            Feedback insturen
                        </a>
                    </li>

                    <li class="list-inline-item text-muted">|</li>

                    <li class="list-inline-item">
                        <a href="https://github.com/Tjoosten/vl-woordenboek" target="_blank" class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-github class="icon" />
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a href="https://www.facebook.com/vlaamswoordenboek" target="_blank" class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-facebook class="icon" />
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a href="https://discord.gg/bqKNs2SDz8" target="_blank" class="footer-icon-color text-decoration-none">
                            <x-tabler-brand-discord class="icon" />
                        </a>
                    </li>
                </ul>
            </span>
        </div>
    </footer>
    </div>

    @yield('scripts')
</body>

</html>
