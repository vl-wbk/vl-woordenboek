@extends('layouts.application-blank', ['title' => 'Regio informatie'])

@section ('content')
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div id="map" style="height: 400px;" class="card-header border-bottom-0"></div>
                    <div class="card-body">
                        <h4 class="fw-bold card-title pb-1 text-gold border-bottom">De Vlaamse dialectregio’s</h4>

                        <p class="card-text mb-2">
                            Het online Vlaamse woordenboek is in de eerste plaats een verzameling van woorden die algemeen bekend zijn in Vlaanderen.
                            De meeste ervan krijgen het label ‘Belgisch-Nederlands’ (in woordenboeken als Van Dale) of ‘standaardtaal in België’ (op Taaladvies.net en bij Team Taaladvies van de Vlaamse overheid).
                            Andere worden vooral in gesproken taal gebruikt of worden ondanks hun ruime verspreiding toch niet als ‘standaardtaal’ beschouwd.
                        </p>

                        <p class="card-text mb-2">
                            Maar er zijn ook heel wat woorden waarvan gebruikers denken dat ze algemeen zijn, terwijl ze maar in een deel van Vlaanderen voorkomen.
                            En er zijn er waarvan algemeen geweten is dat hun gebruik geografisch beperkt is, maar die te mooi zijn om te laten liggen.
                            Hoewel het Vlaams Woordenboek geen dialectwoordenboek is in de strikte zin van het woord, verdienen ook die woorden een plaatsje in het woordenboek.
                            Die woorden krijgen een of meerdere regiolabels, zodat gebruikers weten in welke regio die woorden thuishoren. De lijst met regiolabels, gebaseerd op een indeling uit de dialectologie, staat hieronder.
                            Als je op de interactieve kaart hierboven op een gemeente klikt, weet je meteen tot welke regio die hoort.
                        </p>

                        <p class="card-text">
                            Hoewel we dus ook dialectwoorden opnemen, blijven die de uitzondering op de regel. We willen nog eens benadrukken dat het Vlaams Woordenboek géén dialectwoordenboek is.
                            Hoe mooi en rijk we onze dialectwoordenschat ook vinden, hiervoor bestaan er al tal van andere bronnen.
                            Veel dorpen en steden hebben intussen een eigen woordenboek en een groot deel daarvan zijn online te vinden in de Woordenbank van het <a href="https://www.dialectloket.be/woord/woordenbank-van-de-nederlandse-dialecten/">Dialectloket</a>.
                            Een andere interessante site is de Database van de Zuidelijk Nederlandse Dialecten van het Instituut voor de <a href="https://ivdnt.org/woordenboeken/dsdd/">Nederlandse taal</a>.
                            Op <a href="https://www.mijnwoordenboek.nl/dialecten/">https://www.mijnwoordenboek.nl/dialecten/</a> ten slotte kun je zelf een bijdrage leveren.
                        </p>

                        <hr>

                        <h5 class="fw-bold card-title pb-1 text-gold">Regiolijst</h5>

                        <dl class="row mb-0">
                            <dt class="col-sm-4">Provincie West-Vlaanderen:</dt>
                            <dd class="col-sm-8">Westelijk West-Vlaanderen, Noord-West-Vlaanderen, Binnen-West-Vlaanderen</dd>
                            <dt class="col-sm-4">Provincie Oost-Vlaanderen:</dt>
                            <dd class="col-sm-8">Oost-Vlaanderen, Waasland, (een stuk) Zuid-Brabant</dd>
                            <dt class="col-sm-4">Provincie Antwerpen:</dt>
                            <dd class="col-sm-8">Noordwest-Brabant, Kempen, Oost-Noord-Brabant</dd>
                            <dt class="col-sm-4">Provincie Vlaams-Brabant:</dt>
                            <dd class="col-sm-8">Zuid-Brabant, Geteland</dd>
                            <dt class="col-sm-4">Provincie Limburg:</dt>
                            <dd class="col-sm-8">West-Limburg, Truierland, Beringerland, Centraal Limburg en Maasland, Oost-Limburg</dd>
                            <dt class="col-sm-4">Overgangsregio(s):</dt>
                            <dd class="col-sm-8">West-Vlaanderen>Oost-Vlaanderen, Oost-Vlaanderen>Brabant</dd>
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
                    function getColor(d) {
                        return d == 3 ? '#059669' :
                            d == 4  ? '#4F46E5' :
                            d == 5  ? '#7C3AED' :
                            d == 6  ? '#FC4E2A' :
                            d == 7   ? '#FD8D3C' :
                            d == 8   ? '#93C5FD' :
                            d == 9   ? '#831843' :
                            d == 10 ? '#5C4033' :
                            d == 11 ? '	#5E5820' :
                            d == 12 ? '	#3A4C7A' :
                            d == 13 ? '	#519' :
                            d == 14 ? '	#475' :
                            d == 15 ? '	#F40' :
                            d == 16 ? '#5E3' :
                            d == 17 ? '#033' :
                            d == 18 ? '#000' :
                            d == 19 ? '	#580F1A' :
                                        '#FFEDA0';
}

                     var geojsonLayer = L.geoJSON(geojsonData, {
                        // Optional: Style the polygon
                        style: function (feature) {
                            return {
                                color: getColor(feature.properties.region_id), // Border color (blue)
                                weight: 1,         // Border thickness
                                opacity: 1,      // Border opacity
                                fillColor: getColor(feature.properties.region_id), // Fill color (blue)
                                fillOpacity: 0.1   // Fill opacity
                            };
                        },
                        // Optional: Add popups or other interactions
                        onEachFeature: function (feature, layer) {
                                layer.bindPopup("<strong>Gemeente(s):</strong><br>" + feature.properties.name + "<br><br><strong>Taalkundige regio: </strong><br>" + feature.properties.region_name)


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
