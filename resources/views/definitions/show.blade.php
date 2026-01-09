@extends ('layouts.application-blank', ['title' => $word->word])

@section ('openGraph')
    <meta property="og:title" content="{{ $word->word }} - {{ config('app.name', 'Laravel') }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ request()->fullUrl() }}"/>
    <meta property="og:description" content="{{ $word->description }}"/>
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

@section ('content')
<div class="word-nav-bar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><x-heroicon-o-home class="icon me-1"/>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $word->word }}</li>
                </ol>
            </nav>
            <a href="{{ url()->previous() }}" class="back-link">
                <x-heroicon-o-arrow-left style="width:1rem"/>
                Terug naar vorige pagina
            </a>
        </div>
    </div>
</div>

<div class="word-header">
    <div class="container-fluid">
        <div class="row align-items-start">
            <div class="col-lg-8">
                {{-- Regio Sectie --}}
                <div class="mb-2 d-flex flex-wrap align-items-center gap-2">
                    @foreach($word->regions as $region)
                        <a href="{{ route('region:show', $region) }}" class="text-decoration-none d-flex align-items-center" 
                           style="font-size: 0.75rem; font-weight: 700; color: var(--lexi-region-text); text-transform: uppercase; letter-spacing: 0.025em;">
                            <x-heroicon-s-map-pin style="width:0.9rem; margin-right: 0.2rem;"/>
                            {{ $region->name }}
                        </a>
                        @if(!$loop->last) <span class="text-muted opacity-25">|</span> @endif
                    @endforeach
                </div>
                
                <h1 class="display-3 fw-bold mb-1">{{ $word->word }}</h1>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="d-flex w-100 align-items-center gap-2 text-muted me-2">
                        @if ($word->partOfSpeech)
                            <span class="fw-bold text-dark">{{$word->partOfSpeech->name }}</span>
                            <span>•</span>
                        @endif
                        
                        <span class="font-monospace">{{ $word->characteristics }}</span>
                    </div>

                    {{-- Status Labels --}}
                    @if ($word->labels()->exists())
                        <div class="mt-3">
                            @foreach ($word->labels as $label)
                                <a href="{{ route('label:show', $label) }}" class="shadow-sm word-label"><x-heroicon-o-tag class="icon me-1"/> {{ $label->name}}</a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @auth
                    <div class="header-actions mt-4">
                        @if ($word->bookmarkers->contains(auth()->user()))
                            <a href="{{ route('bookmark:remove', $word) }}" class="action-link-btn text-danger text-decoration-none opacity-75">
                                <x:heroicon-o-bookmark-slash style="width:1.1rem"/> Vergeet dit woord
                            </a>
                        @else
                            <a href="{{ route('bookmark:create', $word) }}" class="action-link-btn text-decoration-none"><x-heroicon-o-bookmark style="width:1.1rem"/> Bewaar</a>
                        @endif

                        <button class="action-link-btn ms-2 text-danger opacity-75"  data-bs-toggle="modal" data-bs-target="#reportModal">
                            <x-heroicon-o-megaphone style="width:1.1rem"/> Verbetering melden
                        </button>
                    </div>
                @endauth
            </div>
            
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('definitions.create') }}" class="btn btn-outline-dark px-4 rounded-1">
                    <x:heroicon-s-document-plus class="icon me-1"/>suggestie voor een nieuw artikel
                </a>

                @can ('update', $word)
                    <a href="{{ $editLink }}" class="btn btn-dark px-4 rounded-1">
                        <x-heroicon-s-pencil-square class="icon me-1"/> bewerk artikel
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

@if ($word->disclaimer)
    <div class="alert alert-info border-0 mb-0" role="alert">
        <p>{{ $word->disclaimer->message }}</p>
    </div>
@endif

{{-- Main Content --}}
<div class="container-fluid py-5">
    <div class="row">
        <div class="col-lg-9 pe-lg-5">
            <section class="mb-5">
                <h5 class="fw-bold mb-3 text-success">Definitie</h5>
                <div class="d-flex">
                    @if ($word->image_url)
                        <div class="flex-shrink-0 d-sm-none d-md-block me-3">
                            <img
                                src="{{ $word->image_url ?? 'https://placehold.co/100x100?text=ongeldige+afbeelding&font=roboto' }}"
                                alt="{{ $word->image_alt ?? trans('Helaas kunnen we afbeelden voor het artikel :article niet beschrijven', ['article' => $word->word]) }}"
                                class="rounded border-0 shadow-sm"
                                style="height: 150px; border: 0 !important; width: 150px;"
                            />
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="text-muted">
                            <div class="markdown-text lh-base text-dark">
                                {!! str($word->description)->markdown()->sanitizeHtml() !!}
                           </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 color-green">Voorbeelden</h5>
                </div>
                <div>
                    {!! str($word->example)->markdown()->sanitizeHtml() !!}
                </div>
            </section>

            @if ($word->related()->exists())
                <section class="mb-5">
                    <h5 class="fw-bold color-green mb-3">Gerelateerde Woorden</h5>
                    <div class="d-flex flex-wrap">
                        @foreach($word->related as $related)
                            <a href="{{ route('word-information.show', $related) }}" class="related-chip shadow-sm">
                                <x-heroicon-o-document-text class="icon color-green me-1"/> {{ $related->word }}
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($word->sources && $word->sources->count() > 0)
    <section class="mb-2">
        <h5 class="fw-bold mb-3 d-flex align-items-center color-green">
            Bronnen & Referenties
        </h5>
        
        <div class="sources-list">
            @foreach($word->sources as $source)
                <div class="source-item shadow-sm">
                    <div class="source-icon">
                     
                            <x-heroicon-s-book-open style="width: 1.2rem;"/>
                
                    </div>
                    <div class="flex-grow-1">
                      
                            <span class="source-link fw-semibold">{{ $source->referenceWork->name }}</span>
                   
                        
                        @if($source->notation)
                            <p class="mb-0 small text-muted mt-1">{{ $source->notation }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

            <section class="border-top">
                 {{-- Contributor Details --}}
                <div class="contributor-box bg-white shadow-sm">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                        <x-heroicon-o-user style="width: 1.5rem;" class="text-muted" />
                    </div>
                    <div>
                        <p class="mb-1 small text-muted">
                            Toegevoegd door 
                            
                            @if ($word->author()->exists())
                                <a href="{{ route('account:public', $word->author) }}" class="fw-bold text-dark">{{ $word->author->name ?? $word->contributor_name }}</a>
                            @else
                                <span class="fw-bold text-dark">{{ $word->contributor_name }}</span>
                            @endif
                        </p>
                        <p class="mb-0 extra-small text-muted" style="font-size: 0.75rem;">
                            Gepubliceerd op {{ optional($word->published_at)->format('d M Y') ?? $word->created_at->format('d M Y') }} 
                            <span class="vr mx-2"></span>
                         Laatst bijgewerkt op {{ $word->updated_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <aside class="col-lg-3">
            {{-- Community Stats --}}
            <div class="card border-0 bg-dark text-white rounded-4 overflow-hidden mb-4 shadow-sm">
                <div class="p-4">
                    <h6 class="small fw-bold text-uppercase opacity-50 mb-3">Hoe ligt dit woord in de community?</h6>
                    <div class="stats-dashboard @auth mb-3 @endauth">
                        <div class="stat-item">
                            <span class="d-block fw-bold">{{ toHumanReadableNumber($word->likers()->count()) }}</span>
                            <span class="extra-small opacity-50" style="font-size: 0.7rem;">Stemmen</span>
                        </div>
                        <div class="stat-item">
                            <span class="d-block fw-bold">{{ toHumanReadableNumber($word->views) }}</span>
                            <span class="extra-small opacity-50" style="font-size: 0.7rem;">Weergaves</span>
                        </div>
                        <div class="stat-item">
                            <span class="d-block fw-bold">{{ toHumanReadableNumber($word->bookmarkers()->count()) }}</span>
                            <span class="extra-small opacity-50" style="font-size: 0.7rem;">Bookmarks</span>
                        </div>
                    </div>
                    
                    @auth
                        <livewire:like-words :article="$word" />
                    @endauth
                </div>
            </div>
        </aside>
    </div>
</div>

<livewire:report-article-modal :article=$word />
@endsection