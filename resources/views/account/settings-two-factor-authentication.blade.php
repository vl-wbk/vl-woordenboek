@extends('layouts.application-blank', ['title' => 'Two factor authenticatie'])

@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-account.profile-information-banner :user=$user />
                </div>
            </div>

            <div class="row py-4">
                <div class="col-lg-3 col-sm-12">
                    @include('account.components.sidebar')
                </div>

                <div class="col-lg-9 col-sm-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="card-title fw-bold color-green">Two factor authenticatie</h5>
                                    <h6 class="card-subtitle text-muted border-bottom mb-3 pb-2">Voeg een extra laag beveiliging toe aan je account doormiddel het gebruik van Two factor authenticatie.</h6>

                                    @if (session('status') == 'two-factor-authentication-enabled')
                                        <p class="mb-2">
                                            We hebben two factor authenticatie voor je account met success geactiveerd voor je accoun   t.
                                            Scan de onderstaande QR code met je Google Authenticator app op je smarthone om de Two factor authenticatie installatie voor je account te voltooien.
                                        </p>

                                        <div class="mb-2">
                                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                                        </div>

                                        <p class="mb-2">
                                            Slaag vervolgens deze herstelcodes op in een password manager of op een andere veilige locatie.
                                            Deze codes kunnen gebruikt worden om de toegang tot je account te herstellen wanneer je het toestel met je Authenticor app verloren bent.
                                        </p>

                                        <div class="bg-sidenav p-3 border-0 rounded mb-3">
                                            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                                {{ $code }}<br>
                                            @endforeach
                                        </div>

                                        @if (!auth()->user()->two_factor_confirmed_at)
                                            <p class="card-text my-2">
                                                Om vervolgens te bevestigen dat Two-factor authenticatie succesvol ingesteld is voor je account kun je in het onderstaande formulier de 6 cijferige code van je authenticator app invoeren ter controle.
                                            </p>

                                            <form action="/user/two-factor-authentication" method="POST">
                                                @csrf

                                                <div class="d-flex align-items-center">
                                                    {{-- 1. The Input Field --}}
                                                    <input id="code" type="text" placeholder="XXXXXX" class="form-control w-75 me-2" name="code" required autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" maxlength="6">

                                                    {{-- 2. The Button --}}
                                                    <button type="submit" class="btn btn-submit w-25">
                                                        <x-heroicon-o-check class="icon me-1"/> Bevestigen
                                                    </button>
                                                </div>
                                            </form>

                                        @endif
                                    @endif

                                    @if (! auth()->user()->two_factor_secret)
                                        <p class="card-text lh-sm text-muted my-3">
                                            Wanneer two-factor authenticatie is ingeschakeld, wordt u tijdens de authenticatie gevraagd om een veilige, willekeurige token.
                                            U kunt deze token ophalen via de Google Authenticator-app op uw telefoon.
                                        </p>


                                        <form action="/user/two-factor-authentication" method="POST">
                                            @csrf {{--  form field protection --}}

                                            <button type="submit" class="btn btn-two-factor">
                                                <x-heroicon-o-wrench-screwdriver class="icon color-green me-1"/> instellen
                                            </button>
                                        </form>
                                    @endif

                                    @if (auth()->user()->two_factor_secret && ! session('status') == 'two-factor-authentication-enabled')
                                        <p class="text-success lh-sm card-text">
                                            Two factor authenticatie is ingesteld en actief op je account momenteel. Geen verdere handelingen zijn nodig. Met de onderstaande knoppen kun je nieuwe herstelcodes aanmaken of de Two factor authenticatie deactiveren.
                                        </p>
                                    @elseif (auth()->user()->two_factor_secret && session('status') == 'recovery-codes-generated')
                                        <p class="mb-3 card-text text-muted">
                                            We hebben nieuwe herstelcodes voor je gegenereerd, slaag deze vervolgens op in een password manager of op een andere veilige locatie.
                                            Deze codes kunnen gebruikt worden om de toegang tot je account te herstellen wanneer je het toestel met je Authenticor app verloren bent.
                                        </p>

                                        <div class="bg-sidenav p-3 border-0 rounded">
                                            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                                {{ $code }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->two_factor_secret)
                            <div class="card-footer border-0 bg-sidenav">
                                <div class="d-flex align-items-center">
                                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary me-2">
                                            <x-heroicon-o-arrow-path class="icon me-1"/> Genereer herstelcodes
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('two-factor.disable') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <x-heroicon-o-x-mark class="icon me-1"/> Deactiveer Two Factor Authenticatie
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
