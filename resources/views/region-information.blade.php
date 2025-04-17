@extends('layouts.application-blank', ['title' => 'Regio informatie'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold card-title pb-1 text-gold border-bottom">Vlaamse provincies en regio's</h4>

                        <p class="card-text">
                            Het online Vlaamse woordenboek wil op de eerste plaats een verzameling bouwen van "Algemeen Beschaafd Vlaamse" woorden, woorden die door zo goed als elke Vlaming worden begrepen.
                            Het Vlaamse is echter sterk versnippeld in een grote varieteit aan dialecten.
                            Dialecttermen nemen een belangrijke taak op in het dagelijks gesproken Vlaams, en krijgen daarom ook hun plaats in het online Vlaams woordenboek.
                            ls ge als gebruiker een Vlaamse termin in onze databank steekt, kunt ge bij uw beschrijving aangeven uit welke streek de dialectterm afkomstig is.
                        </p>

                        <p class="card-text mb-0">
                            De onderstaande kaarten geven de regio's weer die in het Vlaams woordenboek worden onderscheiden
                        </p>

                        <img src="{{ asset('img/vlaamse_provincies_en_regios.png') }}" class="img-fluid my-3" alt="Kaartje met een oerzicht van Vlaamse provincies en Regios">

                        <p class="card-text">
                            Er zijn 5 overheersende Vlaamse dialecten, wier streken van oorsprong sterk overeenkomen met de provinciegrenzen; West-Vlaams, Oost-Vlaams, Antwerps, Brabants en Limburgs.
                        </p>

                        <p class="card-text">
                            Daarnaast zijn er binnen elke provincie lokale regio's met hun eigen dialect.
                            Twee naburige dorpen kunnen sterke verschillen in hun dialect vertonen.
                            Om het woordenboek echter niet te overladen met keuzemogelijkheden, beperken we de classificatie tot dialecten, zoals gesproken in hun overeenkomstige toeristische regio's van Vlaanderen.
                            De volgende regio's zijn opgenomen in het Vlaams woordenboek.
                        </p>

                        <hr>

                        <dl class="row mb-0">
                            <dt class="col-sm-4">Provincie West-Vlaanderen</dt>
                            <dd class="col-sm-8">Kust-West-Vlaams, Westhoeks, Centraal West-Vlaams, Kortijk-Ieper, West-Oost-Vlaamse overgangszone</dd>
                            <dt class="col-sm-4">Provincie Oost-Vlaanderen</dt>
                            <dd class="col-sm-8">Oost-Vlaams (centraal), Waasland, Denderstreek, Vlaams-Brabantse overgangszone, Gents</dd>
                            <dt class="col-sm-4">Provincie Vlaams-Brabant</dt>
                            <dd class="col-sm-8">Pajottenlands, Kleinbrabants, Brussels, (Centaal-)Brabants, Hagelands, Brabantse-Limburgse overgangszone (Diest - Tienen - Sint-Truiden)</dd>
                            <dt class="col-sm-4">Provincie Antwerpen</dt>
                            <dd class="col-sm-8">Noorderkempens, Zuiderkempens (incl. Tessenderlo - Kwaadmechelen - Ham), Antwerps (westen van de provincie), Antwerps (stad)</dd>
                            <dt class="col-sm-4">Provincie Limburg</dt>
                            <dd class="col-sm-8">Lommel, Noord-Limburgs, Centraal-Limburgs, Tongerlands, Truierlands, Maaslands, Citétaal (Genk) </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
