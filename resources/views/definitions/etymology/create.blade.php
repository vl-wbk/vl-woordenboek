@extends ('layouts.application-blank', ['title' => 'Etymology'])

@section('content')
<div class="container">
    <div class="row mb-1">
        <div class="col-12">
            <div class="float-start">
                <h3>
                    <a href="{{ url()->previous() }}" class="text-muted text-decoration-none">
                        <x-heroicon-o-arrow-uturn-left class="icon icon-back-to-results"/>
                    </a>

                    <span class="text-muted">/</span>
                    Etymologie gegevens toevoegen voor het woord: <span class="color-green color-green fw-bold">{{ $article->word }}</span>
                </h3>

                <p class="text-muted mb-3">
                    Elke bezoeker van het Vlaams Woordenboek kan Etymologische gegevens indienen. Die worden beoordeeld door een redacteur voor ze online verschijnen.
                    Met het onderstaande formulier kun je de gegevens versturen naar de redactie zodat zij ermee aan de slag kunnen.
                </p>
            </div>
        </div>

        <div class="col-12">
            <ul class="list-inline mb-0">
                <li class="list-inline-item"><span class="color-green">ID:</span> <span class="fw-bold">#ARTIKEL-{{ $article->id }}</span></li>
                <li class="list-inline-item text-muted">|</li>
                <li class="list-inline-item"><span class="color-green">Aangemaakt op:</span> <span class="fw-bold">{{ $article->created_at->format('d/m/Y') }}</span></li>
                <li class="list-inline-item text-muted">|</li>
                <li class="list-inline-item"><span class="color-green">Laatste wijziging:</span> <span class="fw-bold">{{ $article->updated_at->diffForHumans() }}</span></li>
            </ul>
        </div>
    </div>

    <hr class=" mt-2 mb-3">

    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 shadow-sm">
                <form method="POST" id="createSuggestion" action="{{ route('etymology:store', $article) }}" class="card-body">
                    @csrf {{-- Form field protection --}}

                    <div class="row mb-2">
                        <div class="form-group col-6 mb-2">
                            <label for="periodStart" class="col-form-label">Periode <span class="fw-bold fst-italic">(start)</span></label>
                            <input type="date" class="form-control" name="periode_start" value="{{ old('periode_start') }}" />
                        </div>

                        <div class="form-group col-6 mb-2">
                            <label for="periodEnd" class="col-form-label">Periode <span class="fw-bold fst-italic">(einde)</span></label>
                            <input type="date" class="form-control" name="periode_eind" value="{{ old('periode_eind') }}" />
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="row mb-2">
                        <div class="form-group col-4">
                            <label for="type" class="col-form-label">Type van het woord <span class="fw-bold text-danger">*</span></label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- selecteer een woord type --</option>

                                @foreach ($types as $type)
                                    <option value="{{ $type->value }}" @selected (old('type') === $type->value)>
                                        {{ $type->getLabel() }}
                                    </option>
                                @endforeach
                            </select>

                            <x-forms.validation-error field="type"/>
                        </div>

                        <div class="form-group col-4 mb-2">
                            <label for="originLanguage" class="col-form-label">Oorspronkelijke taal <span class="fw-bold text-danger">*</span></label>
                            <input type="text" name="oorspronkelijke_taal" class="form-control @error('oorspronkelijke_taal') is-invalid @enderror" value="{{ old('oorspronkelijke_taal') }}" name="oorspronkelijke_taal" id="originLanguage">
                            <x-forms.validation-error field="oorspronkelijke_taal"/>
                        </div>

                        <div class="form-group col-4 mb-2">
                            <label for="originForm" class="col-form-label">Vorm in de brontaal (vb. Hospitale) <span class="fw-bold text-danger">*</span></label>
                            <input type="text" name="oorspronkelijke_vorm" class="form-control @error('oorspronkelijke_vorm') is-invalid @enderror" value="{{ old('oorspronkelijke_vorm') }}" name="oorspronkelijke_vorm" id="originForm">
                            <x-forms.validation-error field="oorspronkelijke_vorm"/>
                        </div>

                        <div class="form-group col-12 mb-2">
                            <label for="etymology" class="col-form-label">Etymologie</label>
                            <textarea name="etymologie" class="form-control" id="etyomologie" rows="5">{{ old('etymologie') }}</textarea>
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="row">
                        <div class="form-group col-5">
                            <label for="sourceText" class="col-form-label">Naam van de bron (bv. WNT, EWN) <span class="fw-bold text-danger">*</span></label>
                            <input id="sourceText" type="text" class="form-control @error('bron') is-invalid @enderror" value="{{ old('bron') }}" name="bron"/>
                            <x-forms.validation-error field="bron"/>
                        </div>

                        <div class="form-group col-7">
                            <label for="sourceUrl" class="col-form-label">Link naar de bron</label>
                            <input id="sourceUrl" type="text" class="form-control" value="{{ old('url_bron') }}" name="url_bron" placeholder="https://www.voorbeeld.be">
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
@endsection

