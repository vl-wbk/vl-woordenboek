@extends('layouts.application-blank', ['title' => 'Nieuwe suggestie'])

@section('jumbotron')
    <header class="position-relative py-5 border-bottom" style="background-color: #fcfaf7;">
        <div class="container-fluid position-relative z-1">
            <div class="row justify-content-center">
                <div class="col-11 col-xl-10">

                    {{-- Status Badge & Breadcrumb --}}
                    <div class="d-flex align-items-center mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small text-uppercase fw-bold tracking-tighter" style="font-size: 0.7rem;">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">Vlaams Woordenboek</a>
                                </li>
                                <li class="breadcrumb-item active text-warning" aria-current="page">Bijdrage Inzenden</li>
                            </ol>
                        </nav>
                    </div>

                    {{-- Hoofdtitel --}}
                    <div class="mb-4">
                        <h1 class="display-4 display-md-2 fw-black mb-2 text-dark" style="letter-spacing: -1px;">
                            Het <span class="text-warning">Woord</span> als Erfgoed
                        </h1>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning me-3" style="height: 2px; width: 40px;"></div>
                            <span class="text-uppercase small fw-bold tracking-widest text-muted">Protocol voor nieuwe lemma's</span>
                        </div>
                    </div>

                    {{-- Introductie tekst --}}
                    <div class="row">
                        <div class="col-md-10 col-lg-8">
                            <p class="fs-5 text-secondary mb-5 lh-base" style="font-weight: 300;">
                                Elke bezoeker kan nieuwe suggesties met definities indienen bij het Vlaams Woordenboek.
                                Die worden beoordeeld en bewerkt door een redacteur voor ze online verschijnen.
                                Met dit formulier kun je nieuwe typisch Vlaamse woorden, termen en uitdrukkingen voorstellen.
                            </p>

                            {{-- Acties --}}
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <a href="{{ route('home') }}" class="btn btn-dark rounded-1 px-4 py-2 shadow-sm d-flex align-items-center">
                                    <x-heroicon-o-arrow-left class="icon icon-sm me-2"/>
                                    <span class="small text-uppercase fw-bold tracking-wider">Terug naar overzicht</span>
                                </a>
                                <div class="d-flex align-items-center text-muted small border-start ps-3 d-none d-sm-flex">
                                    <x-heroicon-o-shield-check class="icon icon-sm me-2 text-warning"/>
                                    <span>Redactionele controle actief</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Subtiele grid-overlay aan de onderkant --}}
        <div class="position-absolute bottom-0 start-0 w-100 shadow-sm" style="height: 4px; background-image: radial-gradient(#b08d57 1px, transparent 1px); background-size: 20px 20px; opacity: 0.2;"></div>
    </header>
@endsection

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-11 col-xl-10">
                @php
                    $limietBereikt = $resterend <= 0;
                    $bijnaLimiet = !$limietBereikt && $resterend <= 1;
                @endphp

                {{-- Status Melding --}}
                @if (flash()->message)
                    <div class="alert alert-secondary border-0 shadow-sm rounded-3 border-start border-4 mb-5 p-4 {{ (flash()->class === 'text-success') ? 'border-success' : 'border-danger' }}" role="alert">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 {{ (flash()->class === 'text-success') ? 'text-success' : 'text-danger' }}">
                                    @if(flash()->class === 'text-success')
                                        <x-heroicon-s-check class="icon me-1"/> Suggestie goed ontvangen
                                    @else
                                        <x-heroicon-s-wrench-screwdriver class="icon me-1" /> Er is iets misgelopen!
                                    @endif
                                </h6>
                                <p class="mb-0 text-secondary">{{ flash()->message }}</p>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @elseif (! $limietBereikt)
                    <div class="alert alert-info border-0 shadow-sm" role="alert">
                        <strong class="mb-3 d-block">
                            <span class="badge rounded-pill bg-info shadow-sm me-2">
                                <x-heroicon-o-megaphone class="icon me-1"/> Nieuw
                            </span> 
                        
                            limiet op het aantal suggesties
                        </strong>

                        <p class="mt-2 mb-2">
                            Het Vlaams Woordenboek wordt volledig door vrijwilligers beheerd. Om de kwaliteit van 
                            de redactie te bewaken en ook voldoende tijd vrij te maken voor het onderhouden van bestaande 
                            woorden, geldt er sinds kort een limiet op het aantal suggesties dat je kunt indienen.

                            @guest
                                Als gast kan je maximaal <strong>{{ config('flemish-dictionary.rate-limiting.suggestions.anonymous.max') }} suggesties per 24 uur</strong> indienen. Of neem contact op met ons op als je wilt vrijwilligen bij de redactie.
                            @else
                                Met je account kan je maximaal <strong>{{ config('flemish-dictionary.rate-limiting.suggestions.authenticated.max', 5) }} suggesties per week</strong> indienen.
                            @endguest
                        </p>

                        @guest
                            <p>
                                <a href="{{ route('login') }}" class="alert-link">Log in</a> 
                                of <a href="{{ route('register') }}" class="alert-link">registreer je</a> 
                                voor een hoger quotum ({{ config('flemish-dictionary.rate-limiting.suggestions.authenticated.max', 20) }} per week).
                            </p>
                        @endguest

                        <hr>

                        <p class="mb-0">
                            <span class="d-block">Bedankt alvast voor je begrip en voor je bijdrage aan het woordenboek!</span>
                        </p>
                    </div>
                @endif

                <form action="{{ route('definitions.store') }}" id="createSuggestionForm" method="POST">
                    @csrf

                    <fieldset @if ($limietBereikt) disabled @endif>
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                            <div class="row g-0">

                                {{-- Linkerkolom: De Invoer --}}
                                <div class="col-lg-7 p-4 p-md-5 border-end">
                                    @if ($limietBereikt || $bijnaLimiet || $errors->has('quotum'))
                                        <div class="alert {{ $limietBereikt || $errors->has('quotum') ? 'alert-danger' : 'alert-warning' }} d-flex align-items-start gap-2" role="alert">
                                            <span aria-hidden="true">
                                                @if ($limietBereikt || $errors->has('quotum'))
                                                    <x-heroicon-o-no-symbol class="icon"/>
                                                @else
                                                    <x-heroicon-o-exclamation-triangle class="icon"/>
                                                @endif
                                            </span>

                                            <div>
                                                @if ($errors->has('quotum'))
                                                    {{ $errors->first('quotum') }}

                                                @elseif ($limietBereikt)
                                                    Je hebt de limiet bereikt voor het indienen van suggesties. Probeer het later opnieuw

                                                    @guest
                                                        of <a href="{{ route('login') }}" class="alert-link">meld je aan</a> voor een hoger quotum.
                                                    @else
                                                        .
                                                    @endguest

                                                @else
                                                    Je hebt nog <strong>{{ $resterend }}</strong> suggestie(s) over voor deze periode.
                                                    @guest
                                                        <a href="{{ route('login') }}" class="alert-link">Meld je aan</a> voor een ruimer quotum.
                                                    @endguest
                                                @endif
                                            </div>
                                        </div>
                                        <hr class="pb-2">
                                    @endif

                                    <div class="mb-5">
                                        <h5 class="fw-bold mb-1 text-dark">
                                            <span class="me-2 text-warning">●</span> Lemma Informatie
                                        </h5>
                                        <p class="text-muted small mb-0">Definieer de basis en grammatica van het trefwoord.</p>
                                    </div>

                                    <div class="mb-5">
                                        <label for="woord" class="form-label small text-uppercase fw-bold text-muted" style="letter-spacing: 1px;">
                                            Het Trefwoord <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="woord"
                                            id="woord"
                                            placeholder="bijv. Goesting"
                                            value="{{ old('woord', request()->get('woord')) }}"
                                            class="form-control form-control-lg mb-2 shadow-none  @error('woord') is-invalid @enderror"
                                            style="font-size: 2rem; font-weight: 600;"
                                        >
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted fst-italic">Gebruik spelling volgens de algemene regels.</small>
                                            @error('woord') <span class="text-danger small fw-bold">{{ $message ?? 'Verplicht veld' }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="row g-4 mb-5">
                                        <div class="col-md-6">
                                            <label for="woordsoort" class="form-label small text-uppercase fw-bold text-muted">Woordsoort</label>
                                            <select name="woordsoort" id="woordsoort" class="form-select ">
                                                <option value="">-- selecteer --</option>
                                                @foreach ($partOfSpeeches as $partOfSpeech => $value)
                                                    <option value="{{ $partOfSpeech }}" @selected(old('woordsoort') == $partOfSpeech)>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kenmerken" class="form-label small text-uppercase fw-bold text-muted">Kenmerken</label>
                                            <input type="text" name="kenmerken" id="kenmerken" value="{{ old('kenmerken', '-') }}" class="form-control mb-1" placeholder="bijv. de ~ (v.), -s aria-describedbt="characteristicsHelpBlock">

                                            <span id="characteristicsHelpBlock" class="form-text text-muted">
                                                <x-heroicon-o-information-circle class="icon me-1"/> bijv. de ~ (v.), -s
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label for="beschrijving" class="form-label small text-uppercase fw-bold text-muted">
                                            Beschrijving <span class="text-danger">*</span>
                                        </label>
                                        <textarea
                                            name="beschrijving"
                                            id="beschrijving"
                                            rows="5"
                                            class="form-control p-3  @error('beschrijving') is-invalid @enderror"
                                            placeholder="Wat is de kern van de betekenis?"
                                        >{{ old('beschrijving') }}</textarea>
                                        @error('beschrijving') <span class="text-danger small fw-bold mt-1 d-block">{{ $message ?? 'Verplicht veld' }}</span> @enderror
                                    </div>

                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-end mb-3">
                                            <label class="form-label small text-uppercase fw-bold text-muted mb-0">
                                                Voorbeeldzinnen <span class="text-danger">*</span>
                                            </label>
                                            {{-- Toegevoegde trigger voor het reeds bestaande modal --}}
                                            <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted p-0 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#sourceInformation">
                                                <x-heroicon-o-information-circle class="icon icon-sm me-1"/> Citeerhulp
                                            </button>
                                        </div>

                                        <div id="kv-container" class="d-flex flex-column gap-3">
                                            @foreach(old('voorbeeldzin', [[]]) as $i => $pair)
                                                <div class="row g-2 align-items-start kv-row">
                                                    <div class="col-12 col-md-4">
                                                        <input type="text"
                                                            name="voorbeeldzin[{{ $i }}][bron]"
                                                            value="{{ old("voorbeeldzin.$i.bron") }}"
                                                            class="form-control  @error("voorbeeldzin.$i.bron") is-invalid @enderror"
                                                            placeholder="Bron (bijv. VRT NWS)"
                                                        />
                                                        @error("voorbeeldzin.$i.bron")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-12 col-md-7">
                                                        <textarea type="text"
                                                            name="voorbeeldzin[{{ $i }}][waarde]"
                                                            class="form-control resizable @error("voorbeeldzin.$i.waarde") is-invalid @enderror"
                                                            rows="2"
                                                            placeholder="Typ hier de voorbeeldzin...">{{ old("voorbeeldzin.$i.waarde") }}</textarea>
                                                        @error("voorbeeldzin.$i.waarde")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-12 col-md-1 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-outline-danger remove-row w-100" title="Rij verwijderen">
                                                            <x-heroicon-o-trash class="icon m-0"/>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-light border btn-sm mt-3 fw-bold text-secondary" id="add-pair">
                                            <x-heroicon-o-plus-circle class="icon me-1"/> Extra voorbeeldzin toevoegen
                                        </button>
                                    </div>
                                </div>

                                {{-- Rechterkolom: Meta & Regio --}}
                                <div class="col-lg-5 p-4 p-md-5 d-flex flex-column" style="background-color: #fcfaf7;">
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="fw-bold mb-1 text-dark">
                                                <span class="me-2 text-warning">●</span> Classificatie
                                            </h5>
                                            <a href="{{ route('definitions.region-info') }}" target="_blank" class="btn btn-sm btn-link text-decoration-none text-muted p-0 fw-bold d-flex align-items-center">
                                                <x-heroicon-o-map class="icon icon-sm me-1"/> Regio info
                                            </a>
                                        </div>
                                        <p class="text-muted small">Duid aan waar dit woord specifiek gebruikt wordt.</p>
                                    </div>

                                    <div class="mb-5 flex-grow-1">
                                        <label for="regio" class="form-label small text-uppercase fw-bold text-muted mb-2 d-block">
                                            Geografische situering <span class="text-danger">*</span>
                                        </label>

                                        <select id="regio" class="form-select w-100 shadow-sm border @error('regio') border-danger @enderror rounded-3" name="regio[]" multiple size="10">
                                            @foreach ($regions as $region => $value)
                                                <option value="{{ $region }}" {{ in_array($region, old('regio', [])) ? 'selected' : '' }} class="py-2 px-3 mb-1 rounded-2">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="mt-3 small text-muted p-3 border-start border-3 border-warning bg-white shadow-sm rounded-end">
                                            Houd <kbd class="bg-dark text-white border-0 px-2">Ctrl</kbd> of <kbd class="bg-dark text-white border-0 px-2">Cmd ⌘</kbd> ingedrukt om meerdere regio's te selecteren.
                                        </div>
                                        @error('regio') <div class="text-danger small mt-2 fw-bold">Selecteer minstens één regio.</div> @enderror
                                    </div>

                                    <div class="bg-dark text-white p-4 rounded-4 shadow mt-auto">
                                        <h6 class="fw-bold mb-2 text-warning">Klaar voor de redactie?</h6>
                                        <p class="small text-white-50 mb-4">Uw inzending wordt getoetst op lexicografische nauwkeurigheid door onze beheerders.</p>

                                        @auth
                                            <div class="form-check form-switch mb-4">
                                                <input class="form-check-input" name="notificatie" type="checkbox" id="notificatie" value="1">
                                                <label class="form-check-label small text-white-50" for="notificatie">Houd me op de hoogte via mail</label>
                                            </div>
                                        @endauth

                                        <button type="submit" class="btn btn-warning w-100 py-3 rounded-3 fw-bold text-uppercase tracking-wider shadow-sm transition-all hover-scale">
                                            Lemma Toevoegen
                                        </button>
                                        <button type="reset" class="btn btn-link btn-sm w-100 text-white-50 mt-2 text-decoration-none small">Formulier wissen</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Citeerhulp --}}
    <div class="modal fade" id="sourceInformation" tabindex="-1" aria-labelledby="sourceInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">

                <div class="modal-header bg-dark text-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning p-2 me-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                            <x-heroicon-s-book-open class="icon text-dark" style="width: 1.5rem; height: 1.5rem;"/>
                        </div>
                        <div>
                            <h1 class="modal-title fs-5 fw-bold mb-0" id="sourceInformationLabel">
                                Bronvermelding bij <span class="text-warning">voorbeeldzinnen</span>
                            </h1>
                            <small class="text-white-50 text-uppercase tracking-widest fw-bold" style="font-size: 0.65rem;">Lexicografische Standaard</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 p-md-5" style="background-color: #fcfaf7;">
                    <p class="lead fw-bold text-dark mb-3 fs-6">
                        Voorbeeldzinnen zijn essentieel om de nuance en context van een woord te illustreren.
                    </p>
                    <p class="text-secondary lh-base mb-4 small">
                        Onze voorkeur gaat uit naar citaten uit (online) bronnen, zoals blogs, kranten, tijdschriftartikels of sociale media.
                        <span class="text-dark fw-bold fst-italic">Alleen als het echt niet anders kan</span> (bijv. bij zeldzame dialecten), kun je een zelfverzonnen zin opgeven.
                    </p>

                    <div class="d-flex flex-column gap-4">
                        {{-- 01. Artikels --}}
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-dark rounded-1 me-2">01</span>
                                <h6 class="fw-bold mb-0 text-dark">Uit een artikel</h6>
                            </div>
                            <p class="small text-muted mb-2 fst-italic">Structuur: (bron: auteur – titel – bron – datum publicatie – ‘geraadpleegd op’ datum)</p>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm mb-2 rounded-end">
                                <p class="small mb-1 text-secondary fst-italic">"U voelt meteen stront aan de knikker in ‘Malditos’"</p>
                                <span class="text-dark fw-bold" style="font-size: 0.8rem;">(Bron: titel in De Morgen 5.05.2025, geraadpleegd op 14.05.2025)</span>
                            </div>
                        </div>

                        {{-- 02. Website --}}
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-dark rounded-1 me-2">02</span>
                                <h6 class="fw-bold mb-0 text-dark">Uit een website</h6>
                            </div>
                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm rounded-end">
                                <p class="small mb-1 text-secondary fst-italic">"Goesting in Antwerpen? Wij gidsen je op een plezante manier."</p>
                                <span class="text-dark fw-bold" style="font-size: 0.8rem;">(Bron: Goesting in A, geraadpleegd op 14.05.2025)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4" style="background-color: #fcfaf7;">
                    <button type="button" class="btn btn-dark px-4 py-2 fw-bold small text-uppercase tracking-widest rounded-2" data-bs-dismiss="modal">Begrepen</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('kv-container');
        const btnAdd = document.getElementById('add-pair');

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.kv-row');
            const btns = container.querySelectorAll('.remove-row');

            btns.forEach(btn => {
                if (rows.length === 1) {
                    btn.disabled = true;
                    btn.classList.add('opacity-25');
                } else {
                    btn.disabled = false;
                    btn.classList.remove('opacity-25');
                }
            });
        }

        function reindexVoorbeeldzinRows() {
            container.querySelectorAll('.kv-row').forEach((row, i) => {
                row.querySelectorAll('input, textarea').forEach(field => {
                    field.name = field.name.replace(/\[\d+\]/, `[${i}]`);
                });
            });
        }

        btnAdd.addEventListener('click', () => {
            const rows = container.querySelectorAll('.kv-row');
            const clone = rows[0].cloneNode(true);

            clone.querySelectorAll('input, textarea').forEach(field => {
                field.value = '';
                field.classList.remove('is-invalid');
            });
            clone.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            container.appendChild(clone);
            reindexVoorbeeldzinRows();
            updateRemoveButtons();
        });

        container.addEventListener('click', e => {
            const removeBtn = e.target.closest('.remove-row');
            if (removeBtn && !removeBtn.disabled) {
                const rows = container.querySelectorAll('.kv-row');
                if (rows.length > 1) {
                    removeBtn.closest('.kv-row').remove();
                    reindexVoorbeeldzinRows();
                    updateRemoveButtons();
                }
            }
        });

        // Initialize button states on load
        updateRemoveButtons();
    });
</script>
@endsection

@section('styles')
    <style>
        textarea.resizable {
            resize: vertical !important;
            min-height: 2.5rem;
        }
        /* Kleine toevoeging voor een soepele hover op de verzendknop */
        .hover-scale:hover {
            transform: translateY(-1px);
        }
    </style>
@endsection
