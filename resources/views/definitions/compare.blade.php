@extends('layouts.application-blank', ['title' => 'Lexicale Vergelijking'])

@section('jumbotron')
    <div class="bg-white bg-blend-hard-light shadow-sm overflow-hidden">
        <div class="container-fluid px-3 px-sm-4 py-4 py-lg-5">
            <div class="row align-items-center">
                <nav class="col-12" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}">
                                <x-heroicon-o-home class="icon me-1"/> {{ config('app.name', 'Laravel') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('word-information.show', $articleA) }}">
                                {{ $articleA->word }}
                            </a>
                        </li>
                        
                        <li class="breadcrumb-item active" aria-current="page">vergelijker</li>
                    </ol>
                </nav>

                {{-- Text column: Centered on mobile, left-aligned on large --}}
                <div class="col-xl-5 col-lg-6 mb-4 mb-lg-0 text-center text-lg-start">
                    <h1 class="display-5 fw-bold color-green">Woordvergelijker</h1>
                    <p class="fs-5 text-muted mb-0">
                        {{  __('Wat is het verschil tussen :first en :second?', ['first' => $articleA->word, 'second' => $articleB->word]) }}
                    </p>
                </div>
                
                <div class="col-xl-7 col-lg-6">
                    <form action="{{ route('article:compare', ['word' => $articleA]) }}" method="GET" id="compareForm" class="bg-white shadow-sm p-3 rounded-4 border">
                        {{-- Changed to row-cols-1 for mobile stacking, row-cols-sm-auto for inline --}}
                        <div class="row g-3 align-items-center justify-content-center">
                        
                            {{-- Entry A --}}
                            <div class="col-12 col-sm">
                                <div class="form-floating">
                                    <select disabled class="form-select border-0 bg-light fw-bold" onchange="this.form.submit()">
                                        <option selected>{{ $articleA->word }}</option>
                                    </select>
                                    <label for="article_a" class="small text-uppercase fw-bold text-muted">Oorspronkelijk artikel</label>
                                </div>
                            </div>
                            
                            {{-- Separator: Horizontal on mobile, Vertical on desktop --}}
                            <div class="col-12 col-sm-auto text-center">
                                <span class="badge rounded-pill bg-dark px-3 py-2 shadow-sm">
                                    <x-heroicon-s-arrows-right-left class="icon"/>
                                </span>
                            </div>

                            {{-- Entry B --}}
                            <div class="col-12 col-sm">
                                <div class="form-floating">
                                    <select name="second_word" id="article_b" class="form-select border-0 bg-light fw-bold" onchange="this.form.submit()">
                                        @forelse($articleA->related as $item)
                                            <option value="{{ $item->id }}" {{ $articleB->id == $item->id ? 'selected' : '' }}>
                                                {{ $item->word }}
                                            </option>
                                        @empty
                                            <option selected>{{ $articleA->word }}</option>
                                        @endforelse
                                    </select>
                                    <label for="article_b" class="small text-uppercase fw-bold text-muted">Gerelateerd artikel</label>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-dark rounded-3 py-3 px-4 w-100 shadow-sm transition-all hover-lift">
                                    <x-heroicon-o-magnifying-glass style="width: 1.2rem;" class="me-1"/>
                                    <span class="d-sm-none d-xl-inline">Vergelijk</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="pt-4 pt-lg-5">
        <div class="container-fluid">
            <div class="row g-4"> 
                @foreach([['Oorspronkelijk artikel', $articleA], ['Gerelateerd artikel', $articleB]] as [$label, $article])
                    <div class="col-12 col-lg-6"> {{-- Stacked on mobile/tablet, side-by-side on large --}}
                        <div class="card h-100 shadow-sm border-0 border-top border-4 border-dark">
                            
                            <div class="card-header bg-white pt-4 px-3 px-sm-4 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    {{-- Word breaks on long text --}}
                                    <h3 class="card-title fw-bold color-green mb-0 text-break">{{ $article->word }}</h3>
                                    <span class="badge bg-light text-muted border ms-2">{{ $label }}</span>
                                </div>
                                
                                <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                                    @if ($article->partOfSpeech)
                                        <span class="badge badge-pill bg-success">
                                            {{ $article->partOfSpeech->name }}
                                        </span>
                                    @endif

                                    @if (! is_null($article->characteristics)) 
                                        <span class="text-muted opacity-50 d-none d-sm-inline">|</span>
                                        
                                        <span class="text-dark fw-bold fst-italic small">
                                            {{ $article->characteristics }}
                                        </span>
                                    @endif
                                </div>
                            <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                                 @if ($article->regions->count() > 0)
                                    @foreach ($article->regions as $region)
                                        <a href="" class="text-muted small">
                                            <x-heroicon-s-map-pin class="icon me-1 small text-primary"/>{{ $region->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="card-body bg-white px-3 px-sm-4">
                            <div class="mb-4">
                                <h6 class="text-uppercase tracking-wider text-muted fw-bold small mb-2">
                                    <span class="color-green me-1">//</span>
                                    Definitie
                                </h6>
                                
                                <div class="small text-secondary card-text">
                                    {!! str($article->description)->markdown()->sanitizeHtml() !!}
                                </div>
                            </div>

                            <div class="container px-0 mx-0">
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <h6 class="text-uppercase tracking-wider text-muted fw-bold small mb-2">
                                                <span class="color-green me-1">//</span>
                                                Kernwoorden
                                            </h6>
                                            
                                            <div class="pt-2">
                                                @foreach(explode(',', $article->keywords) as $keyword)
                                                    <span class="badge bg-light text-dark fw-normal border">
                                                        <x-heroicon-o-chat-bubble-left class="icon-sm me-1"/> {{ trim($keyword) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div>
                                            <h6 class="text-uppercase tracking-wider text-muted fw-bold small mb-2">
                                                <span class="color-green me-1">//</span>
                                                Labels
                                            </h6>
                                            
                                            <div class="pt-2">
                                                @forelse($article->labels as $label)
                                                    <a href="{{ route('label:show', $label) }}">
                                                        <span class="badge bg-dark text-light fw-normal border">
                                                            <x-heroicon-o-tag style="height: .75em" class="icon me-1"/> {{ $label->name }}
                                                        </span>
                                                    </a>
                                                @empty
                                                    <span class="text-muted fst-italic">- geen labels gekoppeld</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white px-3 px-sm-4 py-3">
                            <div class="row align-items-center g-2">
                                <div class="col-7">
                                    <p class="mb-0 small text-muted text-truncate">
                                        <x-heroicon-s-user-circle class="icon me-1 small"/> 
                                        <strong>
                                            @if ($article->author()->exists())
                                                <a href="{{ route('account:public', $article->author) }}" class="color-green">{{ $article->author->name ?? $article->contributor_name }}</a>
                                            @else
                                                <span class="fw-bold text-dark">{{ $article->contributor_name }}</span>
                                            @endif
                                        </strong>
                                    </p>
                                    <p class="mb-0 text-muted" style="font-size: 0.7rem;">
                                        {{ $article->published_at ? $article->published_at->format('d/m/Y') : 'n.v.t.' }} 
                                        &bull; {{ number_format($article->views, 0, ',', '.') }} weergaves
                                    </p>
                                </div>
                                <div class="col-5 text-end">
                                    <a href="{{ route('word-information.show', $article) }}" class="btn shadow-sm btn-sm btn-outline-dark">
                                        Details <x-heroicon-o-chevron-right class="icon small"/>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection