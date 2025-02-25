
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
                        <div class="card-header text-dark bg-white">Zoekresultaten voor: <strong>'{{ request()->get('zoekterm') }}'</strong></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Woord</th>
                                            <th scope="col">Auteur</th>
                                            <th scope="col">Voorbeeld</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results as $result)
                                            <tr>
                                                <th scope="row" class="color-green">{{ $result->word }}</th>
                                                <td>{{ $result->author->name ?? '-' }}</td>
                                                <td class="text-muted">{{ $result->example }}</td>
                                                <td>
                                                    <a href="{{ route('word-information.show', $result) }}" class="float-end text-decoration-none">
                                                        <x-heroicon-o-eye class="icon me-1"/> Bekijken
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($results->hasPages())
                            <div class="card-footer bg-white">
                                <span class="float-start">{{ $results->links() }}</span>
                                <span class="float-end"></span>
                            </div>
                        @endif
                    </div>
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
