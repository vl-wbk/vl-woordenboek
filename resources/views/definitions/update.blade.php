@extends ('components.definitions.word-information', ['title' => 'Woord aanpassen'])

@section ('content')
    <div class="card bg-white border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-1 color-green">{{ __('Gegevens aanpassen van het woord: :word', ['word' => $word->word])}}</h5>
            <p class="mb-0 text-muted">
                Met het onderstaande formulier kunt u de gegevens aanpassen met betrekking op het woord <strong>{{ $word->word }}</strong>.<br>
            </p>
        </div>
        <div class="card-body">
            <div class="alert alert-important border-0 shadow-sm alert-danger" role="alert">
                <h5 class="alert-heading fw-bold">
                    Opgepast!
                </h5>

                <p class="lh-sm mb-0">
                    In ruil voor het vertrouwen vragen we u om respectvol om te gaan met het formulier en de data te vervuilen met inconsistenties of beledigende taal.
                    Misbruik van het formulier en vertrouwen zal mogelijks gesanctioneerd worden door uitsluiting van je gebruikersaccount op het vlaams woordenboek.
                </p>
            </div>

            <hr>

            <form action="{{ route('article.update', $word) }}" id="updateArticleForm" method="POST">
                @csrf               {{-- Form field protection --}}
                @method('PATCH')    {{-- HTTP method spoofing --}}

                <div class="form-group">
                    <label for="woord" class="col-form-label">Woord <span class="fw-bold text-danger">*</span></label>
                    <input type="text" name="woord" id="woordHelptext" value="{{ old('woord', $word->word) }}" class="form-control @error('woord') is-invalid @enderror">

                    @if ($errors->has('woord'))
                        <x-forms.validation-error field="woord"/>
                    @else
                        <x-forms.help-text field="woordHelptext" icon="true" text="Het woord, term of de uitspraak die hier wordt gedefinieerd. Gebruik liefst geen hoofdletters."/>
                    @endif
                </div>

                <div class="form-group">
                    <label for="kenmerken" class="col-form-label">Kenmerken</label>
                    <input type="text" name="kenmerken" value=" {{ old('kenmerken', $word->characteristics) }}" id="kenmerkenHelpText" class="form-control">
                    <x-forms.help-text field="kenmerkenHelpText" icon="true" text="Je kunt hier woordkenmerken aangeven, zoals het lidwoord, geslacht en meervoud van een zelfstandig naamwoord. Bijvoorbeeld: de ~ (v.), ~sen."/>
                </div>

                <div class="form-group">
                    <label for="beschrijving" class="col-form-label">Beschrijving <span class="text-danger fw-bold">*</span></label>
                    <textarea name="beschrijving" class="form-control @error('beschrijving') is-invalid @enderror" id="beschrijvingHelpText" cols="4">{{ old('beschrijving', $word->description) }}</textarea>

                    @if ($errors->has('beschrijving'))
                        <x-forms.validation-error field="kenmerken"/>
                    @else
                        <x-forms.help-text icon="true" field="beschrijvingHelpText" text="Beschrijf de term in Algemeen Beschaafd Vlaams en beperk je tot één betekenis per beschrijving. Voeg andere betekenissen apart toe."/>
                    @endif
                </div>

                <div class="form-group">
                    <label for="regio" class="col-form-label">Regio <span class="text-danger fw-bold">*</span></label>
                    <select id="regioHelpText" class="form-control @error('regio') is-invalid @enderror" name="regio[]" multiple size="10">
                        @foreach ($regions as $region => $value)
                            <option value="{{ $region }}" {{ in_array($region, old('regio', $word->regions->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                    <textarea name="voorbeeld" id="voorbeeldHelpText" class="form-control @error('voorbeeld') is-invalid @enderror" cols="6">{{ old('voorbeeld', $word->example) }}</textarea>

                    @if ($errors->has('voorbeeld'))
                        <x-forms.validation-error field="voorbeeld"/>
                    @else
                        <x-forms.help-text icon="true" field="voorbeeldHelpText" text="Geef een voorbeeldzin in Algemeen Beschaafd Vlaams die de hierboven beschreven betekenis van het woord verduidelijkt."/>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-footer bg-black">
            <div class="flex">
                <div class="float-start">
                    <button type="submit" form="updateArticleForm" class="btn btn-sm btn-submit">
                        <x-tabler-send class="icon icon-sm me-1"/> insturen
                    </button>

                    <button type="reset" form="updateArticleForm" class="btn btn-sm btn-light">
                        <x-tabler-arrow-back-up class="icon icon-sm me-1 text-danger"/> reset
                    </button>
                </div>

                <div class="float-end">
                    <a href="{{ route('word-information.show', $word) }}" class="btn btn-light btn-sm">
                        <x-heroicon-o-chevron-double-left class="icon icon-sm me-1 text-muted"/> annuleren
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
