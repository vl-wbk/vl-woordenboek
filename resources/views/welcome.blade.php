
@extends('layouts.application', ['title' => 'Welkom'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header border-bottom-0">Zoeken</div>

                    <div class="card-body">
                        <form id="searchForm" method="GET" action="{{ route('search.results') }}">
                            <input type="text" class="form-control" name="zoekterm" id="exampleFormControlInput1" value="{{ request()->get('zoekterm') }}" placeholder="Uw zoekterm">
                        </form>
                    </div>

                    <div class="bg-white card-footer">
                        <button form="searchForm" class="btn btn-search">Zoeken</button>
                        <button form="searchForm" class="btn btn-link">reset</button>
                    </div>
                </div>

                <hr>

                @if (request()->has('zoekterm') && $results->total() > 0)
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header">Zoekresultaten voor: <strong>'{{ request()->get('zoekterm') }}'</strong></div>
                        <div class="list-group list-group-flush">
                            @foreach ($results as $result)
                                <a href="{{ route('word-information.show', $result) }}" class="list-group-item list-group-item-action" aria-current="true">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 color-green">{{ $result->word }}</h5>

                                        <small class="text-muted">
                                            {{ $result->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <p class="mb-2">{{ $result->description }}</p>

                                    @if ($result->author)
                                        <small class="text-muted">Ingestuurd door: <strong>{{ $result->author->name }}</strong></small>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if ($results->hasPages())
                        <div class="py-3 mt-3 border-top">
                            {{ $results->links() }}
                        </div>
                    @endif
                @else

                    <div class="alert alert-info alert-important shadow-sm" role="alert">
                        <h4 class="alert-heading">Geen woorden gevonden!</h4>

                        <p>
                            Het lijkt erop dat u geen zoekterm hebt opgegeven en/of er woorden zijn gevonden zijn met uw zoekterm.
                            Indien het woord dat u zoekt een vlaams woord is en niet is opgenomen in ons woordenboek. Kunt u via de knop hieronder
                            een suggestie voor het toevoegen van het woord in kwestie aan ons woordenboek.
                        </p>

                        <hr>

                        <a href="{{ route('definitions.create') }}" class="btn btn-info btn-sm text-white">
                            Suggestie toevoegen
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
