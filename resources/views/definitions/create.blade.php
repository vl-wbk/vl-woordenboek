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
                            Jouw <span class="text-warning">Woord</span> als bijdrage
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
                                Dien hier zelf een suggestie in voor een nieuw artikel voor het Vlaams Woordenboek. De redactie beoordeelt en bewerkt alle suggesties voor ze online verschijnen.
                                Wil je weten wat er met jouw suggestie gebeurt? Maak dan een account aan, dan kun je alles van a tot z opvolgen.  Ter info: lokale en regionale woorden worden alleen opgenomen wanneer ze ook voorkomen in tv-series,
                                literatuur of media. Toon dat aan met voorbeeldzinnen op websites van bijv. een blog, een krant zoals kw.be of een tv-zender zoals tvl.be.
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
                {{-- Status Melding --}}
                @if (flash()->message)
                    <div class="alert alert-secondary bg-white border-0 shadow-sm rounded-3 border-start border-4 mb-3 p-4 {{ (flash()->class === 'text-success') ? 'border-success' : 'border-danger' }}" role="alert">
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
                @endif

                <div class="container-fluid px-0">
                    <div class="row">
                        <form action="{{ route('definitions.store') }}" method="POST" class="col-8">
                            @csrf {{-- FORM field protection --}}

                            <fieldset @if ($resterend == 0) disabled @endif>
                                <div class="card bg-white rounded-3 shadow-sm p-4 border-0">
                                    <div class="card-body p-0">
                                        {{-- SEction 1: suggestion - base information --}}
                                        <section class="border-bottom pb-4">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="rounded-3 border border-info shadow-sm bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                    <x-heroicon-o-document-text class="icon text-info"/>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold mb-0 d-flex justify-content-between align-items-center">
                                                        <span>Wat is je suggestie?</span>

                                                        @auth
                                                            <span
                                                                class="badge @if($resterend <= 10 || $resterend == 0) badge-danger @elseif($resterend <=20) badge-warning @else badge-gray @endif"

                                                                @if ($resterend < 20)
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Volgende vrijgave: {{ $volgendeVrijgave }}"
                                                                    data-bs-placement="bottom"
                                                                @endif
                                                            >
                                                                @if ($resterend <= 10 || $resterend <= 20)
                                                                    <x-heroicon-o-shield-exclamation class="icon me-1"/>
                                                                    {{ $resterend }} resterend deze week
                                                                @elseif($resterend == 0)
                                                                    <x-heroicon-o-no-symbol class="icon me-1"/>
                                                                    {{ $resterend }} resterend deze week
                                                                @else
                                                                    {{ $resterend }} resterend deze week
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badge @if ($resterend == 0) badge-danger @else badge-gray @endif"

                                                                @if ($resterend < 5)
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-title="Volgende vrijgave: {{ $volgendeVrijgave }}"
                                                                    data-bs-placement="bottom"
                                                                @endif
                                                            >
                                                                @if ($resterend == 0)
                                                                    <x-heroicon-o-no-symbol class="icon me-1"/>
                                                                    limit bereikt voor vandaag
                                                                @else
                                                                    {{ $resterend }} resterend vandaag
                                                                @endif
                                                            </span>
                                                        @endauth
                                                    </h5>
                                                    <small class="text-muted">Definieer de basis en grammatica van de suggestie</small>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label for="woord" class="form-label">Woord of uitdrukking <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('woord') is-invalid @enderror rounded-3" id="woord" value="{{ old('woord') }}" name="woord" placeholder="Vul het woord of de uitdrukking in" autofocus>

                                                    @if ($errors->has('woord'))
                                                        <x-forms.validation-error field="woord"/>
                                                    @else
                                                        <div class="form-text text-muted" style="font-size: 0.75rem;">Het woord waar je suggestie over gaat.</div>
                                                    @endif
                                                </div>

                                                <div class="col-6">
                                                    <label for="woordsoort" class="form-label">woordsoort</label>
                                                    <select name="woordsoort" id="woordsoort" class="form-select rounded-3">
                                                        <option value="">-- woordsoort --</option>

                                                        @foreach ($partOfSpeeches as $partOfSpeech => $value)
                                                            <option value="{{ $partOfSpeech }}" @selected(old('woordsoort') == $partOfSpeech)>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-6">
                                                    <label for="kenmerken" class="form-label">Kenmerken</label>
                                                    <input type="text" name="kenmerken" class="form-control rounded-3" id="kenmerken" placeholder="bijv. de ~ (v.), -s" value="{{ old('kenmerken', '-') }}">
                                                    <div class="form-text text-muted" style="font-size: 0.75rem;">Grammaticale info, bijv. de ~ (v.), -s"</div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="beschrijving" class="form-label">Beschrijving <span class="text-danger">*</span></label>

                                                    <textarea
                                                        rows="4"
                                                        id="beschrijving"
                                                        class="form-control @error('beschrijving') is-invalid @enderror rounded-3"
                                                        name="beschrijving"
                                                        placeholder="Beschrijf je suggestie zo duidelijk mogelijk. Wat betekent het, wanneer en hoe wordt het gebruikt, ...">{{ old('beschrijving') }}</textarea>

                                                    @if ($errors->has('beschrijving'))
                                                        <x-forms.validation-error field="beschrijving"/>
                                                    @else
                                                        <div class="form-text text-muted" style="font-size: 0.75rem;">
                                                            Geef zoveel mogelijk details zodat onze redacteurs je suggestie goed kunnen beoordelen.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </section>

                                        <section class="border-bottom pb-4">
                                            <div class="d-flex align-items-center my-4">
                                                <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                    <x-heroicon-o-map class="icon text-info"/>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold mb-0">Regionale informatie</h5>
                                                    <small class="text-muted">In welke regio of regionale context hebt je je suggestie gehoord?</small>
                                                </div>
                                                <div class="ms-3 flex-shrink-0">
                                                    <a href="{{ route('definitions.region-info') }}" target="_blank" class="btn btn-sm rounded-3  shadow-sm btn-outline-primary">
                                                        <x-heroicon-o-information-circle class="icon me-1"/> Regio-informatie
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <select id="regio" class="form-select w-100 border @error('regio') border-danger @enderror rounded-3" name="regio[]" multiple size="6">
                                                        @foreach ($regions as $region => $value)
                                                            <option value="{{ $region }}" {{ in_array($region, old('regio', [])) ? 'selected' : '' }}>
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('regio'))
                                                        @error('regio')
                                                            <div class="text-danger small mt-2 fw-bold">Selecteer minstens één regio.</div>
                                                        @enderror
                                                    @else
                                                        <div class="form-text text-muted" style="font-size: 0.75rem;">
                                                            Houd <code>Ctrl</code> of <code>Cmd ⌘</code> ingedrukt om meerdere regio's te selecteren.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </section>

                                        <section class="border-bottom pb-3">
                                            <div class="d-flex align-items-center my-4">
                                                <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                    <x-heroicon-o-chat-bubble-left-right class="icon text-info"/>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="fw-bold mb-0">Voorbeeldzin(nen)</h5>
                                                    <small class="text-muted">Vermeld de bron, maar gebruik geen link. Voorbeelden: standaard.be, histories.be, tvl.be</small>
                                                </div>
                                                <div class="ms-3 flex-shrink-0">
                                                    <button type="button" class="btn btn-sm rounded-3 shadow-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sourceInformation">
                                                        <x-heroicon-o-information-circle class="icon me-1"/> Citeerhulp
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div id="kv-container" class="d-flex flex-column gap-3">
                                                    @foreach(old('voorbeeldzin', [[]]) as $i => $pair)
                                                        <div class="row g-2 align-items-start kv-row">
                                                            <div class="col-12 col-md-4">
                                                                <input type="text"
                                                                    name="voorbeeldzin[{{ $i }}][bron]"
                                                                    value="{{ old("voorbeeldzin.$i.bron") }}"
                                                                    class="form-control rounded-3  @error("voorbeeldzin.$i.bron") is-invalid @enderror"
                                                                    placeholder="Bron (bijv. VRT NWS)"
                                                                />
                                                                @error("voorbeeldzin.$i.bron")
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-12 col-md-7">
                                                                <textarea type="text"
                                                                    name="voorbeeldzin[{{ $i }}][waarde]"
                                                                    class="form-control rounded-3 resizable @error("voorbeeldzin.$i.waarde") is-invalid @enderror"
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


                                                <div>
                                                    <button type="button" class="btn btn-light border btn-sm rounded-3 mt-3 fw-bold text-secondary" id="add-pair">
                                                        <x-heroicon-o-plus-circle class="icon me-1"/> Extra voorbeeldzin toevoegen
                                                    </button>
                                                </div>
                                            </div>
                                        </section>


                                        @auth
                                            <section>
                                                <div class="d-flex align-items-center my-4">
                                                    <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                        <x-heroicon-o-bell-alert class="icon text-info"/>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">Melding bij publicatie</h5>
                                                        <small class="text-muted">Wil je een melding ontvangen wanneer je suggestie gepubliceerd wordt?</small>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <!-- Toggle Option Box -->
                                                        <div class="card bg-light-subtle border-secondary rounded-3 p-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class=" me-3">
                                                                        <x-heroicon-o-bell-alert class="icon" />
                                                                    </div>
                                                                    <div>
                                                                        {{-- <h6class="fw-boldmb-1small">Notificatie</h6> --}}
                                                                        <p class="text-muted mb-0">Gebruik de toggle om aan te geven of je een melding wilt ontvangen.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check form-switch m-0">
                                                                    <input class="form-check-input" value="1" name="notificatie" @checked(old('notificatie', '0')) type="checkbox" role="switch" id="toevoegenNaamSwitch" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        @else
                                            <section>
                                                <div class="d-flex align-items-center my-4">
                                                    <div class="rounded-3 shadow-sm border border-info bg-info bg-opacity-10 d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px;">
                                                        <x-heroicon-o-user-circle class="icon text-info"/>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">Over jou</h5>
                                                        <small class="text-muted">Je kunt deze suggestie ook anoniem indienen.</small>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <!-- Name and Email Inputs -->
                                                    <div class="col-md-12">
                                                        <label for="naam" class="form-label">Naam (of bijnaam)</label>
                                                        <input type="text" name="gebruikersnaam"value="{{ old('gebruikersnaam') }}" class="form-control rounded-3" id="naam" placeholder="Jouw naam of bijnaam">
                                                        <small class="text-muted">Laat dit veld leeg als je een anonieme suggestie wilt uitvoeren</small>
                                                    </div>
                                                </div>
                                            </section>
                                        @endif
                                    </div>
                                </div>

                                <hr>

                                <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                                    <div class="card-body p-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                                        <!-- Left Side: Icon, Title, and Description -->
                                        <div class="d-flex align-items-start">
                                            <div class="bg-danger bg-opacity-25 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                                <x-tabler-heart-handshake class="text-danger icon" />
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Dankjewel!</h6>
                                                <p class="text-muted small mb-0">Je suggestie zal met de nodige zorg worden nagekeken.</p>
                                            </div>
                                        </div>

                                        <!-- Right Side: Action Buttons -->
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <button type="reset" class="btn btn-outline-danger px-4 py-2 rounded-3">
                                                Annuleren
                                            </button>
                                            <button type="submit" class="btn btn-success bg-opacity-20 px-4 py-2 rounded-3 d-flex align-items-center gap-2">
                                                <span>Suggestie indienen</span>
                                                <x-heroicon-o-paper-airplane class="icon" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                        {{-- Sidenav --}}
                        <div class="col-4">
                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4 mb-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-4">Hoe werkt het?</h5>

                                    {{-- Step 1 --}}
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-light-bulb class="text-info icon" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">1. Indienen</h6>
                                            <p class="small mb-0">Vul het formulier in met jouw suggestie.</p>
                                        </div>
                                    </div>

                                    {{-- Step 2 --}}
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-users class="icon text-info" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">2. Beoordeling</h6>
                                            <p class="small mb-0">Onze redacteurs bekijken je suggestie zorgvuldig.</p>
                                        </div>
                                    </div>

                                    {{-- Step 3 --}}
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info bg-opacity-10 shadow-sm p-3 rounded-3 me-3 text-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <x-heroicon-o-check class="icon text-info" />
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">3. Verwerking</h6>
                                            <p class="smalll mb-0">Bij goedkeuring wordt je suggestie toegevoegd aan het woordenboek.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-white border-0 shadow-sm rounded-3 p-4 mb-4">
                                <div class="card-body p-0">
                                    <h5 class="fw-bold color-green mb-4">Tips voor een goede suggestie</h5>

                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center mb-2 text-muted">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Wees zo specifiek mogelijk
                                        </li>
                                        <li class="d-flex align-items-center mb-2 text-muted">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Geef duidelijke voorbeelden.
                                        </li>
                                        <li class="d-flex align-items-center mb-2 text-muted">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Vermeld indien mogelijk de herkomst of regio.
                                        </li>
                                        <li class="d-flex align-items-center mb-2 text-muted">
                                            <x-heroicon-o-check-circle class="text-success me-2 icon flex-shrink-0" />
                                            Controleer of het woord nog niet bestaat in ons woordenboek.
                                        </li>
                                        <li class="d-flex align-items-center text-muted">
                                            <x-heroicon-o-information-circle class="text-info me-2 icon flex-shrink-0" />
                                            Suggesties worden publiek zichtbaar na beoordeling.
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @auth
                                <div class="card bg-white border-0 shadow-sm rounded-3 p-4">
                                    <div class="card-body p-0">
                                        <h5 class="fw-bold color-green mb-2">Eerder ingediende suggesties</h5>
                                        <p class="small mb-4">Bekijk de status van je eerder ingediende suggesties.</p>

                                        <a href="{{ route('suggestions:index') }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-between rounded-3 border-secondary">
                                            <span class="fw-medium">
                                                <x-heroicon-o-queue-list class="icon me-1"/>
                                                Mijn bijdragen bekijken
                                            </span>

                                            <x-heroicon-o-arrow-right class="icon" />
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Citeerhulp --}}
    <div class="modal fade" id="sourceInformation" tabindex="-1" aria-labelledby="sourceInformationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-3 border-0 shadow-lg">

            <div class="modal-header bg-dark text-white border-0 p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning p-2 me-3 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                        <x-heroicon-s-book-open class="icon text-dark" style="width: 1.5rem; height: 1.5rem;"/>
                    </div>
                    <div>
                        <h1 class="modal-title fs-5 fw-bold mb-0" id="sourceInformationLabel">
                            {{ __('modals.source_information.title') }} <span class="text-warning">{{ __('modals.source_information.highlight_title') }}</span>
                        </h1>
                        <small class="text-white-50 text-uppercase tracking-widest fw-bold" style="font-size: 0.65rem;">{{ __('modals.source_information.subtitle') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('modals.source_information.buttons.close') }}"></button>
            </div>

            <div class="modal-body p-4 p-md-5" style="background-color: #fcfaf7;">
                <p class="lead fw-bold text-dark mb-3 fs-6">
                    {{ __('modals.source_information.lead') }}
                </p>
                <p class="text-secondary lh-base mb-4 small">
                    {{ __('modals.source_information.description') }}
                    <span class="text-dark fw-bold fst-italic">{{ __('modals.source_information.fallback_warning') }}</span>
                    {{ __('modals.source_information.fallback_description') }}
                </p>

                <div class="d-flex flex-column gap-4">
                    {{-- 01. Artikels --}}
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-dark rounded-1 me-2">01</span>
                            <h6 class="fw-bold mb-0 text-dark">{{ __('modals.source_information.articles.title') }}</h6>
                        </div>
                        <p class="small text-muted mb-2 fst-italic">{{ __('modals.source_information.articles.structure') }}</p>

                        <div class="bg-white p-3 border-start border-3 border-warning shadow-sm mb-2 rounded-end">
                            <p class="small mb-1 text-secondary fst-italic">{{ __('modals.source_information.articles.quote') }}</p>
                            <span class="text-dark fw-bold" style="font-size: 0.8rem;">{{ __('modals.source_information.articles.citation') }}</span>
                        </div>
                    </div>

                    {{-- 02. Website --}}
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-dark rounded-1 me-2">02</span>
                            <h6 class="fw-bold mb-0 text-dark">{{ __('modals.source_information.website.title') }}</h6>
                        </div>
                        <div class="bg-white p-3 border-start border-3 border-warning shadow-sm rounded-end">
                            <p class="small mb-1 text-secondary fst-italic">{{ __('modals.source_information.website.quote') }}</p>
                            <span class="text-dark fw-bold" style="font-size: 0.8rem;">{{ __('modals.source_information.website.citation') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4" style="background-color: #fcfaf7;">
                <button type="button" class="btn btn-dark px-4 py-2 fw-bold small text-uppercase tracking-widest rounded-2" data-bs-dismiss="modal">{{ __('modals.source_information.buttons.understood') }}</button>
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
