@extends('layouts.application-blank', ['title' => 'Nieuwe suggestie'])

@section('jumbotron')
    <header class="position-relative py-5 border-bottom" style="background-color: #fcfaf7;">
        <div class="container-fluid position-relative">
            <div class="row justify-content-center">
                <div class="col-10">

                    {{-- Status Badge & Breadcrumb --}}
                    <div class="d-flex align-items-center mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 small text-uppercase fw-bold tracking-tighter" style="font-size: 0.7rem;">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Vlaams Woordenboek</a></li>
                                <li class="breadcrumb-item active text-warning" aria-current="page">Bijdrage Inzenden</li>
                            </ol>
                        </nav>
                    </div>

                    {{-- Hoofdtitel --}}
                    <div class="mb-4">
                        <h1 class="display-2 fw-black mb-2 text-dark" style="letter-spacing: -1px;">
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
                                Elke bezoeker kan nieuwe suggesties met definities indienen bij het Vlaams Woordenboek. Die worden beoordeeld en bewerkt door een redacteur voor ze online verschijnen.
                                Met dit formulier kun je nieuwe typisch Vlaamse woorden, termen en uitdrukkingen voorstellen voor het woordenboek.
                            </p>

                            {{-- Acties --}}
                            <div class="d-flex flex-wrap align-items-center gap-4">
                                <a href="{{ route('home') }}" class="btn btn-dark rounded-0 px-4 py-2 shadow-sm d-flex align-items-center">
                                    <x-heroicon-o-arrow-left class="icon icon-sm me-2"/>
                                    <span class="small text-uppercase fw-bold tracking-wider">Terug naar overzicht</span>
                                </a>
                                <div class="d-flex align-items-center text-muted small border-start ps-4 d-none d-sm-flex">
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
            <div class="col-xl-10">

                {{-- Status Melding --}}
                @if (flash()->message)
                    <div class="alert {{ flash()->class }} border-0 shadow-sm rounded-0 border-start border-success border-4 mb-5 p-4 bg-success-200" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                @if(flash()->class === 'alert-success')
                                    <x-heroicon-s-check-circle class="icon icon-lg text-success" />
                                @else
                                    <x-heroicon-s-information-circle class="icon icon-lg text-primary" />
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="color: #1a2a3a;">Systeemmelding</h6>
                                <p class="mb-0 text-muted">{{ flash()->message }}</p>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <form action="{{ route('definitions.store') }}" id="createSuggestionForm" method="POST">
                    @csrf

                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background-color: #fff;">
                        <div class="row g-0">

                            {{-- Linkerkolom: De Invoer --}}
                            <div class="col-lg-7 p-4 p-md-5 border-end" style="background-color: #ffffff;">
                                <div class="mb-5">
                                    <h5 class="fw-bold mb-1" style="color: #1a2a3a;">
                                        <span class="me-2 text-warning">●</span> Lemma Informatie
                                    </h5>
                                    <p class="text-muted small">Definieer de basis en grammatica van het trefwoord.</p>
                                </div>

                                <div class="mb-5">
                                    <label for="woord" class="form-label small text-uppercase fw-black text-muted" style="letter-spacing: 1px;">Het Trefwoord <span class="text-danger fw-bold">*</span></label>
                                    <input
                                        type="text"
                                        name="woord"
                                        id="woord"
                                        placeholder="bijv. Goesting"
                                        value="{{ old('woord', request()->get('woord')) }}"
                                        class="form-control form-control-lg mb-2 @error('woord') is-invalid @enderror"
                                        style=" font-size: 2.2rem;"
                                    >
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted italic">Gebruik spelling volgens de algemene regels.</small>
                                        @error('woord') <span class="text-danger small fw-bold">Verplicht veld</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="woordsoort" class="form-label small text-uppercase fw-black text-muted">Woordsoort</label>
                                        <select name="woordsoort" id="woordsoort" class="form-select shadow-sm-inset">
                                            <option value="">-- woordsoort --</option>
                                            @foreach ($partOfSpeeches as $partOfSpeech => $value)
                                                <option value="{{ $partOfSpeech }}" @selected(old('woordsoort') == $partOfSpeech)>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kenmerken" class="form-label small text-uppercase fw-black text-muted">Kenmerken</label>
                                        <input type="text" name="kenmerken" id="kenmerken" value="{{ old('kenmerken') }}" class="form-control shadow-sm-inset" placeholder="de ~ (v.), -s">
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label for="beschrijving" class="form-label small text-uppercase fw-black text-muted">Beschrijving(en) <span class="text-danger fw-bold">*</span></label>
                                    <textarea name="beschrijving" id="beschrijving" rows="4" class="form-control @error('beschrijving') is-invalid @enderror p-3 @error('beschrijving') is-invalid @enderror" placeholder="Wat is de kern van de betekenis?">{{ old('beschrijving') }}</textarea>
                                    @error('woord') <span class="text-danger small fw-bold">Verplicht veld</span> @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="voorbeeld" class="form-label small text-uppercase fw-black text-muted">Voorbeeld(en) <span class="text-danger fw-bold">*</span></label>
                                    <textarea name="voorbeeld" id="voorbeeld" rows="5" class="form-control @error('voorbeeld') is-invalid @enderror p-3 @error('voorbeeld') is-invalid @enderror" placeholder="Citeer een zin waarin het woord tot leven komt...">{{ old('voorbeeld') }}</textarea>
                                    @error('woord') <span class="text-danger small fw-bold">Verplicht veld</span> @enderror

                                    <div class="mt-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-warning text-dark border-warning rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#sourceInformation">
                                            <x-heroicon-o-document-magnifying-glass class="icon icon-sm me-1"/> Citeerhulp
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Rechterkolom: Meta & Regio --}}
                            <div class="col-lg-5 p-4 p-md-5" style="background-color: #fdfcfb;">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-1" style="color: #1a2a3a;">
                                            <span class="me-2 text-warning">●</span> Classificatie
                                        </h5>
                                        <a href="{{ route('definitions.region-info') }}" target="_blank" class="btn btn-sm btn-link text-decoration-none text-muted p-0 fw-bold">
                                            <x-heroicon-o-map class="icon icon-sm me-1"/> Regio info
                                        </a>
                                    </div>
                                    <p class="text-muted small">Duid aan waar dit woord specifiek gebruikt wordt.</p>
                                </div>

                                <div class="mb-5">
                                    <label for="regio" class="form-label small text-uppercase fw-black text-muted mb-3 d-block">Geografische situering <span class="text-danger fw-bold">*</span></label>
                                    <div class="p-3 bg-white shadow-sm border @error('regio') border-danger @enderror rounded-3">
                                        <select id="regio" class="form-select border-0 outline-none" name="regio[]" multiple size="8" style="min-height: 250px; font-size: 0.9rem;">
                                            @foreach ($regions as $region => $value)
                                                <option value="{{ $region }}" {{ in_array($region, old('regio', [])) ? 'selected' : '' }} class="py-1 px-2 mb-1 rounded">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mt-3 small text-muted p-2 border-start border-3 border-warning bg-warning-subtle">
                                        Houd <kbd class="bg-dark text-white border-0">Ctrl</kbd> of <kbd class="bg-dark text-white border-0">Cmd</kbd> ingedrukt voor multi-selectie.
                                    </div>
                                    @error('regio') <div class="text-danger small mt-2 fw-bold">Selecteer minstens één regio.</div> @enderror
                                </div>

                                <div class="bg-dark text-white p-4 rounded-4 shadow-lg mt-auto">
                                    <h6 class="fw-bold mb-3 text-warning">Klaar voor de redactie?</h6>
                                    <p class="small opacity-75 mb-4">Uw inzending wordt getoetst op lexicografische nauwkeurigheid door onze beheerders.</p>

                                    @auth
                                        <div class="form-check form-switch mb-4">
                                            <input class="form-check-input" name="notificatie" type="checkbox" id="notificatie" value="1">
                                            <label class="form-check-label small" for="notificatie">Houd me op de hoogte via mail</label>
                                        </div>
                                    @endauth

                                    <button type="submit" class="btn btn-warning w-100 py-3 rounded-3 fw-black text-uppercase tracking-wider shadow">
                                        Lemma Toevoegen
                                    </button>
                                    <button type="reset" class="btn btn-link btn-sm w-100 text-white-50 mt-3 text-decoration-none small">Invoer wissen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Citeerhulp --}}
    {{-- Modal: Citeerhulp (Source Information) --}}
    <div class="modal fade" id="sourceInformation" tabindex="-1" aria-labelledby="sourceInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content rounded-4 border-0 shadow-lg">

                {{-- Header in de nieuwe donkere stijl --}}
                <div class="modal-header bg-dark text-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning p-2 me-3 rounded-1 shadow-sm">
                            <x-heroicon-s-book-open class="icon text-dark" style="width: 1.5rem; height: 1.5rem;"/>
                        </div>
                        <div>
                            <h1 class="modal-title fs-4 fw-black mb-0" id="sourceInformationLabel">
                                Bronvermelding bij <span class="text-warning">voorbeeldzinnen</span>
                            </h1>
                            <small class="text-white-50 text-uppercase tracking-widest fw-bold" style="font-size: 0.65rem;">Lexicografische Standaard</small>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-4" style="background-color: #fcfaf7;">

                    {{-- Introductie --}}
                    <div class="row mb-2">
                        <div class="col-lg-10">
                            <p class="lead fw-bold text-dark mb-3">
                                Voorbeeldzinnen zijn essentieel om de nuance en context van een woord te illustreren.
                            </p>
                            <p class="text-secondary lh-base">
                                Onze voorkeur gaat uit naar citaten uit (online) bronnen, zoals blogs, kranten, tijdschriftartikels of sociale media.
                                <span class="text-dark fw-bold italic">Alleen als het echt niet anders kan</span> (bijv. bij zeldzame dialecten), kun je een zelfverzonnen zin opgeven.
                                Houd er rekening mee dat citaten uit bestaande bronnen de betrouwbaarheid van het woordenboek versterken.
                            </p>
                        </div>
                    </div>

                    <hr class="mb-4" style="border-color: #e2d9c8;">

                    {{-- Secties met voorbeelden --}}
                    <div class="row g-5">

                        {{-- 01. Artikels --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-dark rounded-0 me-2">01</span>
                                <h5 class="fw-bold mb-0 text-dark">Uit een artikel</h5>
                            </div>
                            <p class="small text-muted mb-3 italic">Structuur: (bron: auteur – titel – bron – datum publicatie – ‘geraadpleegd op’ datum)</p>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm mb-3">
                                <h6 class="small fw-black text-uppercase tracking-wider mb-2">Voorbeeld ‘stront aan de knikker’:</h6>
                                <p class="small mb-0 text-secondary italic">"U voelt meteen stront aan de knikker in ‘Malditos’" <span class="text-dark fw-bold">(Bron: titel in De Morgen 5.05.2025, geraadpleegd op 14.05.2025)</span></p>
                            </div>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm">
                                <h6 class="small fw-black text-uppercase tracking-wider mb-2">Voorbeeld ‘Brailleliga’:</h6>
                                <p class="small mb-0 text-secondary italic">"De Brailleliga moet niet betalen om documenten in blindenschrift te versturen." <span class="text-dark fw-bold">(Bron: Brailleliga vreest voor kosteloze verzendingen. De Standaard 08.02.2006, geraadpleegd op 14.05.2025)</span></p>
                            </div>
                        </div>

                        {{-- 02. Website --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-dark rounded-0 me-2">02</span>
                                <h5 class="fw-bold mb-0 text-dark">Uit een website</h5>
                            </div>
                            <p class="small text-muted mb-3 italic">Structuur: (Bron: Naam website, ‘geraadpleegd op’ datum, link)</p>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm">
                                <h6 class="small fw-black text-uppercase tracking-wider mb-2">Voorbeeld ‘goesting’:</h6>
                                <p class="small mb-0 text-secondary italic">"Goesting in Antwerpen? Wij gidsen je op een plezante manier." <span class="text-dark fw-bold">(Bron: Goesting in A, geraadpleegd op 14.05.2025)</span></p>
                            </div>
                        </div>

                        {{-- 03. Papieren bron --}}
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-dark rounded-0 me-2">03</span>
                                <h5 class="fw-bold mb-0 text-dark">Papieren bron</h5>
                            </div>
                            <p class="small text-muted mb-3 italic">Structuur: (Bron: titel boek/folder/brochure; datum/jaar)</p>

                            <div class="bg-white p-3 border-start border-3 border-warning shadow-sm">
                                <h6 class="small fw-black text-uppercase tracking-wider mb-2">Voorbeeld ‘hesp’:</h6>
                                <p class="small mb-0 text-secondary italic">"Hesp en kaas in promotie!" <span class="text-dark fw-bold">(Bron: folder van Aldi. 10.01.2010)</span></p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 p-4" style="background-color: #fcfaf7;">
                    <button type="button" class="btn btn-outline-dark px-4 py-2 fw-bold small text-uppercase tracking-widest" data-bs-dismiss="modal">Begrepen</button>
                </div>
            </div>
        </div>
    </div>
@endsection
