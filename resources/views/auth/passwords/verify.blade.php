@extends('layouts.application-blank', ['title' => 'Emailadres verifieren'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 text-center bg-white">
                @if (session('status') == 'verification-link-sent')
                    <div class="card-header bg-success text-white">
                        <x-heroicon-o-check class="icon me-1"/> {{ __('We hebben je juist een nieuwe verificatiemail gestuurd!') }}
                    </div>
                @endif

                <div class="card-body p-4">
                    <x-heroicon-o-exclamation-triangle class="icon icon-blankslate text-danger pb-3"/>

                    <h5 class="card-title fw-bold">{{ __('Nog één kleinigheidje') }}</h5>

                    <p class="card-text pb-3 text-muted">
                        {{ __('We hebben je bij het registreren al een mail gestuurd. Klik op de link daarin om te bevestigen dat jij het echt bent. Op die manier voorkomen we misbruik, spammers en andere onnozelaars.') }}
                        {{ __('Zodra dat in orde is, kun je stemmen, suggesties geven, woorden bewaren en allerlei andere plezante dingen. Geen mail gezien? Kijk eens in je spamfolder of vraag een nieuwe mail met verificatielink op.') }}
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-submit">{{ __('Nieuwe mail met link verzenden') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
