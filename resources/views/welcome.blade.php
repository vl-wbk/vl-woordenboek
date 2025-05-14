
@extends('layouts.application-blank', ['title' => 'Welkom'])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-start">
                    <h3 class="color-green">Opzoeken in het woordenboek</h3>
                </div>

                <x-articles.overview-toolbar/>
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
                                <div class="col-lg-10 col-sm-8">
                                    <input type="text" class="form-control" name="zoekterm" value="{{ request()->get('zoekterm') }}" placeholder="Zoekterm" aria-label="searchterm">
                                </div>
                                <div class="col-lg-2 col-sm-4">
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
            <div class="col-lg-4 col-sm-12">
                <div class="card bg-callout-card shadow-sm border-0 card-body mb-sm-4">
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

            <div class="col-lg-8 col-sm-12">
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
