@extends ('layouts.application-blank', ['title' => 'Etymology'])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="px-5 py-5">
                <div class="row">
                    <h1 class="h3 fw-bold">
                        <a href="{{ url()->previous() }}" class="text-muted text-decoration-none">
                            <x-heroicon-o-arrow-uturn-left class="icon icon-back-to-results"/>
                        </a>

                        <span class="text-muted">/</span>
                        Etymologie gegevens toevoegen voor het woord: <span class="color-green color-green fw-bold">{{ $article->word }}</span>
                    </h1>

                    <p class="col-12 text-muted pb-3 border-bottom">
                        Elke bezoeker van het Vlaams Woordenboek kan Etymologische gegevens indienen. Die worden beoordeeld door een redacteur voor ze online verschijnen.
                        Met het onderstaande formulier kun je de gegevens versturen naar de redactie zodat zij ermee aan de slag kunnen.
                    </p>

                    <ul class="list-inline col-12 pt-3 mb-0">
                        <li class="list-inline-item"><span class="color-green">ID:</span> <span class="fw-bold">#ARTIKEL-{{ $article->id }}</span></li>
                        <li class="list-inline-item text-muted">|</li>
                        <li class="list-inline-item"><span class="color-green">Aangemaakt op:</span> <span class="fw-bold">{{ $article->created_at->format('d/m/Y') }}</span></li>
                        <li class="list-inline-item text-muted">|</li>
                        <li class="list-inline-item"><span class="color-green">Laatste wijziging:</span> <span class="fw-bold">{{ $article->updated_at->diffForHumans() }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="my-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-white border-0 shadow-sm">
                        <form method="POST" id="createSuggestion" action="{{ route('etymology:store', $article) }}" class="card-body">
                            @csrf {{-- Form field protection --}}

                            @if (flash()->message)
                                <div class="alert {{ flash()->class }} alert-dismissible fade show border-0 shadow-sm">
                                    <h6 class="alert-heading fw-bold"><x-heroicon-o-bell-alert class="icon icon-lg me-1"/> Gelukt!</h6>
                                    {{ flash()->message }}
                                </div>
                            @endif

                            <div class="row mb-2">
                                <div class="form-group col-12 mb-2">
                                    <label for="etymology" class="pt-0 col-form-label">Etymologie<span class="fw-bold text-danger">*</span></label>
                                    <textarea
                                        class="form-control @error('etymologie') is-invalid @enderror"
                                        id="etymology"
                                        name="etymologie"
                                        placeholder="Bijv. ontleend aan het Oudfranse 'gost', smaak (12de eeuw), gevormd met het achtervoegsel -ing. 'Gost' komt op zijn beurt uit het Latijn 'gustus', smaak. Oorsponkelijk 'goest(e)'."
                                        rows="4"
                                    >{{ old('etymologie') }}</textarea>

                                    <x-forms.validation-error field="etymologie"/>
                                </div>

                                <div class="form-group col-8 mb-2">
                                    <label for="origin" class="col-form-label">Ontleend uit (taal + oorspr. vorm + betekenis)</label>
                                    <input id="origin" type="text" class="form-control" name="oorsprong" value="{{ old('oorspong') }}" placeholder="Bijv. Latijn 'gustus', smaak">
                                </div>

                                <div class="form-group col-4 mb-2">
                                    <label for="originPeriod" class="col-form-label">Periode</span></label>
                                    <input id="originPeriod" type="text" class="form-control" name="oorspong_periode" value="{{ old('oorspong_periode') }}">
                                </div>

                                <div class="form-group col-8 mb-2">
                                    <label for="furtherDevelopments" class="col-form-label">Verdere ontwikkelingen (talen + vorm + betekenis)</label>
                                    <input type="text" class="form-control" name="verdere_ontwikkeling" value="{{ old('verdere_ontwikkeling') }}" id="furtherDevelopments" placeholder="Bijv. Oudfrans 'gost'; Middelfrans 'goust', smaak">
                                </div>

                                <div class="form-group col-4 mb-2">
                                    <label for="developmentPeriod" class="col-form-label">Periodes</label>
                                    <input type="text" class="form-control" id="developmentPeriod" value="{{ old('verdere_ontwikkeling_periode') }}" name="verdere_ontwikkeling_periode" placeholder="12de, 13de eeuw">
                                </div>

                                <div class="form-group col-8">
                                    <label for="oldestUsage" class="col-form-label">Oudste vindplaats in het Nederlands (vorm, context, evt. betekenis)</label>
                                    <input type="text" class="form-control" id="oldestUsage" value="{{ old('oudste_vindplaats') }}" name="oudste_vindplaats" placeholder="Bijv. goeste, in 'lot may men goeste vray. Huygens.'">
                                </div>

                                <div class="form-group col-4">
                                    <label for="oldestUsagePeriod" class="col-form-label">Periode / Jaartal</label>
                                    <input type="number" min="500" max="{{ date('Y') }}" step="25" name="oudste_vindplaats_periode" placeholder="minimum jaar = 500" id="oldestUsagePeriod" value="{{ old('oudste_vindplaats_periode') }}" class="form-control">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="form-group col-12 mb-2">
                                    <label for="additionalInformation" class="col-form-label pt-0">Aanvullingen</label>
                                    <textarea
                                        class="form-control"
                                        name="aanvullingen"
                                        id="additionalInformation"
                                        name="aanvullingen"
                                        placeholder="Bijv. Bij gebrek aan vindplaatsen is niet duidelijk waarom en wanneer het achtervoegsel -ing is toegevoegd. Dat achtervoegsel wordt normaal gezien alleen bij werkwoordstammen toegevoegd."
                                        rows="4"
                                    >{{ old('aanvullingen') }}</textarea>
                                </div>

                                <div class="form-group col-6">
                                    <label for="sourceName" class="col-form-label">Naam van de bron (bijv. WNT, Etymologiebank, ...) <span class="fw-bold text-danger">*</span></label>

                                    <select name="bron_naam" id="sourceName" class="form-select @error('bron_naam') is-invalid @enderror">
                                        <option value="">-- selecteer waar je de etymologie hebt gevonden --</option>

                                        @foreach ($sources as $source)
                                            <option value="{{ $source->value }}" @selected (old('bron_naam') === $source->value)>
                                                {{ $source->getLabel() }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <x-forms.validation-error field="bron_naam"/>
                                </div>

                                <div class="form-group col-6">
                                    <label for="sourceUrl" class="col-form-label">Link naar de bron</label>
                                    <input type="text" class="form-control" id="sourceUrl" name="bron_hyperlink" value="{{ old('bron_hyperlink') }}" placeholder="Bijv. https://etymologiebank.nl/trefwoord/goesting">
                                </div>
                            </div>
                        </form>

                        <div class="card-footer bg-white">
                            <button type="submit" form="createSuggestion" class="btn btn-sm btn-suggestion-submit">
                                <x-tabler-send class="icon icon-sm me-1" /> Insturen
                            </button>
                            <button type="reset" form="createSuggestion" class="btn btn-link btn-sm">
                                <x-tabler-arrow-back-up class="icon icon-sm me-1 text-danger"/> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

