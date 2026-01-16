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

    {{-- Additional page specific styles --}}
    @yield('styles')

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
                                    <x-heroicon-s-arrows-right-left class="icon me-1" /> {{ __('layout/application.navigation.user-menu.management-console') }}
                                </a>
                            </li>
                        @endcan
                    @endauth

                

                    <li class="nav-item">
                        <a href="https://www.forum.chimpy.be" class="nav-link" target="_blank">
                            <x-heroicon-o-chat-bubble-left-right class="icon me-1"/> {{ __('layout/application.footer.community-section.forum') }}
                        </a>
                    </li>

                    @if (\App\Models\Blog::count('id') > 0)
                        <li class="nav-item">
                            <a href="{{ route('news:index') }}" class="nav-link">
                                <x-heroicon-s-newspaper class="icon"/> {{ __('layout/application.navigation.news') }}
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <x-tabler-info-square-rounded class="icon  me-1" /> {{ __('layout/application.footer.links-section.project-information') }}
                        </a>
                        
                        <ul class="dropdown-menu border-0 shadow-sm">
                            @if (app(\App\Settings\ProjectInformationSettings::class)->pageActive)
                                <li>
                                    <a class="dropdown-item" href="{{ route('project-information') }}">
                                        <x-tabler-info-square-rounded class="icon text-muted me-1" /> Algemene informatie
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('statistics') }}" class="dropdown-item">
                                    <x-heroicon-o-presentation-chart-line class="icon text-muted me-1"/> {{ __('pages/statistics.page-title') }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="mailto:contact@vlaamswoordenboek.be" class="nav-link">
                            <x-heroicon-s-envelope class="icon me-1" /> {{ __('layout/application.footer.links-section.contact') }}
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
                    @else
                        <li class=nav-item"">
                            <a href="{{ route('profile:inbox') }}" class="nav-link">
                                <x-heroicon-s-envelope class="icon me-1"/> {{ auth()->user()->unreadMessagesCount() }}
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" role="button" aria-haspopup="true" data-bs-toggle="dropdown" aria-expanded="false">
                                <x-heroicon-s-user-circle class="icon me-1" /> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu border-0 bg-white shadow-sm dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('account:public', auth()->user()) }}">
                                    <x-heroicon-o-user-circle class="text-muted icon me-1"/> {{ __('layout/application.navigation.user-menu.public-profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                    <x-heroicon-o-cog-8-tooth class="text-muted icon me-1"/> {{ __('layout/application.navigation.user-menu.settings') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('suggestions:index') }}">
                                    <x-heroicon-o-queue-list class="text-muted icon me-1"/> {{ __('layout/application.navigation.user-menu.my-suggestions') }}
                                </a>

                                <a class="dropdown-item" href="{{ route('bookmarks:index') }}">
                                    <x-heroicon-o-book-open class="text-muted icon me-1"/> {{ __('layout/application.navigation.user-menu.saved-words') }}
                                </a>

                                <div class="dropdown-divider"></div>

                                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <x-heroicon-s-power class="icon text-danger me-1" /> {{ __('layout/application.navigation.user-menu.lgout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('jumbotron')

    <main class="{{ $paddingContent ?? 'mb-5' }} flex-shrink-0">
        @yield('content')
    </main>

    <footer class="footer mt-auto pt-4 pt-md-3 mt-5">
        <div class="container-fluid pt-4 pb-2 py-md-4x text-body-secondary">
            <div class="row">
                <div class="col-lg-3">
                <a class="d-inline-flex align-items-center mb-2 text-white text-decoration-none" href="/" aria-label="Bootstrap">
                    <x:heroicon-s-book-open class="icon icon-back-to-results brand-gradient"/>
                    <span class="fs-5 brand-gradient fw-bold ms-2">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <ul class="list-unstyled small text-white">
                    <li class="mb-2">
                        {{ __('layout/application.footer.information-section.info-paragraph') }}
                    </li>

                    <li class="mb-2">
                        De code is gelicentieerd onder de MIT-licentie. De documentatie hiervan is beschikbaar onder de
                        <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" class="text-white">CC BY-NC-SA 4.0 licentie.</a>
                    </li>

                    <li class="mb-2 fst-italic brand-gradient">
                        {{ __('layout/application.footer.information-section.version-paragraph', ['version' => 'v0.1.0']) }}
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 offset-lg-1 mb-3">
                <h5 class="brand-gradient">{{ __('layout/application.footer.links-section.heading') }}</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/" class="text-white">
                            {{ __('layout/application.footer.links-section.home') }}
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('statistics') }}" class="text-white">
                            {{ __('layout/application.footer.links-section.statistics') }}
                        </a>
                    </li>

                    @if (app(\App\Settings\ProjectInformationSettings::class)->pageActive)
                        <li class="mb-2">
                            <a href="{{ route('project-information') }}" class="text-white">
                                {{ __('layout/application.footer.links-section.project-information') }}
                            </a>
                        </li>
                    @endif

                    @if (\App\Models\Blog::count('id') > 0)
                        <li class="mb-2">
                            <a href="{{ route('news:index') }}" class="text-white">
                                {{ __('layout/application.footer.links-section.news') }}
                            </a>
                        </li>
                    @endif

                    <li class="mb-2">
                        <a href="mailto:contact@vlaamswoordenboek.be" class="text-white">
                            {{ __('layout/application.footer.links-section.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 mb-3">
                <h5 class="brand-gradient">{{ __('layout/application.footer.sources-section.heading') }}</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="https://ivdnt.org/woordenboeken/historische-woordenboeken/woordenboek-der-nederlandsche-taal/" class="text-white" target="_blank" rel="noopener" title="Woordenboek der Nederlandsche Taal">
                            WNT
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://www.dialectloket.be/woord/woordenbank-van-de-nederlandse-dialecten/" class="text-white" target="_blank" rel="noopener" title="Woordenbank van de Nederlandse Dialecten">
                            WND
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://www.etymologiebank.nl/" class="text-white" target="_blank" rel="noopener">
                            Etymologiebank
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 mb-3">
                <h5 class="brand-gradient">{{ __('layout/application.footer.contribution-section.heading') }}</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('definitions.create') }}" target="_blank" rel="noopener" class="text-white">
                            {{ __('layout/application.footer.contribution-section.submit-suggestion') }}
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('feedback:create') }}" target="_blank" rel="noopener" class="text-white">
                            {{ __('layout/application.footer.contribution-section.submit-feedback') }}
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://github.com/vl-wbk/vl-woordenboek/issues" target="_blank" rel="noopener" class="text-white">
                            {{ __('layout/application.footer.contribution-section.github-issues') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-2 mb-3">
                <h5 class="brand-gradient">{{ __('layout/application.footer.community-section.heading') }}</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="https://github.com/vl-wbk/vl-woordenboek" target="_blank" rel="noopener" class="text-white">
                            <x:tabler-brand-github class="icon me-2"/>Github
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://discord.com/invite/bqKNs2SDz8" target="_blank" rel="noopener" class="text-white">
                            <x:tabler-brand-discord class="icon me-2"/>Discord
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://www.facebook.com/vlaamswoordenboek" target="_blank" rel="noopener" class="text-white">
                            <x:tabler-brand-facebook class="icon me-2"/>Facebook
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="https://www.forum.chimpy.be" target="_blank" rel="noopener" class="text-white">
                            <x:tabler-messages class="icon me-2"/>{{ __('layout/application.footer.community-section.forum') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
    <div class="footer py-2" style="background-color: oklch(21.6% 0.006 56.043)">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <span class="text-yellow">
                        &copy; {{ __('layout/application.footer.copyright', ['date' => date('Y'), 'application' => config('app.name', 'laravel')]) }}
                    </span>

                    <div class="float-end">
                        <a href="{{ route('terms-of-service') }}" class="text-white text-decoration-none">
                            <x-tabler-gavel class="icon me-1"/> {{ __('layout/application.footer.terms') }}
                        </a>
                        <a href="https://vl-wbk.github.io/documentatie-portaal/" class="text-white ms-3 text-decoration-none" target="_blank">
                            <x-tabler-book-2 class="icon me-1"/> {{ __('layout/application.footer.documentation') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0649FN8Q9F"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-0649FN8Q9F');
    </script>

    @yield('scripts')
</body>

</html>
