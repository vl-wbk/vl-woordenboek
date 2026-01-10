@extends('layouts.application-blank', ['title' => __('pages/search.page-title')])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid py-5">
    <form action="{{ route('search.results') }}" method="GET">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <span class="meta-label">Zoekresultaten voor</span>
          <h1 class="fw-bold mb-0">"{{ request()->get('zoekterm', '') }}"</h1>
          <p class="text-muted small mt-2">
            @if ($results->total() > 0)
                Toont {{ $results->firstItem() ?? 0 }} tot {{ $results->lastItem() ?? 0 }} van de {{ $results->total() }} resultaten 
            @else
            Geen resultaten gevonden
            @endif
          </p>
        </div>
        <div class="col-lg-5">
          <div class="compact-search-group">
            <select name="zoekpatroon" class="form-select rounded-end-0 search-filter-select">
              @foreach ($searchPatterns as $searchPattern)
                                        <option value="{{ $searchPattern->value }}" @selected(old('zoekpatroon', request()->get('zoekpatroon')) === $searchPattern->value)>
                                            {{ $searchPattern->getLabel() }}
                                        </option>
                                    @endforeach
            </select>
            <input type="text" name="zoekterm" placeholder="Uw zoekterm" class="compact-search-input" value="{{ request()->get('zoekterm') }}">
            <button type="submit" class="btn btn-dark rounded-start-0 px-3">
                <x-heroicon-o-magnifying-glass class="icon me-1"/> {{ __('components/search-form.buttons.submit') }}
            </button>
          </div>

          <div class="filter-chip-wrapper">
            <span class="filter-hint">Ook zoeken in:</span>
            <div class="chip-group">
              <input type="checkbox" name="archief" id="archive" value="1" @checked(request()->boolean('archief') === true)>
              <label for="archive" class="chip-label">Het archief</label>

              <input type="checkbox" name="uitgebreid" id="description" value="1" @checked(request()->boolean('uitgebreid') === true)>
              <label for="description" class="chip-label">de beschrijving</label>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
    </div>

    @if (flash()->message)
        <div class="alert alert-warning shadow-sm mb-0 border-0">
            <div class="px-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <x-heroicon-o-bell-alert class="icon me-1"/> {{ flash()->message }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row my-4 pb-2">

            {{--
                MAIN RESULTS COLUMN
                - Takes full width on mobile (col-12).
                - Takes 9/12 on medium screens (col-md-9).
                - Appears first on mobile, last on medium+ (order-md-last).
            --}}
            <div class="@if ($results->total() > 0) col-12 col-md-9 @else col-12 @endif order-md-last">
                <div class="row">

                    <div class="col-12">
                        <div class="row g-4">
                            <div class="col-12">
                                @include('components.definitions.results', ['results' => $results])
                                @include('components.definitions.pagination', ['results' => $results])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--
                SIDEBAR COLUMN
                - Takes full width on mobile (col-12).
                - Takes 3/12 on medium screens (col-md-3).
                - Appears last on mobile, first on medium+ (order-md-first).
            --}}

           <aside class="@if ($results->total() > 0) col-12 col-md-3 @else visually-hidden @endif border-md-first">
    <div class="sticky-top d-flex flex-column gap-3" style="top: 1.5rem; z-index: 900;">

        {{-- CARD 1: Filters & Personal Navigation --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                 @if ($results->total() > 0)
                    <div class="filter-group mb-4">
                        <span class="filter-title mb-3 d-block">Sorteren op</span>
                        <div class="d-flex flex-column gap-1">
                            <x-sortable-button field="alfabetisch" :current-sort="request('sort')">
                                {{ __('pages/search.sidenav.sort.alphabetical') }}
                            </x-sortable-button>

                            <x-sortable-button field="publicatie" :current-sort="request('sort')">
                                {{ __('pages/search.sidenav.sort.publication-date') }}
                            </x-sortable-button>

                            {{-- Handmatige check voor weergaves (omdat dit geen standaard toggle is in je huidige component) --}}
                            @php $isViews = request('sort') === '-weergaves'; @endphp
                            <a href="{{ $isViews ? request()->fullUrlWithoutQuery(['sort']) : request()->fullUrlWithQuery(['sort' => '-weergaves']) }}" 
                               class="filter-link {{ $isViews ? 'active' : '' }}">
                                @if($isViews)
                                    <x-tabler-sort-ascending-letters class="icon-sm me-2"/>
                                @endif
                                {{ __('pages/search.sidenav.sort.views') }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Date Filters --}}
                <div class="filter-group mb-4">
                    <span class="filter-title">{{ __('pages/search.sidenav.filters.heading') }}</span>

                    <x-filter-link field="published_after" value="{{ now()->subWeek()->format('Y-m-d') }}" class="filter-link">
                        {{ __('pages/search.sidenav.filters.last-week') }}
                    </x-filter-link>

                    <x-filter-link field="published_after" value="{{ now()->subMonth()->format('Y-m-d') }}" class="filter-link">
                        {{ __('pages/search.sidenav.filters.last-month') }}
                    </x-filter-link>

                    <x-filter-link field="published_after" value="{{ now()->subYear()->format('Y-m-d') }}" class="filter-link">
                        {{ __('pages/search.sidenav.filters.last-year') }}
                    </x-filter-link>
                </div>

                {{-- Personal Links --}}
                @auth
                <div class="filter-group mb-0">
                    <span class="filter-title-gradient mb-3 d-block">Bibliotheek</span>
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('suggestions:index') }}" class="filter-link d-flex justify-content-between align-items-center">
                            <span><x-heroicon-o-pencil-square class="icon me-2"/>{{ __('pages/search.sidenav.my-suggestions') }}</span>
                            @auth
                                <span class="badge rounded-pill bg-danger text-white border fw-normal">{{ auth()->user()->suggestions->count() }}</span>
                            @endauth
                        </a>

                        <a href="{{ route('bookmarks:index') }}" class="filter-link d-flex justify-content-between align-items-center">
                            <span><x-heroicon-o-bookmark class="icon me-2"/>{{ __('pages/search.sidenav.my-saved-words') }}</span>
                            @auth
                                <span class="badge rounded-pill bg-danger text-white border fw-normal">
                                    {{ auth()->user()->bookmarks->count() }}
                                </span>
                            @endauth
                        </a>
                    </div>
                </div>
                @endauth
            </div>
        </div>

        {{-- CARD 2: "Didn't find it?" (The Action Card) --}}
        <div class="card border-0 shadow-sm rounded-3 bg-light">
            <div class="card-body">
                <h6 class="fw-bold text-dark mb-2">
                    Niet gevonden wat je zocht?
                </h6>
                <p class="small text-muted mb-3">
                    Help ons het woordenboek uit te breiden of probeer iets willekeurigs.
                </p>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('definitions.create') }}" class="btn btn-primary btn-sm fw-medium">
                        <x-heroicon-o-plus class="icon me-1" style="width:16px;"/> 
                        {{ __('pages/search.sidenav.buttons.submit-suggestion') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- CARD 3: Volunteer Callout --}}
        @if (app(\App\Settings\VolunteerSettings::class)->pageActive)
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body position-relative">
                    {{-- Decorative background icon --}}
                    
                    <span class="filter-title mb-2 d-block text-primary">{{ __('components/volunteer-callout.heading') }}!</span>
                    
                    <p class="small text-muted mb-3 position-relative">
                        {{ __('components/volunteer-callout.description', ['applicationName' => config('app.name', 'Laravel')]) }}
                    </p>

                    <a href="{{ route('support.volunteers') }}" class="btn btn-outline-primary btn-sm w-100 stretched-link">
                        {{ __('components/volunteer-callout.action') }}
                    </a>
                </div>
            </div>
        @endif

    </div>
</aside>
    </div>
@endsection
