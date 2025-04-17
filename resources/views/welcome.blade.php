
@extends('layouts.application-blank', ['title' => 'Welkom'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">Raadpleging woordenboek</h3>
                </div>

                <div class="float-end">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with filters and functionalities">
                        <div class="btn-group me-3 shadow-sm">
                            @auth
                                <a href="#" class="btn border-0 btn-light active" aria-current="page">
                                    <x-heroicon-o-magnifying-glass-circle class="icon color-green"/> opzoeking
                                </a>
                                <a href="#" class="btn border-0 btn-light">
                                    <x-heroicon-o-bookmark class="icon color-green"/> bewaarde woorden
                                </a>
                                <a href="#" class="btn border-0 btn-light">
                                    <x-heroicon-o-list-bullet class="icon color-green"/> mijn suggesties
                                </a>
                            @endauth
                        </div>

                        <div class="btn-group shadow-sm" role="group">
                            <a href="{{ route('definitions.create') }}" class="btn border-0 btn-submit">
                                <x-heroicon-s-document-plus class="icon"/> suggestie indienen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header color-green fw-bold bg-white">
                        Zoek een artikel in ons woordenboek met {{ $articleCount }} termen
                    </div>
                    <div class="card-body">
                        <form action="{{ route('search.results') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-10">
                                    <input type="text" class="form-control" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchterm">
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn w-100 btn-submit">
                                        <x-heroicon-o-magnifying-glass class="icon me-1"/> Zoeken
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container"> {{-- Results & sidebar --}}
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-callout-card shadow-sm border-0 card-body">
                    <h5 class="card-title fw-bold fst-italic">Helpende handen gezocht!</h5>
                    <h6 class="card-subtitle mb-2">Uw inzet, onze kracht</h6>

                    <p class="card-text">
                        Zin om je handen uit de mouwen te steken en mee te bouwen aan het Vlaams Woordenboek van morgen?
                        We zijn op zoek naar enthousiaste vrijwilligers die mee het verschil willen maken!
                    </p>

                    <p class="card-text">
                        <a href="{{ route('support.volunteers') }}" class="btn btn-white mt-3">
                            Ik wil meer weten
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-md-8">
                @includeWhen($termPresent, 'components.definitions.filters', ['results' => $results->total()])

                {{-- Blankslate for when the user starts using the application. --}}
                @includeWhen(! $termPresent, 'components.definitions.blankslates.new-visit')
                @includeWhen($termPresent && (request('zoekterm') === null || $results->total() === 0), 'components.definitions.blankslates.no-results')

                {{-- When Search term and results are present --}}
                @includeWhen($termPresent && $results->total() > 0 && request('zoekterm') !== null, 'components.definitions.results', ['results' => $results])

                {{-- Indien men vraagt waarom hier dat paginatie zinnetje wel vertroond word. Antwoord gewoon dat alleen god wist wat ik aan het doen was --}}
                @includeWhen($termPresent && (request('zoekterm') !== null || $results->total() === 0), 'components.definitions.pagination', ['results' => $results])
            </div>
        </div>
    </div> {{-- END results & sidebar --}}
@endsection
