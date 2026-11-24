<div class="border-top pt-3 mt-3">
    @if (session()->has('etymology-error'))
        <div class="alert alert-warning small py-2 mb-3" role="alert">
            {{ session('etymology-error') }}
        </div>if (session()->has('etymology-saved'))
    @endif

    @if ($showForm)
        {{-- Formulier (toevoegen of bewerken) --}}
        <form wire:submit="{{ $editing ? 'update' : 'save' }}"
              class="card bg-white shadow-none" style="border-style: dashed !important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="card-title mb-0 fw-semibold">
                        {{ $editing ? 'Etymologie bewerken' : 'Etymologie toevoegen' }}
                    </h6>
                    @if ($editing)
                        <span class="badge bg-warning-subtle text-warning-emphasis">Redactie</span>
                    @else
                        <span class="badge bg-info-subtle text-info-emphasis">Bijdrage</span>
                    @endif
                </div>

                <p class="text-body-secondary small mb-2">
                    @if ($editing)
                        Je wijzigingen zijn meteen zichtbaar voor alle bezoekers. De oorspronkelijke auteur blijft vermeld.
                    @else
                        Deel wat je weet over de herkomst of ontwikkeling van dit woord. Voeg indien mogelijk een bron toe.
                        Let op: je kunt maar één etymologie insturen.
                    @endif
                </p>

                <div class="mb-3">
                    <label for="etymology_origin" class="form-label small fw-medium mb-1">
                        Herkomst / verklaring <span class="text-danger">*</span>
                    </label>
                    <textarea
                        id="etymology_origin"
                        wire:model="origin"
                        rows="3"
                        class="form-control form-control-sm @error('origin') is-invalid @enderror"
                        placeholder="Bijv. afgeleid van het Frans ... of samengesteld uit ..."></textarea>
                    @error('origin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-sm-5">
                        <label for="etymology_language" class="form-label small fw-medium mb-1">
                            Bron- of herkomsttaal
                        </label>
                        <select
                            id="etymology_language"
                            wire:model="sourceLanguage"
                            class="form-select form-select-sm @error('sourceLanguage') is-invalid @enderror">
                            <option value="">Onbekend / niet van toepassing</option>
                            @foreach ($languages as $code => $label)
                                <option value="{{ $code }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('sourceLanguage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-sm-7">
                        <label for="etymology_source" class="form-label small fw-medium mb-1">
                            Bron of referentie
                        </label>
                        <input
                            type="text"
                            id="etymology_source"
                            wire:model="source"
                            class="form-control form-control-sm @error('source') is-invalid @enderror"
                            placeholder="Bijv. Etymologisch Woordenboek, artikel of link">
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" wire:click="cancel"
                            class="btn btn-sm btn-link text-body-secondary text-decoration-none">
                        Annuleren
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary"
                            wire:loading.attr="disabled" wire:target="save,update">
                        <span wire:loading.remove wire:target="save,update">
                            {{ $editing ? 'Wijzigingen opslaan' : 'Etymologie opslaan' }}
                        </span>
                        <span wire:loading wire:target="save,update">Opslaan…</span>
                    </button>
                </div>
            </div>
        </form>

    @elseif ($etymology)
        {{-- Bestaande etymologie --}}
        <section aria-labelledby="etymology-heading">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                <h5 id="etymology-heading" class="vote-card__title mb-0">
                    <span class="color-green me-1" aria-hidden="true">//</span>Etymologie
                </h5>

                @if ($canEdit)
                    <button type="button" wire:click="edit"
                            class="btn btn-sm btn-link text-decoration-none">
                        Bewerken
                    </button>
                @endif
            </div>

            <article class="card shadow-none" style="border-style:dashed;">
                <div class="card-body p-3">
                    <p class="mb-2" style="white-space: pre-line;">{{ $etymology->origin }}</p>

                    <div class="d-flex border-top pt-2 flex-wrap align-items-center column-gap-3 row-gap-1 small text-body-secondary">
                        @if ($etymology->source_language)
                            <span class="badge text-bg-light border fw-normal">
                                {{ $languages[$etymology->source_language] ?? $etymology->source_language }}
                            </span>
                        @endif

                        @if ($etymology->source)
                            <span>
                                <span class="fw-medium">Bron:</span>
                                @if (filter_var($etymology->source, FILTER_VALIDATE_URL))
                                    <a href="{{ $etymology->source }}" target="_blank" rel="noopener noreferrer nofollow">
                                        {{ \Illuminate\Support\Str::limit($etymology->source, 60) }}
                                    </a>
                                @else
                                    {{ $etymology->source }}
                                @endif
                            </span>
                        @endif

                        <span class="ms-sm-auto">
                            @if ($etymology->user)
                                {{ $etymology->user->name }} ·
                            @endif
                            <time datetime="{{ $etymology->created_at->toIso8601String() }}"
                                  title="{{ $etymology->created_at->format('d/m/Y H:i') }}">
                                {{ $etymology->created_at->diffForHumans() }}
                            </time>
                        </span>
                    </div>
                </div>
            </article>

            <p class="text-body-secondary small mt-2 mb-0">
                <strong class="fw-semibold">Let op:</strong>
                etymologieën zijn bijdragen van de community. De redactie controleert de juistheid
                ervan niet en is hier niet verantwoordelijk voor.
            </p>
        </section>

    @elseif (session()->has('etymology-saved'))
        <div class="alert alert-success small border-0 py-2 mb-3" role="alert" data-autofade>
            {{ session('etymology-saved') }}
        </div>
    @elseif($canCreate)
        etymologie toegevoegd
    @else
        {{-- Uitnodiging --}}
        <div class="card bg-white shadow-none" style="border-style: dashed !important;">
            <div class="card-body py-2 px-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <p class="mb-0 text-body-secondary small">
                    <strong class="text-body fw-semibold">Nog geen etymologie bekend.</strong>
                    Weet jij waar dit woord vandaan komt?
                </p>

                @auth
                    @if ($hasSubmitted)
                        <span class="small text-success">Je hebt al een etymologie ingestuurd.</span>
                    @else
                        <button type="button" wire:click="open" class="btn btn-sm btn-outline-primary text-nowrap">
                            Etymologie toevoegen
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary text-nowrap">
                        Log in om bij te dragen
                    </a>
                @endauth
            </div>
        </div>
    @endif
</div>
