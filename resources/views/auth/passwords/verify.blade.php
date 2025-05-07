@extends('layouts.application-blank', ['title' => 'Emailadres verifieren'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 text-center bg-white">
                @if (session('status') == 'verification-link-sent')
                    <div class="card-header bg-success text-white">
                        <x-heroicon-o-check class="icon me-1"/> We hebben u juist een nieuwe verificatiemail gestuurd!
                    </div>
                @endif

                <div class="card-body p-4">
                    <x-heroicon-o-exclamation-triangle class="icon icon-blankslate text-danger pb-3"/>

                    <h5 class="card-title fw-bold">Nog één kleinigheidje</h5>

                    <p class="card-text pb-3 text-muted">
                        We hebben u bij het registreren al een mail gestuurd. klik op de link daarin om te tonen dat jij het echt zijt.
                        Het is gewoon om misbruik en zever te vermijden.
                        Eens dat in orde is, kunt ge stemmen, woorden toevoegen en alles gebruiken zoals het hoort met je account.
                        Geen mail gezien? Kijk eens in uw spam of vraag een nieuwe op.
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-submit">{{ __('Verificatie link verzenden') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
