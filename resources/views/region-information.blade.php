@extends('layouts.application-blank', ['title' => 'Regio informatie'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div id="map" style="height: 400px;" class="card-header border-bottom-0"></div>
                    <div class="card-body">
                        <h4 class="fw-bold card-title pb-1 text-gold border-bottom">Vlaamse provincies en regio's</h4>

                        <p class="card-text mb-2">
                            Het online Vlaamse woordenboek wil op de eerste plaats een verzameling bouwen van "Algemeen Beschaafd Vlaamse" woorden, woorden die door zo goed als elke Vlaming worden begrepen.
                            Het Vlaamse is echter sterk versnippeld in een grote varieteit aan dialecten.
                            Dialecttermen nemen een belangrijke taak op in het dagelijks gesproken Vlaams, en krijgen daarom ook hun plaats in het online Vlaams woordenboek.
                            als ge als gebruiker een Vlaamse termin in onze databank steekt, kunt ge bij uw beschrijving aangeven uit welke streek de dialectterm afkomstig is.
                        </p>

                        <p class="card-text mb-2">
                            De bovenstaande interactieve kaart geeft elke gemeente in Vlaanderen aan. Indien u het woord ergens hebt gehoord of niet weet uit welke regio het komt. Kun je de gemeente opzoeken in de kaart om meer info te vinden omtrent de regio.
                        </p>

                        <p class="card-text mb-2">
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

@section('scripts')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
<style>
    .leaflet-tile { border-color: transparent; }
.leaflet-container path.leaflet-interactive:focus:not(:focus-visible) {
  outline: 0;
}
</style>

<script>
        // Initialize the map
        var map = L.map('map', {
}).setView([50.8503, 4.3517], 10); // Set initial view (approx. Brussels center)

        // Add a base tile layer (e.g., OpenStreetMap)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // URL of your Laravel API endpoint
        const geoApiUrl = '/api/geo-data'; // Adjust if your API path is different

        // Fetch the GeoJSON data from the backend
        fetch(geoApiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse the JSON response
            })
            .then(geojsonData => {
                // Data fetched successfully, add it to the map
                if (geojsonData && geojsonData.type === "FeatureCollection") {
                     var geojsonLayer = L.geoJSON(geojsonData, {
                        // Optional: Style the polygon
                        style: function (feature) {
                            return {
                                color: '#ca8a04', // Border color (blue)
                                weight: 1,         // Border thickness
                                opacity: 1,      // Border opacity
                                fillColor: '#eab308', // Fill color (blue)
                                fillOpacity: 0.1   // Fill opacity
                            };
                        },
                        // Optional: Add popups or other interactions
                        onEachFeature: function (feature, layer) {
                                layer.bindPopup("<strong>Gemeente(s):</strong><br>" + feature.properties.name + "<br><br><strong>Taalkundige regio: </strong><br>" + feature.properties.region)


                        }
                    }).addTo(map);

                    // Optional: Fit the map view to the bounds of the GeoJSON layer
                    map.fitBounds(geojsonLayer.getBounds());

                } else {
                     console.error("Invalid GeoJSON data received:", geojsonData);
                }
            })
            .catch(error => {
                console.error("Error fetching geo data:", error);
            });

    </script>
@endsection
