@extends('layouts.application-blank', ['title' => '2FA authenticatie'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-yellow">
                    Two-Factor Authentication
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger border-0" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- Form for TOTP Code --}}
                    <form method="POST" action="{{ route('two-factor.login') }}" id="totp-form">
                        @csrf

                        <p class="text-muted card-text pb-2 border-bottom">
                            Voer in het onderstaande formulier de <strong>6 cijferige code</strong> in van je authenticator applicatie. Moest je toegang tot het apperaat verloren zijn kun je nog altijd aanmelden via een herstelcode.
                        </p>

                        <div class="my-3">
                            <input id="code" type="text" placeholder="XXXXXX" class="form-control" name="code" required autofocus autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" maxlength="6">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            {{-- 1. Verify & Log In Button (Primary Action) --}}
                            <button type="submit" class="btn btn-submit flex-grow-1 w-50 me-2">
                                Verifieer & Log in
                            </button>

                            {{-- 2. Use a Recovery Code Link/Button (Secondary Action) --}}
                            <a href="#" class="btn btn-two-factor w-50" id="recovery-link" onclick="toggleForm()">
                                Gebruik een herstelcode
                            </a>
                        </div>
                    </form>

                    {{-- Form for Recovery Code (Hidden by default) --}}
                    <form method="POST" action="{{ route('two-factor.login') }}" id="recovery-form" style="display:none;">
                        @csrf

                        <p class="text-muted card-text pb-2 border-bottom">
                            Voer een van je herstelcodes in om je aan te melden in het Vlaams Woordenboek. Na gebruik van de herstelcode zal deze verwijderd worden.
                        </p>

                        <div class="my-3">
                            <input id="recovery_code" type="text" class="form-control" name="recovery_code" required placeholder="xxxxxxxxxx-xxxxxxxxxx" autocomplete="one-time-code">
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            {{-- 1. Verify & Log In Button (Primary Action) --}}
                            <button type="submit" class="btn btn-submit flex-grow-1 w-50 me-2">
                                Gebruik herstelcode
                            </button>

                            {{-- 2. Use a Recovery Code Link/Button (Secondary Action) --}}
                            <a href="#" class="btn btn-two-factor w-50" id="recovery-link" onclick="toggleForm()">
                                Gebruik authenticator code
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        const totpForm = document.getElementById('totp-form');
        const recoveryForm = document.getElementById('recovery-form');

        if (totpForm.style.display === 'none') {
            // Switch to TOTP form
            totpForm.style.display = 'block';
            recoveryForm.style.display = 'none';
        } else {
            // Switch to Recovery form
            totpForm.style.display = 'none';
            recoveryForm.style.display = 'block';
        }
    }
</script>
@endsection
