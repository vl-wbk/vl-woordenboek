@extends('layouts.application-blank', ['title' => 'Wachtwoords configureren'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <x-tabler-login-2 class="icon me-1" /> {{ __('Aanmelden bij het vlaams woordenboek') }}
                </div>

                <div class="card-body bg-white rounded-start rounded-end">
                    @if (session('status'))
                        <div class="alert alert-success border-0 py-2" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ $user->email }}"/>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <x-forms.validation-error field="password"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Wachtwoord bevestiging') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="offset-md-4 col-md-6">
                                <button type="submit" class="btn btn-submit border-0">
                                    {{ __('Wachtwoord opslaan & aanmelden') }}
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
