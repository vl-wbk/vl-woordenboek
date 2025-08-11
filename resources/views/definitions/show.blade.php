@extends ('layouts.application-blank', ['title' => $word->word])

@section ('openGraph')
    <meta property="og:title" content="{{ $word->word }} - {{ config('app.name', 'Laravel') }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ request()->fullUrl() }}"/>
    <meta property="og:description" content="{{ $word->description }}"/>
    <meta property="og:image" content="{{ asset('/img/app-logo.jpg') }}"/>
    <meta property="og:image_alt" content="Logo van het Vlaams woordenboek"/>
    <meta propery="og:local" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta property="og:article:published_time" content="{{ now()->parse($word->published_at)->toDatetimeString() }}"/>
    <meta property="og:article:modified_time" content="{{ now()->parse($word->updated_at)->toDatetimestring() }}"/>
    <meta property="og:article:author" content="{{ $word->editor->name ?? '' }}"/>
    <meta property="og:section" content="Linguistiek"/>
@endsection

@section ('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="w-100">
                <h3 class="color-green w-100 mb-2">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                        <x-heroicon-o-arrow-uturn-left class="icon icon-back-to-results"/>
                    </a>

                    <span class="text-muted">/</span>{{ $word->word }}

                    <div class="d-flex gap-2 float-end align-items-center">
                        @auth
                            <livewire:like-words :article="$word" />

                            @if ($word->bookmarkers->contains(auth()->user()))
                                <a href="{{ route('bookmark:remove', $word) }}" class="btn btn-light shadow-sm" title="Vergeet dit woord">
                                    <x:heroicon-o-bookmark-slash class="icon text-danger"/>
                                </a>
                            @else
                                <a href="{{ route('bookmark:create', $word) }}" class="btn btn-light shadow-sm" title="bewaar dit woord">
                                    <x:heroicon-o-bookmark class="icon text-success"/>
                                </a>
                            @endif
                        @endauth

                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle shadow-sm" title="Bijdragen aan het woordenboek" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <x:heroicon-o-plus class="icon color-green"/>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="{{ route('definitions.create') }}">
                                        <x:heroicon-o-document-plus class="icon text-muted me-1"/>Suggestie voor een nieuw woord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('etymology:create', $word) }}">
                                        <x:heroicon-o-plus class="icon text-muted me-1"/>Etymologie toevoegen
                                    </a>
                                </li>
                            </ul>
                        </div>

                        @auth
                            <a href="#" data-bs-toggle="modal" data-bs-target="#reportModal" class="btn btn-danger shadow-sm" title="Fout in het artikel melden">
                                <x:heroicon-s-exclamation-triangle class="icon"/>
                            </a>
                        @endauth
                    </div>
                </h3>
                <p class="mt-3">
                    @if ($word->partOfSpeech)
                        <span class="badge bg-secondary me-2">{{ $word->partOfSpeech->name }}</span>
                    @endif

                    <span class="fw-bold fst-italic text-muted">{{ $word->characteristics }}</span>
                </p>
            </div>
        </div>

        @if($word->isHidden() || $word->state->notIn([\App\Enums\ArticleStates::Archived, \App\Enums\ArticleStates::New]))
            <div class="alert alert-warning px-3 py-2 shadow-sm" role="alert">
                <span class="flex-shrink-0 me-1 fw-bold">
                    <x:heroicon-o-bell-alert class="icon"/> Melding:
                </span>
                Dit Woordenboek artikel is momenteel nodig niet publiceerd wegens dat het nog in de <strong>{{ $word->state->getLabel() }}</strong> status staat.
            </div>
        @endif

        <div class="card shadow-sm mt-2 {{ $word->disclaimer ? 'border-info' : 'border-0' }}">
            @if ($word->disclaimer)
                <div class="card-header text-info-emphasis border-bottom-0 bg-info-subtle">
                    <strong class="me-1">DISCLAIMER:</strong> {{ $word->disclaimer->message }}
                </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="{{ ($word->isPublished() || $word->state->in([\App\Enums\ArticleStates::Archived, \App\Enums\ArticleStates::New])) ? 'col-8' : 'col-12' }}">
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <h3 class="h6 text-muted fw-bold border-bottom pb-2">Regio(s)</h3>

                                @foreach($word->regions as $region)
                                    <a href="{{ route('region:show', $region) }}" class="badge badge-primary shadow-sm text-decoration-none me-2">
                                        {{ $region->name }}
                                    </a>
                                @endforeach
                            </div>

                            <div class="col-lg-6 mt-md-0 mt-sm-3">
                                <h3 class="h6 text-muted border-bottom pb-2 fw-bold">Label(s)</h3>

                                @forelse ($word->labels as $label)
                                    <a href="{{ route('label:show', $label) }}" class="badge bg-light text-dark shadow-sm text-decoration-none border me-2">
                                        <x-heroicon-o-tag class="icon me-1"/> {{ $label->name }}
                                    </a>
                                @empty
                                    <span class="text-muted fst-italic">- geen labels gekoppeld.</span>
                                @endforelse
                            </div>
                        </div>

                        <h5 class="text-muted border-bottom pb-2 fw-bold">Betekenis</h5>

                        <div class="d-flex">
                            @if ($word->image_url)
                                <div class="flex-shrink-0 d-sm-none d-md-block me-3">
                                    <img
                                        src="{{ $word->image_url ?? 'https://placehold.co/100x100?text=ongeldige+afbeelding&font=roboto' }}"
                                        alt="{{ $word->image_alt ?? trans('Helaas kunnen we afbeelden voor het artikel :article niet beschrijven', ['article' => $word->word]) }}"
                                        class="rounded border-0 shadow-sm"
                                        style="height: 100px; border: 0 !important; width: 100px;"
                                    />
                                </div>
                            @endif

                            <div class="flex-grow-1">
                                <div class="text-muted">
                                    <div class="markdown-text">
                                        {!! str($word->description)->markdown()->sanitizeHtml() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($word->isPublished() || $word->state->in([\App\Enums\ArticleStates::Archived, \App\Enums\ArticleStates::New]))
                        <div class="col-lg-4 mt-md-0 mt-sm-3">
                            <div class="card bg-secondary-subtle border-0">
                                <div class="card-body">
                                    @includeWhen($word->isPublished(), 'components.articles.stateInformation.published', ['word' => $word])
                                    @includeWhen($word->state->is(\App\Enums\ArticleStates::Archived), 'components.articles.stateInformation.archived', ['word' => $word])
                                    @includeWhen($word->state->is(\App\Enums\ArticleStates::New), 'components.articles.stateInformation.suggestion', ['word' => $word])
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-sidenav border-">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="example-tab" data-bs-toggle="tab" data-bs-target="#example-tab-pane" type="button" role="tab" aria-controls="example-tab-pane" aria-selected="true">
                            <x:heroicon-o-language class="icon color-green me-1"/>Voorbeeld gebruik
                        </button>
                    </li>

                    @if (count($etymologies) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="etymologie-tab" data-bs-toggle="tab" data-bs-target="#etymologie-tab-pane" type="button" role="tab" aria-controls="etymologie-tab-pane" aria-selected="true">
                                <x:heroicon-o-queue-list class="icon color-green me-1"/> Etymologieen
                            </button>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="articleInformationTab">
                    <div class="tab-pane fade show active" id="example-tab-pane" role="tabpanel" aria-labelledby="example-tab" tabindex="0">
                        <div class="markdown-text">
                            {!! str($word->example)->markdown()->sanitizeHtml() !!}
                        </div>
                    </div>

                    @if (count($etymologies) > 0)
                        <div class="tab-pane fade" id="etymologie-tab-pane" role="tabpanel" aria-labelledby="example-tab" tabindex="0">
                            <div class="accordion shadow-sm" id="etymologyAccordion">
                                @foreach ($etymologies as $etymology)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button show shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $etymology->id }}" aria-expanded="true" aria-controls="{{ $etymology->id }}">
                                                <strong>{{ $etymology->origin_period }} - {{ $etymology->origin }}</strong>
                                            </button>
                                        </h2>

                                        <div id="{{ $etymology->id }}" class="accordion-collapse collapse" data-bs-parent="#etymologyAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary">Oorsprong</h6>
                                                        <p class="mb-0"><strong>Oorsprong:</strong> {{ $etymology->origin }}</p>
                                                        <p class="mb-0"><strong>Periode:</strong> {{ $etymology->origin_period }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary">Vindplaats</h6>
                                                        <p class="mb-0"><strong>Oudste vindplaats:</strong> {{ $etymology->oldest_find_spot }}</p>
                                                        <p class="mb-0"><strong>Vindperiode:</strong> {{ $etymology->oldest_find_period }}</p>
                                                    </div>
                                                </div>

                                                <hr class="my-3">
                                                <h6 class="text-primary">Etymologie</h6>

                                                <p>{{ $etymology->etymology }}</p>

                                                <hr class="my-3">
                                                <h6 class="text-primary">Verdere Ontwikkeling</h6>

                                                <p class="mb-1"><strong>Periode:</strong> {{ $etymology->further_development_period }}</p>
                                                <p class="mb-0"><strong>Info:</strong> {{ $etymology->further_development }}</p>

                                                @if ($etymology->additional_info)
                                                    <hr class="my-3">
                                                    <h6 class="text-primary">Aanvullende informatie</h6>

                                                    <p>{{ $etymology->additional_info ?? '-' }}</p>

                                                @endif

                                                <small class="text-muted d-block mt-3">Bron: <a href="{{ $etymology->source_hyperlink }}">{{ $etymology->source_name->getLabel() }}</a></small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <livewire:report-article-modal :article=$word />
@endsection
