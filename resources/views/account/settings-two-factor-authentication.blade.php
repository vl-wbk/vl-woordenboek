@extends('layouts.application-blank', ['title' => __('pages/account-settings/two-factor-authentication.title')])

@section('content')
    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <x-account.profile-information-banner :user=$user/>
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
                                    <h5 class="card-title fw-bold color-green">{{ __('pages/account-settings/two-factor-authentication.title') }}</h5>
                                    <h6 class="card-subtitle text-muted border-bottom mb-3 pb-2">
                                        {{ __('pages/account-settings/two-factor-authentication.subtitle') }}
                                    </h6>

                                    {{-- Bepaal of de codes net zijn geregenereerd --}}
                                    @php
                                        $codesJustRegenerated = (auth()->user()->two_factor_secret && session('status') === 'recovery-codes-generated');
                                    @endphp

                                    {{-- 3. ✅ 2FA ACTIEF EN BEVESTIGD (Normale actieve status) --}}
                                    @if (auth()->user()->two_factor_secret && auth()->user()->two_factor_confirmed_at && !$codesJustRegenerated)
                                        <p class="text-success lh-sm card-text">
                                            <x-heroicon-o-shield-check class="icon me-1"/>
                                            {!! __('pages/account-settings/two-factor-authentication.status.active') !!}
                                        </p>
                                    @endif

                                    {{-- 4. ♻️ HERSTELCODES ZIJN NIEUW GEGENEREERD (Toont alleen codes) --}}
                                    @if ($codesJustRegenerated)
                                        <div class="alert alert-warning p-3">
                                            <p class="fw-bold mb-1">{{ __('pages/account-settings/two-factor-authentication.recovery-codes.title') }}</p>
                                            <p class="mb-3 text-muted">
                                                {!! __('pages/account-settings/two-factor-authentication.recovery-codes.text') !!}
                                            </p>
                                        </div>

                                        {{-- DIV met ID voor kopiëren --}}
                                        <div id="recovery-codes-container-regenerated"
                                             class="bg-sidenav p-3 border-0 rounded mb-3">
                                            @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                                {{ $code }}<br>
                                            @endforeach
                                        </div>

                                        {{-- Knoppen voor kopiëren/downloaden --}}
                                        <div class="d-flex mb-4">
                                            <button type="button" class="btn btn-outline-secondary me-2" onclick="copyRegeneratedCodes()" id="copy-button-regenerated">
                                                <x-heroicon-o-clipboard class="icon me-1"/>
                                                {{ __('pages/account-settings/two-factor-authentication.buttons.copy-codes') }}
                                            </button>

                                            <button type="button" class="btn btn-outline-secondary" onclick="downloadRegeneratedCodes()">
                                                <x-heroicon-o-arrow-down-tray class="icon me-1"/>
                                                {{ __('pages/account-settings/two-factor-authentication.buttons.download-codes') }}
                                            </button>
                                        </div>
                                    @endif


                                    {{-- 1. 2FA INGESCHAKELD & NOG TE BEVESTIGEN (Stappenplan) --}}
                                    @if (session('status') === 'two-factor-authentication-enabled' || (auth()->user()->two_factor_secret && ! auth()->user()->two_factor_confirmed_at))
                                        <div class="alert alert-info p-3 mb-4">
                                            <p class="fw-bold mb-1">{{ __('pages/account-settings/two-factor-authentication.setup.title') }}</p>
                                            <p class="mb-0">{{ __('pages/account-settings/two-factor-authentication.setup.info') }}</p>
                                        </div>

                                        {{-- STAP 1: SCAN QR CODE --}}
                                        <h6 class="fw-bold mt-4">{{ __('pages/account-settings/two-factor-authentication.scan-step.title') }}</h6>
                                        <p class="mb-2">{{ __('pages/account-settings/two-factor-authentication.scan-step.text') }}</p>

                                        <div class="mb-2">
                                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                                        </div>

                                        <div class="mb-4">
                                            <p class="fw-semibold text-muted small">
                                                {{ __('pages/account-settings/two-factor-authentication.setup.key', ['key' => decrypt(auth()->user()->two_factor_secret)]) }}
                                            </p>
                                        </div>

                                        {{-- STAP 2: HERSTELCODES (Wordt overgeslagen als codes net zijn geregenereerd in Sectie 4) --}}
                                        @if (!$codesJustRegenerated)
                                            <h6 class="fw-bold mt-4">{{ __('pages/account-settings/two-factor-authentication.recovery-code-step.title') }}s</h6>
                                            <p class="mb-2">
                                                {!! __('pages/account-settings/two-factor-authentication.recovery-code-step.text') !!}
                                            </p>

                                            <div id="recovery-codes-container"
                                                 class="bg-sidenav p-3 border-0 rounded mb-3">
                                                @foreach ((array) auth()->user()->recoveryCodes() as $code)
                                                    {{ $code }}<br>
                                                @endforeach
                                            </div>

                                            <div class="d-flex mb-4">
                                                <button type="button" class="btn btn-outline-secondary me-2" onclick="copyRecoveryCodes()" id="copy-button-initial">
                                                    <x-heroicon-o-clipboard class="icon me-1"/>
                                                    {{ __('pages/account-settings/two-factor-authentication.buttons.copy-codes') }}
                                                </button>

                                                <button type="button" class="btn btn-outline-secondary" onclick="downloadRecoveryCodes()">
                                                    <x-heroicon-o-arrow-down-tray class="icon me-1"/>
                                                    {{ __('pages/account-settings/two-factor-authentication.buttons.download-codes') }}
                                                </button>
                                            </div>
                                        @endif

                                        {{-- STAP 3: BEVESTIGINGSFORMULIER --}}
                                        @if (!auth()->user()->two_factor_confirmed_at)
                                            <h6 class="fw-bold mt-4">{{ __('pages/account-settings/two-factor-authentication.confirmation-step.title') }}</h6>
                                            <p class="card-text my-2">
                                                {{ __('pages/account-settings/two-factor-authentication.confirmation-step.text') }}
                                            </p>

                                            <form action="{{ route('two-factor.confirm') }}" method="POST">
                                                @csrf

                                                <div class="d-flex align-items-center">
                                                    {{-- 1. Het Inputveld met de Foutklasse --}}
                                                    <input
                                                        id="code"
                                                        type="text"
                                                        placeholder="XXXXXX"
                                                        class="form-control w-75 me-2 @error('code', 'confirmTwoFactorAuthentication') is-invalid @enderror"
                                                        name="code"
                                                        required
                                                        autocomplete="one-time-code"
                                                        inputmode="numeric"
                                                        pattern="[0-9]*"
                                                        maxlength="6"
                                                    >

                                                    {{-- 2. De Knop --}}
                                                    <button type="submit" class="btn btn-submit w-25">
                                                        <x-heroicon-o-check class="icon me-1"/> {{ __('pages/account-settings/two-factor-authentication.buttons.confirm') }}
                                                    </button>
                                                </div>

                                                {{-- Weergave van de Foutmelding met de 'twoFactorAuth' error bag --}}
                                                @error('code', 'confirmTwoFactorAuthentication')
                                                    <p class="text-danger mt-1">{{ $message }}</p>
                                                @enderror
                                            </form>
                                        @endif
                                    @endif

                                    {{-- 2. INSCHAKELEN KNOP (Wanneer 2FA NOG NIET IS INGESTELD) --}}
                                    @if (! auth()->user()->two_factor_secret)
                                        <p class="card-text lh-sm text-muted my-3">
                                            {{ __('pages/account-settings/two-factor-authentication.status.inactive-text') }}
                                        </p>

                                        <form action="/user/two-factor-authentication" method="POST">
                                            @csrf {{--  form field protection --}}

                                            <button type="submit" class="btn btn-two-factor">
                                                <x-heroicon-o-lock-closed class="icon color-green me-1"/>
                                                {{ __('pages/account-settings/two-factor-authentication.buttons.activate') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 5. 🛠️ FOOTER MET ACTIES (Alleen tonen als 2FA is ingesteld EN bevestigd) --}}
                        {{-- DE FIX ZIT HIER: auth()->user()->two_factor_confirmed_at is de nieuwe voorwaarde --}}
                        @if (auth()->user()->two_factor_secret && auth()->user()->two_factor_confirmed_at)
                            <div class="card-footer border-0 bg-sidenav">
                                <div class="d-flex align-items-center">
                                    {{-- Knop voor het genereren van codes --}}
                                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary me-2">
                                            <x-heroicon-o-arrow-path class="icon me-1"/>
                                            {{ __('pages/account-settings/two-factor-authentication.buttons.regenerate-codes') }}
                                        </button>
                                    </form>

                                    {{-- Knop voor het deactiveren van 2FA --}}
                                    <form method="POST" action="{{ route('two-factor.disable') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <x-heroicon-o-x-mark class="icon me-1"/>
                                            {{ __('pages/account-settings/two-factor-authentication.buttons.deactivate') }}
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

@section('scripts')
    {{-- Script sectie (moet in @push('scripts') staan in een productiesetting) --}}
    <script>
        /**
         * Hulpmiddel om de tekst te kopiëren en de knoptekst tijdelijk te wijzigen.
         */
        function copyCodesHelper(elementId, buttonId) {
            const codesElement = document.getElementById(elementId);
            const copyButton = document.getElementById(buttonId);

            if (codesElement) {
                // Verwijder de <br> tags en trim de codes
                const codes = codesElement.innerText.trim();

                // Gebruik de moderne Clipboard API
                navigator.clipboard.writeText(codes).then(() => {
                    // Update knoptekst tijdelijk
                    const originalText = copyButton.innerHTML;
                    copyButton.innerHTML = 'Gekopieerd!';

                    setTimeout(() => {
                        copyButton.innerHTML = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Kopiëren mislukt:', err);
                    alert('Kopiëren mislukt. Kopieer de code s handmatig.');
                });
            }
        }

        /**
         * Downloadt de codes als een .txt-bestand.
         */
        function downloadHelper(elementId) {
            const codesElement = document.getElementById(elementId);
            if (codesElement) {
                // Verwijder de <br> tags en trim de codes
                const codes = codesElement.innerText.trim();

                // Creëer een Blob en download deze
                const blob = new Blob([codes], {type: 'text/plain'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'herstelcodes-' + new Date().toISOString().slice(0, 10) + '.txt';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        }

        // Functies voor initiële codes
        function copyRecoveryCodes() {
            copyCodesHelper('recovery-codes-container', 'copy-button-initial');
        }

        function downloadRecoveryCodes() {
            downloadHelper('recovery-codes-container');
        }

        // Functies voor geregenereerde codes
        function copyRegeneratedCodes() {
            copyCodesHelper('recovery-codes-container-regenerated', 'copy-button-regenerated');
        }

        function downloadRegeneratedCodes() {
            downloadHelper('recovery-codes-container-regenerated');
        }
    </script>
@endsection
