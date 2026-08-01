@extends ('layouts.application-blank', ['title' => 'Registreren'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <x-heroicon-s-user-plus class="icon"/> {{ __('Account aanmaken op het Vlaams Woordenboek') }}
                </div>

                <div class="card-body rounded-start rounded-end bg-white">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <x-honeypot />

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Gebruikersnaam') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="gebruikersnaam" value="{{ old('name') }}" required autocomplete="given-name" autofocus>
                                <x-forms.validation-error field="gebruikersnaam"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Adres') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                <x-forms.validation-error field="email"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Wachtwoord') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <x-forms.validation-error field="password"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Bevestig wachtwoord') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('agreement') is-invalid @enderror" type="checkbox" name="agreement" id="agreement" {{ old('agreement') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="agreement">
                                        Ik ga akkoord met de <a href="{{ route('terms-of-service') }}" target="_blank">algemene voorwaarden</a>
                                    </label>

                                    <x-forms.validation-error field="agreement"/>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-submit">
                                    {{ __('Registreren') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
