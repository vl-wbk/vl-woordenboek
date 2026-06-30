@extends('layouts.application-blank', ['title' => __('pages/region-information.title')])


@section('jumbotron')
    <header class="position-relative py-5 border-bottom bg-white">
        <div class="container-fluid position-relative z-1">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb mb-0 small text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none text-muted">Vlaams Woordenboek</a>
                            </li>
                            <li class="breadcrumb-item active text-warning" aria-current="page">{{ __('pages/region-information.title') }}</li>
                        </ol>
                    </nav>
                    <div class="mb-3">
                        <h1 class="display-5 fw-bold mb-2 text-dark tracking-tight">
                            {{ __('pages/region-information.title') }}
                        </h1>
                    </div>
                    <p class="text-secondary mb-0 lh-lg" style="font-size: 1.05rem; max-width: 800px;">
                        Ontdek de taalkundige diversiteit op de kaart en bekijk hoe dialectregio's en overgangsgebieden verspreid liggen over Vlaanderen. Zoek uw eigen gemeente of verken de provincies.
                    </p>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="container-fluid py-5 bg-light">
        <div class="row justify-content-center">
            <div class="col-xl-10">

                {{-- Interactieve Kaart met nieuwe Controls --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5 position-relative" id="map-container">

                    <div class="position-absolute top-0 start-0 m-3 z-3 w-100 d-flex justify-content-between pe-4 pointer-events-none">
                        {{-- Zoekbalk --}}
                        <div class="pointer-events-auto" style="width: 300px; max-width: 90vw;">
                            <div class="input-group shadow-sm bg-white rounded">
                                <span class="input-group-text bg-white border-0 text-muted">
                                    <x-heroicon-o-magnifying-glass class="icon"/>
                                </span>

                                <input type="text" id="gemeenteZoeker" class="form-control border-0 py-2" placeholder="Zoek een gemeente..." autocomplete="off">

                                {{-- De Reset Knop: toegevoegd binnen de input-group --}}
                                <button id="clearSearch" class="btn btn-white border-0 text-muted d-none" type="button">
                                    <x-heroicon-o-x-circle class="icon text-danger"/>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div id="map" style="height: 600px; width: 100%; z-index: 1;"></div>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="regioDetailCanvas" aria-labelledby="regioDetailLabel">
                            <div class="offcanvas-header bg-light">
                                <h5 class="offcanvas-title fw-bold" id="regioDetailLabel">Regio Informatie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>

                            <div class="offcanvas-body" id="regioDetailContent"></div>
                        </div>

                        <div class="bg-dark text-white p-3 text-center small fw-semibold tracking-wider text-uppercase d-flex justify-content-center align-items-center gap-2">
                            <x-heroicon-o-cursor-arrow-rays class="icon icon-sm text-warning"/>
                            Interactieve overzichtskaart van de taalkundige regio's
                        </div>
                    </div>

                    {{-- Informatie & Interactieve Regiolijst --}}
                    <div class="row g-5">

                    {{-- Rechterkolom: Interactieve Regiolijst --}}
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                            <h5 class="fw-bold mb-4 text-dark border-bottom border-light pb-3 d-flex align-items-center justify-content-between">
                                Regiolijst

                                <span class="badge bg-info-subtle text-info-emphasis fw-normal small px-2 py-1">
                                    <x-heroicon-o-information-circle class="icon icon-sm me-1"/>Hover om uit te lichten
                                </span>
                            </h5>

                            <div class="row g-3" id="interactive-legend">
                                {{-- Gebruik col-lg-4 col-md-6 voor een strakkere verdeling --}}
                                @php
                                    $regions = [
                                        ['title' => 'Provincie West-Vlaanderen', 'data' => 'Westelijk West-Vlaanderen,Noord-West-Vlaanderen,Binnen-West-Vlaanderen', 'text' => 'Westelijk, Noord-West, Binnen-West-Vlaanderen'],
                                        ['title' => 'Provincie Oost-Vlaanderen', 'data' => 'Oost-Vlaanderen,Waasland,Zuid-Brabant', 'text' => 'Oost-Vlaanderen, Waasland, (stuk) Zuid-Brabant'],
                                        ['title' => 'Provincie Antwerpen', 'data' => 'Noordwest-Brabant,Kempen,Oost-Noord-Brabant', 'text' => 'Noordwest-Brabant, Kempen, Oost-Noord-Brabant'],
                                        ['title' => 'Provincie Vlaams-Brabant', 'data' => 'Zuid-Brabant,Geteland', 'text' => 'Zuid-Brabant, Geteland'],
                                        ['title' => 'Provincie Limburg', 'data' => 'West-Limburg,Truierland,Beringerland,Centraal Limburg en Maasland,Oost-Limburg', 'text' => 'West-Limburg, Truierland, Beringerland, Centraal- en Maasland, Oost-Limburg'],
                                        ['title' => 'Overgangsregio(s)', 'data' => 'West-Vlaanderen>Oost-Vlaanderen,Oost-Vlaanderen>Brabant', 'text' => 'West-Vlaanderen>Oost-Vlaanderen,Oost-Vlaanderen>Brabant', 'class' => 'text-warning']
                                    ];
                                @endphp

                                @foreach($regions as $region)
                                <div class="col-lg-4 col-md-6">
                                    <div class="region-group" data-regions="{{ $region['data'] }}">
                                        <div class="small fw-bold text-uppercase tracking-wider text-muted mb-0 {{ $region['class'] ?? '' }}">
                                            {{ $region['title'] }}
                                        </div>
                                        <div class="text-dark small lh-sm">{{ $region['text'] }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Linkerkolom: Theorie --}}
                    <div class="col-lg-12">
                        <div class="bg-white p-4 rounded-4 shadow-sm h-100 border-0">
                            <h4 class="fw-bold mb-3 text-dark border-bottom border-light pb-2">Over de indeling</h4>

                            <div class="text-secondary lh-base">
                                <p class="mb-3">
                                    {{ __('pages/region-information.first-sentence') }}
                                    {{ __('pages/region-information.second-sentence') }}
                                    {{ __('pages/region-information.third-sentence') }}
                                </p>
                                <p class="mb-3">
                                    {{ __('pages/region-information.second-paragraph.first_sentence') }}
                                    {{ __('pages/region-information.second-paragraph.second-sentence') }}
                                    {{ __('pages/region-information.second-paragraph.third-sentence') }}
                                    {{ __('pages/region-information.second-paragraph.fourth-sentence') }}
                                    {{ __('pages/region-information.second-paragraph.fifth-sentence') }}
                                </p>
                                <p class="mb-0">
                                    {{ __('pages/region-information.third-paragraph.first-sentence') }}
                                    {{ __('pages/region-information.third-paragraph.second-paragraph') }}
                                    {!! __('pages/region-information.third-paragraph.third-sentence') !!}
                                    {!! __('pages/region-information.third-paragraph.fourth-sentence') !!}
                                    {!! __('pages/region-information.third-paragraph.fifth-sentence') !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .tracking-wider { letter-spacing: 0.05em; }
        .tracking-tight { letter-spacing: -0.025em; }
        .pointer-events-none { pointer-events: none; }
        .pointer-events-auto { pointer-events: auto; }

        .leaflet-tile { border-color: transparent; }
        .leaflet-container path.leaflet-interactive:focus:not(:focus-visible) { outline: 0; }

        .leaflet-top.leaflet-left { display: none; } /* Verberg standaard top-left controls */

        /* Zorg dat de kaart-container zelf een relatieve positie heeft voor de overlay */
        #map-container { position: relative; }

        /* 2. Fix voor de 'sticky' regiolijst */
        .sticky-column {
            position: -webkit-sticky;
            position: sticky;
            top: 2rem; /* Ruimte vanaf de bovenkant bij scrollen */
            height: fit-content;
        }

        /* Fullscreen mode fix */
        .map-fullscreen-mode {
            position: fixed !important;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 9999 !important;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 1px solid #f8f9fa;
        }
        .leaflet-popup-content { margin: 1.25rem; font-family: inherit; }
        .leaflet-container a.leaflet-popup-close-button { padding: 8px 8px 0 0; color: #6c757d; }

        /* Fullscreen styles */
        .map-fullscreen-mode {
            position: fixed !important;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw !important; height: 100vh !important;
            z-index: 9999 !important;
            border-radius: 0 !important;
        }
        .map-fullscreen-mode #map { height: 100% !important; }

        /* Interactieve Legend Hover Effect */
        .region-group { transition: all 0.2s ease; padding: 0.5rem; border-radius: 0.5rem; cursor: pointer; }
        .region-group:hover { background-color: #f8f9fa; }
    </style>
@endsection



@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('gemeenteZoeker');
            const searchFeedback = document.getElementById('zoekResultaat');
            const clearBtn = document.getElementById('clearSearch');

            let geojsonLayer = null;
            let isSearching = false;

            // 1. Map Initialisatie
            const map = L.map('map', { scrollWheelZoom: false }).setView([51.0500, 4.3517], 9);
            map.zoomControl.remove();
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 12,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const regionColors = { 3: '#059669', 4: '#4F46E5', 5: '#7C3AED', 6: '#FC4E2A', 7: '#FD8D3C', 8: '#93C5FD', 9: '#831843', 10: '#5C4033', 11: '#5E5820', 12: '#3A4C7A', 13: '#551199', 14: '#447755', 15: '#FF4400', 16: '#55EE33', 17: '#003333', 18: '#000000', 19: '#580F1A' };

            // 2. Data ophalen
            fetch('/api/geo-data')
                .then(response => response.ok ? response.json() : Promise.reject('Netwerkfout'))
                .then(geojsonData => {
                    if (!geojsonData) return;

                    geojsonLayer = L.geoJSON(geojsonData, {
                        style: (feature) => ({
                            color: regionColors[feature.properties.region_id] || '#FFEDA0',
                            weight: 2, opacity: 0.8, fillColor: regionColors[feature.properties.region_id] || '#FFEDA0', fillOpacity: 0.25
                        }),

                        onEachFeature: (feature, layer) => {
                            layer.bindPopup(`<strong>Gemeente:</strong> ${feature.properties.name}<br><strong>Regio:</strong> ${feature.properties.region_name}`);

                            layer.on({
                                mouseover: (e) => {
                                    // Pas alleen hover-stijl toe als we NIET in zoekmodus zijn
                                    // OF als dit toevallig de gevonden laag is (optioneel)
                                    if (!isSearching) {
                                        e.target.setStyle({ fillOpacity: 0.7, weight: 4 });
                                    }
                                },
                                mouseout: (e) => {
                                    if (!isSearching) {
                                        geojsonLayer.resetStyle(e.target);
                                    }
                                }
                            });
                        }
                    }).addTo(map);

                    map.fitBounds(geojsonLayer.getBounds(), { padding: [10, 10] });

                    // 2b. LEGENDA INTERACTIE (Nu correct binnen de scope)
                    document.querySelectorAll('.region-group').forEach(group => {
                        group.addEventListener('mouseenter', function() {
                            // Reset zoekmodus bij hover over legenda
                            if (isSearching) {
                                isSearching = false;
                                if (searchInput) searchInput.value = '';
                                if (clearBtn) clearBtn.classList.add('d-none');
                                map.flyToBounds(geojsonLayer.getBounds(), { duration: 0.25 });
                            }

                            const targetRegions = this.getAttribute('data-regions').toLowerCase().split(',');
                            geojsonLayer.eachLayer(layer => {
                                const isMatch = targetRegions.some(tr => layer.feature.properties.region_name.toLowerCase().includes(tr.trim()));
                                layer.setStyle({ fillOpacity: isMatch ? 0.6 : 0.05, weight: isMatch ? 3 : 1, opacity: isMatch ? 0.8 : 0.2 });
                            });
                        });

                        group.addEventListener('mouseleave', () => geojsonLayer.resetStyle());
                    });
                });

            // 3. Gemeente Zoeker
            const normalize = (str) => str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    const query = normalize(e.target.value.trim());

                    if (clearBtn) clearBtn.classList.toggle('d-none', query.length === 0);
                    if (searchFeedback) searchFeedback.classList.add('d-none');
                    if (query.length < 2) return;

                    searchTimeout = setTimeout(() => {
                        let foundLayer = null;
                        let exactMatchLayer = null;

                        geojsonLayer.eachLayer(layer => {
                            const naam = normalize(layer.feature.properties.name);
                            if (naam.includes(query)) {
                                if (!foundLayer) foundLayer = layer;
                                if (naam === query || naam.startsWith(query + "-") || naam.startsWith(query + " ")) exactMatchLayer = layer;
                            }
                        });

                        const result = exactMatchLayer || foundLayer;
                        if (result) {
                            isSearching = true;
                            map.flyToBounds(result.getBounds(), { maxZoom: 12, duration: 0.25 });
                            setTimeout(() => result.openPopup(), 600);
                            geojsonLayer.resetStyle();
                            result.setStyle({ fillOpacity: 0.6, weight: 4, color: '#FF0000' });
                        } else if (searchFeedback) {
                            searchFeedback.classList.remove('d-none');
                        }
                    }, 400);
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    clearBtn.classList.add('d-none');
                    isSearching = false;
                    map.flyToBounds(geojsonLayer.getBounds(), { duration: 1 });
                    geojsonLayer.resetStyle();
                });
            }
        });
    </script>
@endsection
