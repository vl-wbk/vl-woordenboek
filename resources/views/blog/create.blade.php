@extends('layouts.application-blank', ['title' => trans('Artikel toevoegen')])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">Schrijf een nieuw artikel</h3>
                </div>

                <div class="float-end">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar met knoppen met relatie op de artikel inzending">
                        <div class="btn-group border-0 shadow-sm me-2" role="group" aria-label="First group">
                            <button type="button" class="btn btn-light">Annuleren</button>
                            <button type="button" class="btn btn-light">Insturen</button>
                        </div>

                        <div class="btn-group border-0 shadow-sm" role="group" aria-label="Second group">
                            <a href="" class="btn btn-submit">
                                Mijn artikelen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-12">
                <p>
                    Heb jij een intressante insteek over de nederlands taal en hun gerleateerde onderwerpen en wil je iets delen met ons publiek?
                    Via dit onderstaande formulier kan je het delen met de gebruikers van het Vlaams Woordenboek. <strong>De artikelen mogen niet van promotionele aard zijn</strong>
                    en moeten van educatieve of informatieve aard zijn. We behouden ons het recht om artikelen die afwijken van deze norm te verwijderen en niet te publiceren.
                </p>

                <p class="my-2">Elk artikel zal worden publiceerd met onze gebruiker.</p>

                <p>
                    Nadat artikelen ter goedkeuring zijn ingediend, worden ze beoordeeld voordat ze worden gepubliceerd.
                    Er wordt geen melding verzonden van afgewezen artikelen. In plaats daarvan raden we aan om artikelen ook op je eigen kanaal te crossposten.
                    Na publicatie kun je je artikel niet meer bewerken, dus controleer het grondig voordat je het ter goedkeuring indient.
                    Het twee keer indienen van hetzelfde artikel of het plaatsen van spam leidt tot een blokkering van je account. We accepteren geen e-mailverzoeken voor gastartikelen.
                    Een dergelijk verzoek wordt genegeerd.
                </p>
            </div>

            <div class="col-12">
                <form method="POST" action="" class="card card-body border-0 shadow-sm bg-white">
                    @csrf {{-- Form protection --}}
                </form>
            </div>
        </div>
    </div>
@endsection
