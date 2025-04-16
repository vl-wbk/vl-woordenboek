@extends ('layouts.application-blank', ['title' => 'Nieuwe suggestie'])

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="color-green float-start">Suggestie indienen</h3>

                <a href="{{ url()->previous() }}" class="btn btn-outline-danger shadow-sm float-end">
                    <x-heroicon-o-arrow-uturn-left class="icon me-1"/> Annuleren
                </a>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-white border-0 shadow-sm">
                    <form action="{{ route('definitions.store') }}" id="createSuggestionForm" method="POST" class="card-body">
                        @csrf {{--  Form field protection --}}

                        {{-- This hidoden form input connects the currently authenticated user (if there is a user authenticated) --}}
                        <input type="hidden" name="creator" value="{{ optional(auth()->user())->id }}">

                        <p class="card-text mb-0">
                            Elke gebruiker kan nieuwe termen en hun definities aanleveren bij het Vlaams Woordenboek. <br>
                            Met het volgende formulier kunt ge nieuwe beschrijvingen van typisch vlaamse woorden, termen en uitspraken toevoegen aan onze databank.
                        </p>

                        <hr class="my-2">

                        <div class="form-group">
                            <label for="woord" class="col-form-label">Woord <span class="fw-bold text-danger">*</span></label>
                            <input type="text" name="woord" id="woordHelptext" value="{{ old('woord') }}" class="form-control @error('woord') is-invalid @enderror">

                            @if ($errors->has('woord'))
                                <x-forms.validation-error field="woord"/>
                            @else
                                <x-forms.help-text field="woordHelptext" icon="true" text="Het woord, term of de uitspraak die hier wordt gedefinieerd. Gebruik liefst geen hoofdletters."/>
                            @endif
                        </div>

                        <div class="row">
                            <div class="form-group col-4">
                                <label for="woordsoort" class="col-form-label">Woordsoort</label>

                                <select name="woordsoort" id="woordsoort" class="form-select">
                                    <option value="">-- selecteer woordsoort --</option>

                                    @foreach ($partOfSpeeches as $partOfSpeech => $value)
                                        <option value="{{ $partOfSpeech }}" @selected(old('woordsoort') == $partOfSpeech)>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>

                                <x-forms.help-text field="kenmerkenHelpText" icon="true" text="Wat voor woord is het? Bv. zn, ww, bn."/>
                            </div>

                            <div class="form-group col-8">
                                <label for="kenmerken" class="col-form-label">Kenmerken</label>
                                <input type="text" name="kenmerken" value=" {{ old('kenmerken') }}" id="kenmerkenHelpText" class="form-control">
                                <x-forms.help-text field="kenmerkenHelpText" icon="true" text="Lidwoord, geslacht en meervoud – bv. de ~ (v.), ~sen."/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="beschrijving" class="col-form-label">Beschrijving <span class="text-danger fw-bold">*</span></label>
                            <textarea name="beschrijving" class="form-control @error('beschrijving') is-invalid @enderror" id="beschrijvingHelpText" cols="4">{{ old('beschrijving') }}</textarea>

                            @if ($errors->has('beschrijving'))
                                <x-forms.validation-error field="beschrijving"/>
                            @else
                                <x-forms.help-text icon="true" field="beschrijvingHelpText" text="Beschrijf de term in Algemeen Beschaafd Vlaams en beperk je tot één betekenis per beschrijving. Voeg andere betekenissen apart toe."/>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="regio" class="col-form-label">Regio <span class="text-danger fw-bold">*</span></label>
                            <select id="regioHelpText" class="form-control @error('regio') is-invalid @enderror" name="regio[]" multiple size="10">
                                @foreach ($regions as $region => $value)
                                    <option value="{{ $region }}" {{ in_array($region, old('regio', [])) ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('regio'))
                                <x-forms.validation-error field="regio"/>
                            @else
                                <span id="regioHelptext" class="form-text text-muted">
                                    <x-tabler-info-circle class="icon icon-sm me-1"/> Indien het woord enkel in een lokaal dialect wordt gebruikt, geef dan ook de streek aan waar deze term wordt gebruikt.
                                    - <a href="{{ route('definitions.region-info') }}" target="_blank">Meer info over de regio's.</a> <br>

                                    U kunt meerdere regio's aanklikken doormiddel van de CRTL toets in te drukken.
                                </span>
                            @endif
                        </div>

                        <hr class="mb-1">

                        <div class="form-group">
                            <label for="voorbeeld" class="col-form-label">Voorbeeld <span class="fw-bold text-danger">*</span></label>
                            <textarea name="voorbeeld" id="voorbeeldHelpText" class="form-control @error('voorbeeld') is-invalid @enderror" cols="6">{{ old('voorbeeld') }}</textarea>

                            @if ($errors->has('voorbeeld'))
                                <x-forms.validation-error field="voorbeeld"/>
                            @else
                                <x-forms.help-text icon="true" field="voorbeeldHelpText" text="Geef een voorbeeldzin in Algemeen Beschaafd Vlaams die de hierboven beschreven betekenis van het woord verduidelijkt."/>
                            @endif
                        </div>
                    </form>

                    <div class="card-footer bg-white border-top">
                        <button type="submit" form="createSuggestionForm" class="btn btn-sm btn-suggestion-submit">
                            <x-tabler-send class="icon icon-sm me-1" /> Insturen
                        </button>
                        <button type="reset" form="createSuggestionForm" class="btn btn-link btn-sm">
                            <x-tabler-arrow-back-up class="icon icon-sm me-1 text-danger"/> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
