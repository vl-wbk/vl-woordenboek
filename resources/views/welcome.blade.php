@extends('layouts.application-blank', ["title" => __('pages/welcome.page-title')])

@section('jumbotron')
    <header class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
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
                                <label for="searchTerm" class="visually-hidden">Definitie opzoeken</label>
                                <input type="text" id="searchTerm" class="form-control bg-white shadow-sm" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="{{ __('components/search-form.inputs.search-term.placeholder') }}" aria-label="searchterm">
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

<div class="bg-white">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 my-2 d-flex flex-column flex-lg-row" style="align-items: first baseline;">

                <div class="text-muted fw-bold text-uppercase mb-2 mb-lg-0 me-3"
                      style="font-size: 0.75rem; letter-spacing: 0.5px; white-space: nowrap;">
                    {{ __('Snuister eens door deze woorden:') }}
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem 1rem">
                    @foreach($trendingWords as $word)
                        <a class="fw-bold text-dark" href="{{ route('word-information.show', $word) }}">{{ $word->word }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</header>
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
                            <span class="text-success small fw-bold text-uppercase tracking-widest" style="font-size: 0.7rem;">Woord van de dag</span>
                            <h2 class="display-6 fw-bold mt-1 mb-0">
                                {{ $wordOfTheDay->article->word }}
                                <small class="text-muted fw-light fs-5 ms-2 font-monospace">{{ $wordOfTheDay->article->characteristics }}</small>
                            </h2>
                        </header>

                        <div class="markdown-text my-3 text-secondary">
                            {!! str($wordOfTheDay->article->description)->words(20)->markdown()->sanitizeHtml() !!}
                        </div>

                        <footer class="d-flex flex-wrap align-items-center gap-4 mt-4">
                            <a href="{{ route('word-information.show', $wordOfTheDay->article) }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 fw-bold">
                                LEES VOLLEDIG ARTIKEL
                            </a>

                            <div class="d-flex">
                                <a href="{{ url('/feed/woord-van-de-dag') }}" class="text-rss text-decoration-none">
                                    <x-heroicon-o-rss class="icon text-danger me-1"/> RSS feed
                                </a>
                            </div>
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
                <div class="col d-flex">
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

                @if (app(\App\Settings\VolunteerSettings::class)->pageActive)
                    <div class="col d-flex">
                        <div class="card bg-white border shadow-sm rounded-4 p-4 w-100 d-flex flex-column">
                            <h6 class="fw-bold text-uppercase small text-success mb-1">{{ __('pages/welcome.call-outs.volunteer.title') }}</h6>
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
                @endif

                <div class="col d-flex">
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
                <a href="{{ route('search.results') }}" class="link-primary text-decoration-none small fw-bold">Bekijk alle woorden &rarr;</a>
            </div>

            <div class="row g-4">
                @foreach($recent as $result)
                    <div class="col-4 d-flex align-items-stretch">
                        <div class="word-card d-flex flex-column shadow-sm w-100">

                            @if ($result->regions()->exists())
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach($result->regions as $region)
                                    {{-- Show the first 2 regions as clickable tags --}}
                                    @if($loop->iteration <= 1)
                                        <a href="{{ route('region:show', $region) }}" class="word-card__tag d-inline-flex align-items-center text-decoration-none transition-all">
                                            <x-heroicon-o-map-pin class="icon me-1" aria-hidden="true"/> {{ $region->name }}
                                        </a>
                                    @endif

                                    {{-- If there are more than 2, show the count badge and stop the loop --}}
                                    @if($loop->iteration > 1)
                                    @php
                                            $remainingNames = $result->regions->slice(2)->pluck('name')->implode(', ');
                                        @endphp
                                        <span class="badge border-light-subtle rounded-pill bg-light text-dark border ms-1 px-2.5 py-1.5"
                                            title="{{ $remainingNames }}"
                                            style="font-weight: 500; cursor: help; font-size: 0.8rem;">
                                            +{{ $loop->remaining + 1 }} regio's
                                        </span>
                                        @break
                                    @endif
                                @endforeach
                                </div>
                            @endif

                            <a href="{{ route('word-information.show', $result) }}" class="word-card__title color-green">
                                {{ $result->word }}
                                <span class="word-card__pos">{{ strtolower($result->characteristics) }}</span>
                            </a>

                            <div class="word-card__desc flex-grow-1">
                                {!! str($result->description)->words(22)->markdown()->sanitizeHtml() !!}
                            </div>

                            <div class="word-card__footer mt-auto">
                                @if ($result->author)
                                    <span class="word-card__meta">
                                        Door
                                        <strong>
                                            @if ($result->author()->exists())
                                                {{ $result->author->name ?? $result->contributor_name ?? config('app.name') }}
                                            @else
                                                {{ $result->contributor_name ?? config('app.name') }}
                                            @endif
                                        </strong>
                                        <span class="word-card__sep">·</span>
                                        {{ __('Weergaves: :count', ['count' => $result->views]) }}
                                    </span>
                                @else
                                    <span></span>
                                @endif

                                <a href="{{ route('word-information.show', $result) }}" class="word-card__link">
                                    Lees meer <x-heroicon-o-arrow-right class="icon" aria-hidden="true"/>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
