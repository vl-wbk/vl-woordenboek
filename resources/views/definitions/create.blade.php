@extends('layouts.application-blank', ['title' => 'Nieuwe suggestie'])

@section('jumbotron')
    <div class="bg-light-subtle bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="row">
                <div class="py-4 py-md-5">
                    <h1 class="display-6 fw-bold">Suggestie in het <span class="text-warning">Vlaams Woordenboek</span></h1>

                    <p class="col-12 col-lg-10 fs-5">
                        Elke bezoeker kan nieuwe suggesties met definities indienen bij het Vlaams Woordenboek. Die worden beoordeeld en bewerkt door een redacteur voor ze online verschijnen.<br>
                        Met dit formulier kun je nieuwe typisch Vlaamse woorden, termen en uitdrukkingen voorstellen voor het woordenboek.
                    </p>

                    <a href="{{ route('home') }}" class="btn mt-3 mt-md-4 btn-outline-danger shadow-sm">
                        <x-heroicon-o-arrow-uturn-left class="icon me-1"/> Annuleren
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div>
        <div class="container-fluid">
            <div class="row my-4 pt-3 pt-md-4 mb-2">
                <div class="col-12">
                    @if (flash()->message)
                        <div class="alert {{ flash()->class }} alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <h6 class="alert-heading fw-bold"><x-heroicon-o-bell-alert class="icon icon-lg me-1"/> Gelukt!</h6>
                            {{ flash()->message }}
                        </div>
                    @endif

                    <div class="card bg-white border-0 shadow-sm">
                        <form action="{{ route('definitions.store') }}" id="createSuggestionForm" method="POST" class="card-body">
                            @csrf {{--  Form field protection --}}

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="woord" class="form-label">Jouw suggestie <span class="fw-bold text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="woord"
                                        id="woord"
                                        value="{{ old('woord') }}"
                                        class="form-control @error('woord') is-invalid @enderror"
                                        aria-describedby="woordHelptext"
                                        autocomplete="off"
                                    >
                                    @if ($errors->has('woord'))
                                        <x-forms.validation-error field="woord"/>
                                    @else
                                        <x-forms.help-text field="woordHelptext" icon="true" text="Het woord, de term of de uitdrukking die je voorstelt. Gebruik alleen hoofdletters als het echt nodig is (bijv. bij namen)"/>
                                    @endif
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="woordsoort" class="form-label">Woordsoort</label>
                                    <select name="woordsoort" id="woordsoort" class="form-select" aria-describedby="kenmerkenHelpText">
                                        <option value="">-- selecteer woordsoort --</option>
                                        @foreach ($partOfSpeeches as $partOfSpeech => $value)
                                            <option value="{{ $partOfSpeech }}" @selected(old('woordsoort') == $partOfSpeech)>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-forms.help-text field="kenmerkenHelpText" icon="true" text="Selecteer de woordsoort uit de keuzelijst"/>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label for="kenmerken" class="form-label">Kenmerken</label>
                                    <input
                                        type="text"
                                        name="kenmerken"
                                        id="kenmerken"
                                        value="{{ old('kenmerken') }}"
                                        class="form-control"
                                        aria-describedby="kenmerkenHelpText"
                                    >
                                    <x-forms.help-text field="kenmerkenHelpText" icon="true" text="bij zelfstandige naamwoorden: lidwoord, geslacht en meervoud, bijv. de ~ (v.), ~sen; bij werkwoorden: de stamtijden, bijv. neutte, geneut; bij bijvoeglijke naamwoorden: de trappen van vergelijking, bijv. ~er, ~st"/>
                                </div>

                                <div class="col-12">
                                    <label for="beschrijving" class="form-label">Beschrijving <span class="text-danger fw-bold">*</span></label>
                                    <textarea
                                        name="beschrijving"
                                        id="beschrijving"
                                        rows="4"
                                        class="form-control @error('beschrijving') is-invalid @enderror"
                                        aria-describedby="beschrijvingHelpText"
                                    >{{ old('beschrijving') }}</textarea>

                                    @if ($errors->has('beschrijving'))
                                        <x-forms.validation-error field="beschrijving"/>
                                    @else
                                        <x-forms.help-text icon="true" field="beschrijvingHelpText" text="Beschrijf de gesuggereerde toevoeging in Algemeen (Belgisch-)Nederlands. Beperk je tot één betekenis per suggestie. Meerdere betekenissen? Dien dan extra suggesties in."/>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="regio" class="form-label">Regio <span class="text-danger fw-bold">*</span></label>
                                    <select
                                        id="regio"
                                        class="form-select @error('regio') is-invalid @enderror"
                                        name="regio[]"
                                        multiple
                                        size="6"
                                        aria-describedby="regioHelpText"
                                    >
                                        @foreach ($regions as $region => $value)
                                            <option value="{{ $region }}" {{ in_array($region, old('regio', [])) ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('regio'))
                                        <x-forms.validation-error field="regio"/>
                                    @else
                                        <span id="regioHelpText" class="form-text text-muted">
                                            <x-tabler-info-circle class="icon icon-sm me-1"/> Als dit woord of deze uitdrukking alleen in een bepaalde regio of een lokaal dialect wordt gebruikt, geef dan ook de juiste regio(‘s) aan.
                                            - <a href="{{ route('definitions.region-info') }}" target="_blank" rel="noopener">Meer info over de regio's.</a> <br>
                                            Wil je meer dan één regio aanduiden? Hou de CTRL-toets ingedrukt terwijl je een voor een op de regio’s klikt.
                                        </span>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <label for="voorbeeld" class="form-label">Voorbeelden <span class="fw-bold text-danger">*</span></label>
                                    <textarea
                                        name="voorbeeld"
                                        id="voorbeeld"
                                        class="form-control @error('voorbeeld') is-invalid @enderror"
                                        rows="6"
                                        aria-describedby="voorbeeldHelpText"
                                    >{{ old('voorbeeld') }}</textarea>

                                    @if ($errors->has('voorbeeld'))
                                        <x-forms.validation-error field="voorbeeld"/>
                                    @else
                                        <small id="voorbeeldHelpText" class="form-text text-muted d-block">
                                            <x-tabler-info-circle class="icon icon-sm me-1"/>  Geef een voorbeeldzin in het Algemeen (Belgisch–)Nederlands waaruit de hierboven beschreven betekenis duidelijk wordt. Voeg zeker een bronvermelding toe.
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#sourceInformation">meer info</a>
                                        </small>
                                    @endif
                                </div>

                                @auth
                                    <div class="col-12">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" name="notificatie" type="checkbox" id="notificatie" value="1" @checked(old('notificatie') == 1)>
                                            <label class="form-check-label" for="notificatie">
                                                Ik wens een mail notificatie te ontvangen wanneer mijn suggestie word gepubliceerd.
                                            </label>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </form>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                                <button type="submit" form="createSuggestionForm" class="btn btn-suggestion-submit w-100 w-sm-auto">
                                    <x-tabler-send class="icon icon-sm me-1" /> Insturen
                                </button>
                                <button type="reset" form="createSuggestionForm" class="btn btn-link text-danger w-100 w-sm-auto">
                                    <x-tabler-arrow-back-up class="icon icon-sm me-1"/> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sourceInformation" tabindex="-1" aria-labelledby="sourceInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header bg-sidenav color-green border-0">
                    <h1 class="modal-title fs-5" id="sourceInformationLabel"><x-heroicon-s-book-open class="icon me-2"/>Bronvermelding bij voorbeeldzinnen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2">Voorbeeldzinnen zijn belangrijk om de context waarin een woord of uitdrukking wordt gebruikt te illustreren. Daarom moet je bij elke suggestie minstens één voorbeeldzin geven.</p>

                    <p class="mb-2">
                        Onze voorkeur gaat uit naar citaten uit (online) bronnen, zoals blogs, kranten- en tijdschriftartikels, andere webpagina’s, eventueel sociale media.
                        Alleen als het ècht niet anders kan, bijvoorbeeld bij dialectwoorden of uitgesproken spreektalig taalgebruik, kun je een zelfverzonnen voorbeeldzin geven.
                        Hou er rekening mee dat dat soort zinnen minder betrouwbaar overkomen bij de lezer.
                    </p>

                    <p class="mb-2 pb-2 border-bottom">Als je een citaat gebruikt, vermeld je uiteraard ook de bron. Dat doe je als volgt.</p>

                    <h5 class="color-green fw-bold">Uit een artikel:</h5>

                    <p class="mb-3 fst-italic text-muted">(bron: (auteur) – titel – bron – datum publicatie – ‘geraadpleegd op’ datum raadpleging)</p>

                    <h6 class="fw-bold"><x-heroicon-o-information-circle class="icon"/> Voorbeeld (bij ‘stront aan de knikker’):</h6>

                    <p class="mb-3">
                        U voelt meteen stront aan de knikker in ‘Malditos’ (Bron: titel in De Morgen 5.05.2025, geraadpleegd op 14.05.2025)
                    </p>

                    <h6 class="fw-bold"><x-heroicon-o-information-circle class="icon"/> Voorbeeld (bij ‘Brailleliga’):</h6>

                    <p class="mb-2 pb-2 border-bottom">
                        De Brailleliga moet niet betalen om documenten in blindenschrift te versturen. (Bron: Brailleliga vreest voor kosteloze verzendingen. De Standaard 08.02.2006, geraadpleegd op 14.05.2025)
                    </p>

                    <h5 class="color-green fw-bold">Uit een website:</h5>
                    <p class="mb-3 fst-italic text-muted">(Bron: Naam website, ‘geraadpleegd op’ datum raadpleging, link)</p>

                    <h6 class="fw-bold"><x-heroicon-o-information-circle class="icon"/> Voorbeeld (bij ‘goesting’):</h6>
                    <p>Goesting in Antwerpen? Wij gidsen je op een plezante manier. (Bron: Goesting in A, geraadpleegd op 14.05.2025)</p>

                    <h5 class="color-green fw-bold">Uit een papieren bron:</h5>
                    <p class="mb-3 fst-italic text-muted">(Bron: titel van boek, andere bron zoals folder, brochure; publicatiedatum of -jaar)</p>

                    <h6 class="fw-bold"><x-heroicon-o-information-circle class="icon"/> Voorbeeld: (bij ‘hesp’):</h6>
                    <p>Hesp en kaas in promotie! (Bron: folder van Aldi. 10.01.2010)</p>
                </div>
            </div>
        </div>
    </div>
@endsection
