@extends('layouts.application-blank', ["title" => __('pages/welcome.page-title')])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="py-5">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <h1 class="display-6 fw-bold">{!! __('pages/welcome.jumbotron.headings.welcome', ['applicationName' => config('app.name', 'Laravel')]) !!}</h1>
                    </div>

                    <div class="col-10">
                        <p class="pb-3 fs-5">
                            {{ __('pages/welcome.jumbotron.leading-paragraph', ['articleCount' => $articleCount]) }}
                        </p>
                    </div>

                    <form class="col-lg-10" action="{{ route('search.results') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-12 col-lg-3">
                                <label for="searchPatternSelect" class="visually-hidden">{{ __('components/search-form.pattern-select') }}</label>
                                <select name="zoekpatroon" class="form-select bg-white shadow-sm" id="searchPatternSelect">
                                    @foreach ($searchPatterns as $searchPattern)
                                        <option value="{{ $searchPattern->value }}" @selected(old('zoekpatroon', request()->get('zoekpatroon')) === $searchPattern->value)>
                                            {{ $searchPattern->getLabel() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-8 col-lg-7">
                                <input type="text" class="form-control bg-white shadow-sm" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="{{ __('components/search-form.inputs.search-term.placeholder') }}" aria-label="searchterm">
                            </div>
                            <div class="col-12 col-sm-4 col-lg-2">
                                <button type="submit" class="btn shadow-sm w-100 btn-submit">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/> {{ __('components/search-form.buttons.submit') }}
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="uitgebreid" type="checkbox" id="checkChecked" value="1" @checked(request()->boolean('uitgebreid') === true) switch>
                                    <label class="form-check-label" for="checkChecked">
                                        {{ __('components/search-form.toggles.description-search') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="archief" type="checkbox" id="checkCheckedArchived" value="1" @checked(request()->boolean('archief') === true) switch>
                                    <label class="form-check-label" for="checkCheckedArchived">
                                        {{ __('Ik wens ook te zoeken in het archief') }}
                                    </label>
                                </div>
                            </div>

                            {{-- ... existing checkboxes for uitgebreid and archief ... --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
   <style>
    /* 1. Hide the overflow and ensure the track stays on one line */
    .marquee-box {
        overflow: hidden;
        white-space: nowrap;
        display: flex;
        align-items: center;
        /* Ensure it spans full width when stacked */
        width: 100%;
        mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    }

    /* 2. The track that holds the words */
    .marquee-track {
        display: flex;
        gap: 0.5rem; 
        padding-left: 0.5rem;
        /* Faster base speed for mobile */
        animation: scroll-rtl 175s linear infinite;
    }

    /* 3. The logic: move half the track's width then snap back */
    @keyframes scroll-rtl {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* 4. Pause on hover and touch for usability */
    .marquee-track:hover, .marquee-track:active {
        animation-play-state: paused;
    }

    /* Original styles preserved */
    .trending-tag {
        transition: all 0.2s ease-in-out;
    }

    .trending-tag:hover {
        background-color: var(--bs-primary) !important;
        color: white !important;
        border-color: var(--bs-primary) !important;
    }

    /* Desktop adjustments: Slow down speed and adjust spacing */
    @media (min-width: 992px) {
        .marquee-track {
            animation-duration: 125s;
        }
    }
</style>

<div class="bg-white">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 my-2 d-flex flex-column flex-lg-row align-items-start align-items-lg-center">
                
                <span class="text-muted fw-bold text-uppercase mb-2 mb-lg-0 me-3" 
                      style="font-size: 0.75rem; letter-spacing: 0.5px; white-space: nowrap;">
                    {{ __('Snuister eens door deze woorden:') }}
                </span>
                
                <div class="marquee-box">
                    <div class="marquee-track">    
                        {{-- First Set --}}
                        @foreach($trendingWords as $word)
                            <a href="{{ route('word-information.show', $word) }}" 
                               class="badge rounded-pill bg-white text-dark border text-decoration-none py-2 px-3 shadow-sm trending-tag">
                                {{ $word->word }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid py-5">
    @if ($wordOfTheDay)
        {{-- Woord van de Dag --}}
    <article class="row mb-3 justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 bg-transparent">
                <div class="card-body p-0 border-start border-4 border-success ps-4">
                    <header>
                        <span class="text-success small fw-bold text-uppercase tracking-widest">Woord van de dag</span>
                        <h2 class="display-6 fw-bold mt-2 mb-0">
                            {{ $wordOfTheDay->article->word }}
                            <small class="text-muted fw-light fs-4 ms-2 font-monospace">{{ $wordOfTheDay->article->characteristics }}</small>
                        </h2>
                    </header>
                    <div class="markdown-text my-3">
                        {!! str($wordOfTheDay->article->description)->words(22)->markdown()->sanitizeHtml() !!}
                    </div>
                    <footer>
                        <a href="{{  route('word-information.show', $wordOfTheDay->article) }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold">
                            LEES VOLLEDIG ARTIKEL
                        </a>
                    </footer>
                </div>
            </div>
        </div>
    </article>

    <div class="row justify-content-center">
        <div class="col-lg-10"><hr class="my-5 opacity-10"></div>
    </div>
    @endif

    {{-- Footer Actions --}}
    <section class="row justify-content-center">
    <div class="col-lg-10">
        <div class="row g-4">
            <div class="col-md-4 d-flex">
                <div class="card bg-white border shadow-sm rounded-4 p-4 w-100 d-flex flex-column">
                    <h6 class="fw-bold text-uppercase small text-success mb-1">{{ __('pages/welcome.call-outs.suggestion.title') }}</h6>
                    <p class="fw-bold text-dark small mb-3">{{ __('pages/welcome.call-outs.suggestion.subtitle') }}</p>
                    
                    <p class="text-muted small">
                        {{ __('pages/welcome.call-outs.suggestion.text') }}
                    </p>
                    
                    <div class="mt-auto pt-3">
                        <a href="{{ route('definitions.create') }}" class="fw-bold text-dark text-decoration-none link-underline">
                            {{ __('pages/welcome.call-outs.suggestion.actionText') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card bg-white border shadow-sm rounded-4 p-4 w-100 d-flex flex-column">
                    <h6 class="fw-bold text-uppercase small text-success mb-1">{{ __('pages/welcome.call-outs.volunteer.title') }}y</h6>
                    <p class="fw-bold text-dark small mb-3">{{ __('pages/welcome.call-outs.volunteer.subtitle') }}</p>
                    
                    <p class="text-muted small">
                        {{ __('pages/welcome.call-outs.volunteer.text') }}
                    </p>
                    
                    <div class="mt-auto pt-3">
                        <a href="{{ route('support.volunteers') }}" class="fw-bold text-dark text-decoration-none link-underline">
                            {{ __('pages/welcome.call-outs.volunteer.actionText') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card bg-white border shadow-sm rounded-4 p-4 w-100 d-flex flex-column">
                    <h6 class="fw-bold text-uppercase small text-success mb-1">{{ __('pages/welcome.call-outs.information.title') }}</h6>
                    <p class="fw-bold text-dark small mb-3">{{ __('pages/welcome.call-outs.information.subtitle') }}</p>
                    
                    <p class="text-muted small">
                        {{ __('pages/welcome.call-outs.information.text') }}
                    </p>
                    
                    <div class="mt-auto pt-3">
                        <a href="{{ route('project-information') }}" class="fw-bold text-dark text-decoration-none link-underline">
                            {{ __('pages/welcome.call-outs.information.actionText') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="row justify-content-center">
    <div class="col-lg-10"><hr class="my-5 opacity-10"></div>
</div>

    {{-- Grid: Laatste Woorden --}}
    <section class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="fw-bold h4 m-0">Laatst toegevoegd</h3>
                {{-- <a href="#" class="link-primary text-decoration-none small fw-bold">Bekijk alle woorden &rarr;</a> --}}
            </div>    

            @foreach($recent as $result)
    <div class="lexi-card {{ $loop->last ? 'mb-0' : '' }}">
   @if ($result->regions()->exists())
    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach($result->regions as $region)
            <span class="lexi-tag-enhanced">
                <x-heroicon-o-map-pin class="icon me-1"/> {{ $region->name }}
            </span>
        @endforeach
    </div>
@endif

    <div class="content-body">
        <a href="{{ route('word-information.show', $result) }}" class="text-decoration-none">
            <h4 class="word-title mb-2">{{ $result->word }} <span class="word-type ms-2">{{ strtolower($result->characteristics) }}</span></h3>
        </a>

        <div class="text-secondary opacity-75 mb-2" style="font-weight: 400;">
            {!! str($result->description)->words(22)->markdown()->sanitizeHtml() !!}
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light-subtle">
        @if ($result->author)
            <span class="small text-muted">
                @if ($result->author()->exists())
                     Door <span class="text-dark fw-semibold">{{ $result->author->name ?? $result->contributor_name ?? config('app.name') }}</span>
                @else
                    Door <span class="text-dark fw-semibold">{{  $result->contributor_name ?? config('app.name') }}</span>
                @endif

                <span class="">•</span> {{  __('Weergaves: :count', ['count' => $result->views]) }}
            </span>
        @endif

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('word-information.show', $result) }}" 
               class="btn btn-sm rounded-pill btn-outline-dark fw-bold btn-sm shadow-sm">
                Ontdek <x-heroicon-o-arrow-right class="icon-sm ms-1"/>
            </a>
        </div>
    </div>
</div>
@endforeach
    </section>
</div>
@endsection