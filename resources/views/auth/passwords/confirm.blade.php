@extends('layouts.application-blank', ['title' => 'Emailadres verifieren'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold border-bottom pb-2">
                        <x-heroicon-o-shield-exclamation class="icon text-danger me-1" style="height: 1.3rem; width: 1.3rem;"/>
                        {{ __('U staat op een punt om een beveiligde handeling uit te voeren') }}
                    </h5>

                    <p class="card-text my-3 text-muted">
                        Gezien de handling of configuratie die je probeerd uit te voeren, vragen we je om hieronder je wachtwoord in voeren.
                        Om zeker te zijn dat de eigenaar van het account deze handeling aan het uitvoeren is. De bevestiging is geldig voor een paar uur.
                    </p>

                    <form method="POST" class="border-top" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row my-3">
                            <div class="col-md-12">
                                <input id="password" placeholder="Uw huidig wachtwoord" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit">
                                    <x-heroicon-o-paper-airplane class="icon me-1"/>{{ __('Wachtwoord bevestigen') }}
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
