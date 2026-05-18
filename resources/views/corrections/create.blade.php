@extends('layouts.application-blank', ['title' => __('Correctie voorstellen: :word', ['word' => $word->word])])

@section('openGraph')
    <meta name="robots" content="noindex, nofollow" />
    
    <meta property="og:title" content="{{ $word->word }} - {{ config('app.name', 'Laravel') }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ request()->fullUrl() }}"/>
    <meta property="og:description" content="{{ $word->seo_description }}"/>
    <meta property="og:image" content="{{ asset('/img/app-logo.jpg') }}"/>
    <meta property="og:image:alt" content="Logo van het Vlaams woordenboek"/>
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta property="article:published_time" content="{{ optional($word->published_at)->toIso8601String() ?? \Illuminate\Support\Carbon::parse($word->created_at)->toIso8601String() }}"/>
    <meta property="article:modified_time" content="{{ optional($word->updated_at)->toIso8601String() ?? \Illuminate\Support\Carbon::now()->toIso8601String() }}"/>
    <meta property="og:article:author" content="{{ $word->editor->name ?? '' }}"/>
    <meta property="og:section" content="Linguïstiek"/>

    @if ($word->isArchived())
        <meta name="robots" content="noindex, follow" />
    @endif
@endsection

@section('content')
    <style>
        .markdown-text p:not(:last-child) { margin-bottom: .70rem; }
        
        /* Segmented Pill Navigation Styles */
        .segmented-tabs .nav-link {
            color: #6c757d !important;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
        }
        .segmented-tabs .nav-link:hover {
            color: #212529 !important;
            background-color: rgba(0, 0, 0, 0.03);
        }
        .segmented-tabs .nav-link.active {
            background-color: #ffffff !important;
            color: #212529 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        }
    </style>

    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                
                <!-- Breadcrumb Navigation Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none">
                                    <x-heroicon-o-home class="icon me-1" style="width: 1.1rem; height: 1.1rem; vertical-align: text-bottom;"/>
                                    {{ config('app.name', 'Laravel') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('search.results') }}" class="text-decoration-none">{{ __('Zoeken') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('word-information.show', $word) }}" class="text-decoration-none">{{ $word->word }}</a></li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">{{ __('correctie voorstellen') }}</li>
                        </ol>
                    </nav>
                    <a href="{{ route('word-information.show', $word) }}" class="btn btn-outline-secondary shadow-sm btn-sm d-inline-flex align-items-center">
                        <x-heroicon-o-arrow-left class="icon me-1" style="width: 1rem; height: 1rem;"/> Terug naar artikel
                    </a>
                </div>

                <!-- Main Grid Layout -->
                <div class="row g-4">
                    
                    <!-- ── LEFT COLUMN: CORRECTION FORM ── -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white border-bottom p-4">
                                <h2 class="h5 fw-bold text-dark mb-2 d-flex align-items-center">
                                    <x-heroicon-o-pencil-square class="color-green me-2" style="width: 1.5rem; height: 1.5rem;"/>
                                    Verbetering doorgeven
                                </h2>
                                <p class="text-muted small mb-0">
                                    Bedankt om mee te bouwen aan het Vlaams woordenboek. Wijzig hieronder de velden die een correctie vereisen.
                                </p>
                            </div>
                            
                            <div class="card-body bg-white p-4">
                                <form action="" method="POST">
                                    @csrf

                                    <!-- Target Word Display -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-uppercase text-muted small tracking-wider mb-1">Trefwoord</label>
                                        <div class="fw-bold text-success fs-4 py-0">{{ $word->word }}</div>
                                    </div>

                                    <!-- Proposed Description -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="description" class="form-label fw-semibold mb-0">Aangepaste beschrijving</label>
                                            <span class="badge text-bg-light border text-muted small fw-normal">Markdown toegestaan</span>
                                        </div>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" placeholder="Typ hier de gecorrigeerde definitie of betekenis...">{{ old('description', $word->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <hr>

                                    <!-- Context / Reason for Correction -->
                                    <div class="mb-4 p-3 bg-light shadow-sm rounded-2 border">
                                        <label for="reason" class="form-label fw-semibold text-dark d-flex align-items-center mb-2">
                                            <x-heroicon-s-chat-bubble-bottom-center-text class="icon color-green me-1" style="width: 1.25rem; height: 1.25rem;"/>
                                            Waarom is deze wijziging nodig? <span class="text-danger ms-1">*</span>
                                        </label>
                                        <textarea class="form-control bg-white @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" required placeholder="Bijv: 'De huidige betekenis klopt niet in de regio Antwerpen', of bronvermelding..."></textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Action Row -->
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                        <span class="text-muted small"><span class="text-danger">*</span> Verplicht veld</span>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('word-information.show', $word) }}" class="btn btn-link px-3 text-decoration-none text-secondary">Annuleren</a>
                                            <button type="submit" class="btn btn-submit shadow-sm px-4 fw-medium">
                                                <x-heroicon-s-paper-airplane class="icon me-1" style="transform: rotate(-45deg);"/> correctie indienen
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ── RIGHT COLUMN: CURRENT LIVE VERSION ── -->
                    <div class="col-lg-6">
                        <div class="position-sticky" style="top: 2rem;">
                            
                            <!-- Compact Alert Notice -->
                            <div class="alert alert-info border-0 shadow-sm mb-3 d-flex align-items-center gap-2 py-2 px-3" role="alert">
                                <x-heroicon-s-information-circle class="icon text-info flex-shrink-0" style="width: 1.25rem; height: 1.25rem;"/>
                                <div class="small">
                                    <strong class="text-dark">Huidige live-versie</strong> ter referentie.
                                </div>
                            </div>

                            <!-- Context Card View with App-style Tab Control -->
                            <div class="card shadow-sm border-0 bg-white" id="article-content">
                                <div class="card-header bg-white p-3 border-bottom-0">
                                    
                                    <!-- Meta Header Details -->
                                    <div class="mb-3 text-muted small">
                                        <!-- Top Row: Word and Part of Speech -->
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="fw-bold color-green fs-6">{{ $word->word }}</span>
                                            @if ($word->partOfSpeech)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle-semibold">{{ $word->partOfSpeech->name }}</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Bottom Row: Characteristics -->
                                        @if($word->characteristics)
                                            <div class="fst-italic text-secondary mt-1" style="font-size: 0.85rem;">
                                                {{ $word->characteristics }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- App-style Segmented Pills Control -->
                                    <div class="overflow-x-auto pb-1">
                                        <ul class="nav nav-pills bg-light rounded-2 p-1 gap-1 border segmented-tabs flex-nowrap" id="liveArticleTabs" role="tablist">
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button class="nav-link active w-100 small fw-semibold py-1.5 border-0 text-center" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-tab-pane" type="button" role="tab" aria-controls="desc-tab-pane" aria-selected="true">
                                                    <x-heroicon-s-document-text class="icon me-2 color-green"/> beschrijving
                                                </button>
                                            </li>
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button class="nav-link w-100 small fw-semibold py-1.5 border-0 text-center" id="regions-tab" data-bs-toggle="tab" data-bs-target="#regions-tab-pane" type="button" role="tab" aria-controls="regions-tab-pane" aria-selected="false">
                                                    <x-heroicon-s-map class="icon me-2 color-green"/> regio's
                                                </button>
                                            </li>
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button class="nav-link w-100 small fw-semibold py-1.5 border-0 text-center d-inline-flex align-items-center justify-content-center" id="labels-tab" data-bs-toggle="tab" data-bs-target="#labels-tab-pane" type="button" role="tab" aria-controls="labels-tab-pane" aria-selected="false">
                                                    <x-heroicon-s-tag class="icon me-2 color-green"/> labels
                                                </button>
                                            </li>
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button class="nav-link w-100 small fw-semibold py-1.5 border-0 text-center" id="related-tab" data-bs-toggle="tab" data-bs-target="#related-tab-pane" type="button" role="tab" aria-controls="related-tab-pane" aria-selected="false">
                                                    <x-heroicon-s-link class="icon me-2 color-green"/> gerelateerd
                                                </button>
                                            </li>
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button class="nav-link w-100 small fw-semibold py-1.5 border-0 text-center" id="sources-tab" data-bs-toggle="tab" data-bs-target="#sources-tab-pane" type="button" role="tab" aria-controls="sources-tab-pane" aria-selected="false">
                                                    <x-heroicon-s-book-open class="icon me-2 color-green"/> bronnen
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Card Body with Content Panels & Internal Scroll -->
                                <div class="card-body p-3 pt-0">
                                    <div class="tab-content overflow-y-auto px-1" id="liveArticleTabsContent" style="max-height: 50vh;">
                                        
                                        <!-- Tab 1: Description & Examples -->
                                        <div class="tab-pane fade show active text-secondary small markdown-text" id="desc-tab-pane" role="tabpanel" aria-labelledby="desc-tab" tabindex="0">
                                            beschrijving
                                        </div>

                                        <!-- Tab 2: Regions -->
                                        <div class="tab-pane fade small text-secondary" id="regions-tab-pane" role="tabpanel" aria-labelledby="regions-tab" tabindex="0">
                                            <strong class="d-block text-dark mb-1.5">Actieve Regio's:</strong>

                                            @forelse($word->regions as $region)
                                                <span class="badge rounded-pill text-bg-primary-subtle text-primary border border-primary-subtle me-1 mb-1">{{ $region->name }}</span>
                                            @empty
                                                <span class="badge rounded-pill text-bg-light border text-muted">Gans Vlaanderen</span>
                                            @endforelse
                                        </div>

                                        <!-- Tab 3: Labels -->
                                        <div class="tab-pane fade small text-secondary" id="labels-tab-pane" role="tabpanel" aria-labelledby="labels-tab" tabindex="0">
                                            <strong class="d-block text-dark mb-1.5">Gekoppelde labels:</strong>
                                            @php $hasPublicLabels = false; @endphp
                                            @foreach($word->labels as $label)
                                                @if(!$label->private)
                                                    <span class="badge text-bg-light border text-dark me-1 mb-1">{{ $label->name }}</span>
                                                    @php $hasPublicLabels = true; @endphp
                                                @endif
                                            @endforeach
                                            @if(!$hasPublicLabels)
                                                <span class="text-muted fst-italic">Geen publieke labels gekoppelde aan dit artikel.</span>
                                            @endif
                                        </div>

                                        <!-- Tab 4: Related Articles -->
                                        <div class="tab-pane fade small text-secondary" id="related-tab-pane" role="tabpanel" aria-labelledby="related-tab" tabindex="0">
                                            <strong class="d-block text-dark mb-1.5">Gerelateerde artikelen:</strong>
                                            @if(isset($word->relations) && $word->relations->count() > 0)
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($word->relations as $related)
                                                        <li class="mb-1">
                                                            <a href="{{ route('word-information.show', $related) }}" class="text-decoration-none text-success fw-medium">
                                                                {{ $related->word }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted fst-italic">Geen gerelateerde artikelen gevonden.</span>
                                            @endif
                                        </div>

                                        <!-- Tab 5: Sources -->
                                        <div class="tab-pane fade small text-secondary" id="sources-tab-pane" role="tabpanel" aria-labelledby="sources-tab" tabindex="0">
                                            <strong class="d-block text-dark mb-1.5">Geraadpleegde bronnen:</strong>
                                            @if(!empty($word->sources))
                                                <div class="lh-base">
                                                    {{ $word->sources }}
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Geen specifieke bronnen opgegeven voor dit artikel.</span>
                                            @endif
                                        </div>

                                    </div><!-- /tab-content -->
                                </div>
                            </div>

                        </div>
                    </div><!-- /col-lg-5 -->

                </div><!-- /row -->
                
            </div>
        </div>
    </div>
@endsection