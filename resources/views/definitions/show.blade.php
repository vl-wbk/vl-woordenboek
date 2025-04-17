@extends ('layouts.application-blank')

@section ('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">
                        <a href="{{ url()->previous() }}" class="color-green text-decoration-none">
                            <x-heroicon-o-arrow-uturn-left class="icon icon-back-to-results"/>
                        </a>

                        <span class="text-muted">/</span>Artikelinformatie
                    </h3>

                    <span class="text-danger">
                        <x-heroicon-s-bell-alert class="icon"/>
                        Dit artikel werd nog niet redactioneel bewerkt en daarom kan de kwaliteit ontoereikend zijn
                    </span>
                </div>

                @auth
                    <div class="btn-toolbar float-end" role="toolbar" aria-label="Toolbar with filters and functionalities">
                        <div class="btn-group me-3 shadow-sm">
                            <livewire:like-words :article="$word"/>
                            <a href="#" class="btn border-0 btn-light">
                                <x-heroicon-o-bookmark class="icon color-green"/> bewaren
                            </a>
                        </div>

                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-danger btn-sm float-end" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <x-tabler-file-alert class="icon"/> rapporteren
                            </button>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-8">
                <div class="card card-body shadow-sm border-0">
                    <div>
                        <h4 class="text-gold float-start">{{ $word->word }}</h4>
                        <span class="float-end color-green">{{ $word->status->getLabel() }}</span>
                    </div>

                    <ul class="list-unstyled mb-0 text-muted-bottom">
                        <li class="mb-1">
                            @if ($word->partOfSpeech)
                                <span class="badge text-bg-secondary me-1">{{ $word->partOfSpeech->name }}</span>
                            @endif

                            {{ $word->characteristics }}
                        </li>
                    </ul>

                    <div class="mt-2 text-muted">
                        {!! str($word->description)->sanitizeHtml() !!}
                    </div>
                </div>

                <hr>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-sidenav border-0">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="example-tab" data-bs-toggle="tab" data-bs-target="#example-tab-pane" type="button" role="tab" aria-controls="example-tab-pane" aria-selected="true">
                                    <x-heroicon-o-language class="icon"/> Voorbeeld gebruik
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="region-tab" data-bs-toggle="tab" data-bs-target="#region-tab-pane" type="button" role="tab" aria-controls="region-tab-pane" aria-selected="true">
                                    <x-heroicon-o-map-pin class="icon"/> Regio's
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="label-tab" data-bs-toggle="tab" data-bs-target="#label-tab-pane" type="button" role="tab" aria-controls="label-tab-pane" aria-selected="true">
                                    <x-heroicon-o-tag class="icon"/> Labels
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="example-tab-pane" role="tabpanel" aria-labelledby="example-tab" tabindex="0">
                                {!! str($word->example)->sanitizeHtml() !!}
                            </div>

                            <div class="tab-pane fade show" id="region-tab-pane" role="tabpanel" aria-labelledby="region-tab" tabindex="0">
                                <ul class="list-unstyled mb-0">
                                    @forelse ($word->regions as $region)
                                        <li><x-heroicon-o-map class="icon color-green me-1"/> {{ $region->name }}</li>
                                    @empty
                                        <li class="text-muted">- Geen regio voor het woord gevonden</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="tab-pane fade show" id="label-tab-pane" role="tabpanel" aria-labelledby="label-tab" tabindex="0">
                                <ul class="list-unstyled mb-0">
                                    @forelse ($word->labels as $label)
                                        <li>
                                            <x-heroicon-o-map class="icon me-1"/> {{ $label->name }}
                                        </li>
                                    @empty
                                        <li class="text-muted">- Geen labels voor het woord gevonden</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-sidenav shadow-sm border-0 card-body">
                    <h5 class="card-title fw-bold color-green border-dark-subtle border-bottom">Redactie informatie</h5>

                    <dl class="row mt-2">
                        <dt class="col-sm-5">Suggestie door</dt>
                        <dd class="col-sm-7"><span class="float-end">{{ $word->author->name ?? 'onbekend' }}</span></dd>
                        <dt class="col-sm-5">Redacteur</dt>
                        <dd class="col-sm-7"><span class="float-end">{{ $word->editor->name ?? 'onbekend' }}</span></dd>
                        <dt class="col-sm-5">Eindredacteur</dt>
                        <dd class="col-sm-7"><span class="float-end">{{ $word->publisher->name ?? 'onbekend' }}</span></dd>
                    </dl>

                    <h5 class="card-title fw-bold color-green border-dark-subtle border-bottom">Publicatie gegevens</h5>

                    <dl class="row mt-2 mb-0">
                        <dt class="col-sm-5">Publicatiedatum</dt>
                        <dd class="col-sm-7"><span class="float-end">{{ $word->created_at->format('d/m/Y') }}</span></dd>
                        <dt class="col-sm-5">Laatste bewerking</dt>
                        <dd class="col-sm-7"><span class="float-end">{{ $word->updated_at->format('d/m/Y') }}</span></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <livewire:report-article-modal :article=$word />
@endsection
