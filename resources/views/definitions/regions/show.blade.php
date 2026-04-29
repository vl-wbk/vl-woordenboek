@extends ('layouts.application-blank', ['title' => 'Regio informatie'])

@section('jumbotron')
    <style>
    /* Zorg dat de kaart op desktop de volledige hoogte van de tekstkolom vult */
    @media (min-width: 992px) {
        .col-lg-5 .card {
            height: 100% !important;
            min-height: 300px;
        }
    }
    
    /* Smooth transition voor hover effecten op badges */
    .badge.transition-all:hover {
        background-color: rgba(220, 53, 69, 0.2) !important;
    }

    /* Focus styles voor een naadloze look */
    .input-group-merge .form-control:focus {
        background-color: #f1f3f5 !important;
    }
    
    /* Subtiele interactie op de knop */
    .hover-lift-sm:hover {
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2) !important;
    }

    /* Badge styling */
    .badge-filter {
        border: 1px solid rgba(0,0,0,0.05);
        font-size: 0.8rem;
    }

    .hover-opacity-100:hover {
        opacity: 1 !important;
    }

        .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .word-card:hover {
  
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
    .input-group-merge .form-control:focus {
        box-shadow: none;
        background-color: #f8f9fa !important;
    }
    .pagination { margin-bottom: 0; }

    /* Editorial tweaks */
    .fw-black { font-weight: 900; }
    .ls-1 { letter-spacing: 1px; }
    
    /* Maak de zijbalk subtieler op grotere schermen */
    @media (min-width: 992px) {
        aside {
            border-left: 1px solid #f0f0f0;
        }
    }
</style>

<div class="bg-white border-bottom shadow-sm">
    <div class="px-5">
        <div class="container-fluid py-4 py-lg-5">
            <div class="row g-4 flex-column-reverse flex-lg-row">
                <div class="col-lg-7">
                    <nav aria-label="breadcrumb" class="mb-2 d-none d-md-block">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item small"><a href="/" class="text-decoration-none">Woordenboek</a></li>
                            <li class="breadcrumb-item small active text-muted">regio: {{ $region->name }}</li>
                        </ol>
                    </nav>

                    <h1 class="display-5 fw-bold text-dark mb-3">
                        Regio: <span class="text-brand-green">{{ $region->name }}</span>
                    </h1>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge rounded-pill bg-white border text-dark px-3 py-2 shadow-sm">
                            <x-heroicon-s-book-open class="icon text-primary me-1" style="width:18px;"/>
                            {{ $relatedArticles->total() }} <span class="fw-normal d-none ms-1 d-sm-inline">woorden</span>
                        </span>

                        @if ($popularWord)
                            <a href="{{ route('word-information.show', $popularWord) }}" 
                            class="badge rounded-pill bg-danger bg-opacity-10 border border-danger border-opacity-25 text-danger px-3 py-2 text-decoration-none shadow-sm transition-all">
                                <x-heroicon-s-fire class="icon me-1" style="width:18px;"/>
                                <span class="fw-normal d-none d-sm-inline">Trending:</span> <strong>{{ $popularWord->word }}</strong>
                            </a>
                        @endif
                    </div>

                    <div class="pt-3 border-top d-none d-sm-block">
                        <p class="small text-muted mb-0">
                            <span class="me-3">
                                <x-heroicon-o-calendar class="icon me-1" style="width:14px;"/>
                                {{ $region->created_at->locale('nl_BE')->isoFormat('D MMM YYYY') }}
                            </span>
                            <span>
                                <x-heroicon-o-arrow-path class="icon me-1" style="width:14px;"/>
                                Update: {{ $region->updated_at->locale('nl_BE')->isoFormat('D MMM YYYY') }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Kaart Paneel - Nu met responsive height --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative" 
                        style="height: 200px; lg:height: 100%;">
                        <div id="map" class="h-100 w-100 bg-light"></div>
                        
                        <div class="position-absolute top-0 end-0 m-2 d-lg-none" style="z-index: 1000;">
                            <span class="badge bg-white bg-opacity-75 text-dark border shadow-sm">
                                <x-heroicon-s-map-pin class="icon text-danger" style="width:12px;"/>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow-sm">
    <div class="px-5">
        <div class="container-fluid">
            <div class="col-12 col-lg-10 my-2 d-flex flex-column flex-lg-row" style="align-items: first baseline;">
                <div class="text-muted fw-bold text-uppercase mb-2 mb-lg-0 me-3" style="font-size: 0.75rem; letter-spacing: 0.5px; white-space: nowrap;">
                    Index:
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem 1rem">
                    @foreach (range('A', 'Z') as $character)
                        <a class="fw-bold text-dark" href="{{ route('region:show', ['region' => $region, 'zoekterm' => $character]) }}">
                            {{ $character }}
                        </a>
                    @endforeach                                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('content')
    <div class="py-5 px-5">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-9">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="h4 fw-bold text-dark mb-0">
                            Gekoppelde woorden <span class="badge bg-info text-info-subtle ms-2 fs-6 fw-normal">{{ $relatedArticles->total() }}</span>
                        </h2>
                    </div>

                    <div class="card border-0 shadow-sm bg-white rounded-4 mb-4  border-bottom overflow-hidden">
                        <div class="card-body p-2 p-md-3">
                            <form action="#woorden" method="GET" class="row g-2">
                                
                                {{-- Zoekveld met geïntegreerd icoon --}}
                                <div class="col-lg-7 col-md-6">
                                    <div class="input-group input-group-merge h-100">
                                        <span class="input-group-text bg-light border-0 ps-3">
                                            <x-heroicon-o-magnifying-glass class="text-muted" style="width: 20px;"/>
                                        </span>
                                        <input type="text" 
                                            name="zoekterm" 
                                            value="{{ request('zoekterm') }}" 
                                            class="form-control bg-light border-0 py-2 ps-2 shadow-none" 
                                            placeholder="Zoek in {{ $region->name }}..."
                                            autocomplete="off">
                                    </div>
                                </div>

                                {{-- Sorteer dropdown --}}
                                <div class="col-lg-3 col-md-4 col-8">
                                    <div class="input-group h-100">
                                        <span class="input-group-text bg-light border-0 d-none d-lg-flex">
                                            <x-heroicon-o-bars-arrow-down class="text-muted" style="width: 18px;"/>
                                        </span>
                                        <select name="sortering" class="form-select bg-light border-0 shadow-none py-2">
                                            <option value="" @selected(!request('sortering'))>Sorteer op...</option>
                                            <option value="alfabetisch" @selected(request('sortering') === 'alfabetisch')>A - Z</option>
                                            <option value="populariteit" @selected(request('sortering') === 'populariteit')>Meest bekeken</option>
                                            <option value="recent" @selected(request('sortering') === 'recent')>Recent toegevoegd</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Button --}}
                                <div class="col-lg-2 col-md-2 col-4">
                                    <button type="submit" class="btn btn-submit w-100 h-100 rounded-3 fw-bold transition-all hover-lift-sm">
                                        <span class="d-none d-xl-inline">Zoeken</span>
                                        <x-heroicon-s-magnifying-glass class="d-inline d-xl-none mb-1" style="width: 18px;"/>
                                    </button>
                                </div>
                            </form>

                            {{-- Active Filter Pills --}}
                            @if (request()->filled('zoekterm') || request()->filled('sortering'))
                                <div class="d-flex align-items-center flex-wrap gap-2 mt-3 pt-3 border-top">
                                    <span class="small text-muted fw-bold text-uppercase me-2" style="font-size: 0.65rem;">Actieve filters:</span>
                                    
                                    @if (request('zoekterm'))
                                        <div class="badge-filter d-flex align-items-center bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-semibold">
                                            "{{ request('zoekterm') }}"
                                            <a href="{{ request()->fullUrlWithoutQuery('zoekterm') }}" class="ms-2 text-primary opacity-50 hover-opacity-100">
                                                <x-heroicon-s-x-mark style="width: 14px;"/>
                                            </a>
                                        </div>
                                    @endif

                                    @if (request('sortering'))
                                        <div class="badge-filter d-flex align-items-center bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-1 small fw-semibold">
                                            <x-heroicon-s-arrows-up-down class="me-1" style="width: 12px;"/>
                                            {{ ucfirst(request('sortering')) }}
                                            <a href="{{ request()->fullUrlWithoutQuery('sortering') }}" class="ms-2 text-dark opacity-50 hover-opacity-100">
                                                <x-heroicon-s-x-mark style="width: 14px;"/>
                                            </a>
                                        </div>
                                    @endif

                                    <a href="{{ url()->current() }}" class="ms-auto small text-danger fw-bold text-decoration-none hover-underline">
                                        Wis alles
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <section id="woorden">
                        @if ($relatedArticles->total() > 0)
                            {{-- Woorden Lijst --}}
                            <div class="vstack gap-3 mb-4">
                                @foreach ($relatedArticles as $relatedArticle)
                                    <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden word-card transition-all">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <h3 class="h5 fw-bold mb-1 italic text-dark">{{ $relatedArticle->word }}</h3>
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <x-heroicon-s-eye class="icon text-success me-1" style="width:14px;"/>
                                                        {{ toHumanReadableNumber((int) $relatedArticle->views) }} weergaves
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-3 mb-md-0">
                                                    <p class="text-muted small mb-0 line-clamp-2">
                                                        {{ strip_tags(str($relatedArticle->description)->markdown()) }}
                                                    </p>
                                                </div>
                                                <div class="col-md-3 text-md-end">
                                                    <a href="{{ route('word-information.show', $relatedArticle) }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold">
                                                        <x-heroicon-s-chevron-double-right class="icon me-1"/> Bekijken
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex align-items-center justify-content-between mt-3 border-top pt-4">
                                <span class="small text-muted d-none d-md-block">
                                    Resultaten {{ $relatedArticles->firstItem() }} - {{ $relatedArticles->lastItem() }} van {{ $relatedArticles->total() }}
                                </span>
                                <div>
                                    {{ $relatedArticles->onEachSide(1)->appends(request()->query())->links() }}
                                </div>
                            </div>

                        @else
                            {{-- Blankstate --}}
                            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                                <div class="card-body">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                        <x-heroicon-o-magnifying-glass class="text-muted" style="width: 40px;"/>
                                    </div>
                                    <h5 class="fw-bold">Geen woorden gevonden</h5>
                                    <p class="text-muted mx-auto" style="max-width: 400px;">
                                        We konden geen woorden vinden die matchen met je zoekopdracht binnen deze regio.
                                    </p>
                                    @if (request()->filled('zoekterm'))
                                        <a href="{{ url()->current() }}" class="btn btn-primary rounded-pill px-4 mt-2">
                                            Reset Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="col-lg-3">
                    <aside class="ps-lg-5">
                        {{-- Section Divider & Title --}}
                        <div class="border-top border-dark border-3 pt-3 mb-5">
                            <h6 class="fw-black text-uppercase mb-1" style="letter-spacing: 2px; font-size: 0.8rem;">Statistieken</h6>
                            <span class="text-muted small">Regio analyse</span>
                        </div>

                        {{-- Typography-Focused Stats --}}
                        <div class="mb-5">
                            {{-- Views --}}
                            <div class="row align-items-center mb-4 g-0">
                                <div class="col-2">
                                    <x-heroicon-s-eye class="text-brand-green" style="width: 20px;"/>
                                </div>
                                <div class="col-10 border-start ps-3">
                                    <div class="fs-4 fw-bold lh-1 text-dark">{{ toHumanReadableNumber((int) $analytics['views']['statistic']) }}</div>
                                    <div class="text-muted text-uppercase fw-semibold mt-1" style="font-size: 0.6rem; ls: 1px;">Totale interactie</div>
                                </div>
                            </div>

                            {{-- Woorden --}}
                            <div class="row align-items-center mb-4 g-0">
                                <div class="col-2">
                                    <x-heroicon-s-language class="text-brand-green" style="width: 20px;"/>
                                </div>
                                <div class="col-10 border-start ps-3">
                                    <div class="fs-4 fw-bold lh-1 text-dark">{{ $analytics['word']['statistic'] }}</div>
                                    <div class="text-muted text-uppercase fw-semibold mt-1" style="font-size: 0.6rem; ls: 1px;">Geregistreerde termen</div>
                                </div>
                            </div>

                            {{-- Auteurs --}}
                            <div class="row align-items-center mb-4 g-0">
                                <div class="col-2">
                                    <x-heroicon-s-finger-print class="text-brand-green" style="width: 20px;"/>
                                </div>
                                <div class="col-10 border-start ps-3">
                                    <div class="fs-4 fw-bold lh-1 text-dark">{{ $analytics['contributor']['statistic'] }}</div>
                                    <div class="text-muted text-uppercase fw-semibold mt-1" style="font-size: 0.6rem; ls: 1px;">Unieke bijdragers</div>
                                </div>
                            </div>

                            
                        </div>

                        {{-- Textual Call-out (Geen kaart, maar focus op tekst) --}}
                        <div class="py-4 border-top border-bottom border-light mb-4">
                            <h6 class="fw-bold text-dark mb-2">Draag bij aan het {{ config('app.name', 'Laravel') }}</h6>
                            <p class="text-muted small lh-base mb-3">
                                De taal van <strong>{{ $region->name }}</strong> leeft dankzij jou. Heb je een suggestie voor een ontbrekende term?
                            </p>
                            <a href="#" class="text-brand-green fw-bold small text-decoration-none d-flex align-items-center">
                                Suggestie inzenden <x-heroicon-m-arrow-small-right class="ms-1" style="width: 16px;"/>
                            </a>
                        </div>
                    </aside>
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
        zoomControl: false,         // Removes the +/- buttons
        scrollWheelZoom: false,     // Disables zooming with the mouse wheel
        doubleClickZoom: false,     // Disables zooming by double-clicking
        touchZoom: false,           // Disables zooming by pinching on mobile
        boxZoom: false,             // Disables zooming by drawing a box (Shift + Drag)
        keyboard: false,            // Disables zooming via keyboard (+ / - keys)
        dragging: false              // Keeps dragging enabled (set to false to lock the map entirely)
    }).setView([50.8503, 4.3517], 7);

        // Add a base tile layer (e.g., OpenStreetMap)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // URL of your Laravel API endpoint
        const geoApiUrl = '/api/geo-data/{{ $region->id}}'; // Adjust if your API path is different

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
                        return '#519';
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
