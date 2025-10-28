
@extends('layouts.application-blank', ['title' => 'Welkom'])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="px-5 py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">Opzoeking in het <span class="text-warning">Vlaams Woordenboek</span></h1>
                    <p class="col-12 fs-5 pb-3 border-bottom">
                        Op basis van de je zoekterm(en) hebben we {{ $results->total() }} matches gevonden.
                    </p>

                    <form class="col-md-7 mt-4" action="{{ route('search.results') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-lg-3">
                                <label for="searchPatternSelect" class="visually-hidden">Zoekpartoon selectie</label>
                                <select name="zoekpatroon" class="form-select bg-white shadow-sm" id="searchPatternSelect">
                                    @foreach ($searchPatterns as $searchPattern)
                                        <option value="{{ $searchPattern->value }}" @selected(old('zoekpatroon', request()->get('zoekpatroon')) === $searchPattern->value)>
                                            {{ $searchPattern->getLabel() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-7 col-sm-8">
                                <input type="text" class="form-control bg-white shadow-sm" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchterm">
                            </div>
                            <div class="col-lg-2 col-sm-4">
                                <button type="submit" class="btn shadow-sm w-100 btn-submit">
                                    <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                </button>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" name="uitgebreid" type="checkbox" id="checkChecked" value="1" @checked(request()->boolean('uitgebreid') === true) switch>
                                    <label class="form-check-label" for="checkChecked">
                                        Ik wens ook uitgebreid te zoeken in de beschrijving
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

        <div class="container-fluid">
            <div class="row my-4 pb-2">

                    <div class="col-12 mb-3">
                        <div class="float-end mb-3 d-sm-none d-md-block">
                            <span class="list-inline-item fw-bold text-muted">
                                Sorteren op:
                            </span>

                            <div class="btn-group shadow-sm" role="group" aria-label="filters">
                                <x-sortable-button field="alfabetisch" :current-sort="request('sort')">
                                    Alfabetische volgorde
                                </x-sortable-button>

                                <x-sortable-button field="publicatie" :current-sort="request('sort')">
                                    Publicatiedatum
                                </x-sortable-button>

                                @if (request('sort') === '-weergaves')
                                    <a href="{{ request()->fullUrlWithoutQuery(['sort']) }}" class="btn active btn-light">
                                        <x-tabler-sort-ascending-letters class="icon color-green me-1"/> Weergaves
                                    </a>
                                @else
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => '-weergaves']) }}" class="btn btn-light">
                                        <x-tabler-sort-descending-letters class="icon color-green me-1"/> Weergaves
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between">
                                <a href="{{ route('definitions.create') }}" class="btn btn-submit w-50 me-1">
                                    <x-heroicon-o-plus class="icon me-1"/> Suggestie indienen
                                </a>
                                <a href="{{ route('search.results', ['zoekterm' => $randomArticle->word]) }}" class="btn btn-outline-secondary w-50 ms-1">
                                    <x-heroicon-s-book-open class="icon me-1"/> Willekeurig woord
                                </a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title color-green mb-3">Gepubliceerd sinds</h5>

                                <x-filter-link field="published_after" value="{{ now()->subWeek  ()->format('Y-m-d') }}">
                                    Afgelopen week
                                </x-filter-link>
                                <x-filter-link field="published_after" value="{{ now()->subMonth()->format('Y-m-d') }}">
                                    Afgelopen maand
                                </x-filter-link>
                                <x-filter-link field="published_after" value="{{ now()->subYear()->format('Y-m-d') }}">
                                    Afgelopen jaar
                                </x-filter-link>

                                <hr>

                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <x-heroicon-o-pencil-square class="icon color-green me-2"/>

                                            <a href="{{ route('suggestions:index') }}" class="text-decoration-none text-muted">
                                                Mijn suggesties
                                            </a>
                                        </div>

                                        @auth
                                            <span class="badge text-bg-danger rounded-pill">
                                                {{ auth()->user()->suggestions->count() }}
                                            </span>
                                        @endauth
                                    </li>

                                    <li class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <x-heroicon-o-bookmark class="icon color-green me-2"/>

                                            <a href="{{ route('bookmarks:index') }}" class="text-decoration-none text-muted">
                                                Mijn bewaarde woorden
                                            </a>
                                        </div>

                                        @auth
                                            <span class="badge text-bg-danger rounded-pill">
                                                {{ auth()->user()->bookmarks->count() }}
                                            </span>
                                        @endauth
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card bg-callout-card mt-4 shadow-sm border-0 card-body mb-sm-4">
                            <h5 class="card-title fw-bold fst-italic">Helpende handen gezocht!</h5>

                            <p class="card-text mt-2">
                                Goesting om een handje toe te steken bij het Vlaams Woordenboek?
                                We zijn op zoek naar taalgevoelige vrijwilligers die de inhoud van deze site mee naar een hoger niveau willen tillen.
                            </p>

                            <p class="card-text">
                                <a href="{{ route('support.volunteers') }}" class="btn btn-white mt-3">
                                    Ik wil meer weten
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="col-9">
                        <div class="row g-4">
                            <div class="col-12">
                                @if (flash()->message)
                                    <div class="alert alert-info">
                                        {{ flash()->message }}
                                    </div>
                                @endif

                                @include('components.definitions.results', ['results' => $results])
                                @include('components.definitions.pagination', ['results' => $results])
                            </div>
                        </div>
                    </div>

                </div>
            </div>

@endsection
