@extends('layouts.application-blank', ["title" => 'welkom'])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="px-5 py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">Welkom op het <span class="text-warning">Vlaams Woordenboek</span></h1>

                    <div class="col-12">
                        <p class="border-bottom pb-3 fs-5">
                            Een community-driven woordenboek van {{ $articleCount }} artikelen.
                            Waar de maatschappelijke waarden van belang is. Met dank aan onze vele bijdragers.
                        </p>
                    </div>

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
    <div class="py-4">
        <div class="container-fluid">
            <div class="row my-4 pb-2">
                <div class="col-md-3">
                    <div class="card h-100 border-0 bg-sidenav shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">Snuisteren in het woordenboek?</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">Ontdek een nieuw woord.</h6>

                            <p class="card-text mb-3">
                                Zoek je geen specifiek woord? Of heb je geen inspiratie? Maar toch de goesting om te snuisteren?
                                Dan kun je ook kiezen voor een willekeurig woord.
                            </p>

                            <a href="{{ route('search.results', ['zoekterm' => $randomArticle->word]) }}" class="btn btn-sm btn-outline-dark mt-auto">
                                Start met snuisteren
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 bg-sidenav border-0 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">Weet van een leuk Vlaams woord?!</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">Dat verdiend een plaats in ons woordenboek.</h6>

                            <p class="card-text mb-3">
                                We proberen zoveel mogelijk woorden te verzamelen in ons Woordenboek.
                                Ook jouw woord verdiend een plaats? Wil je dat? Dan kun je een suggestie indienen.
                            </p>

                            <a href="{{ route('definitions.create') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                Suggestie indienen
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-sidenav border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">Versterk jij onze ploeg?</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">Word vrijwilliger, uw inzet onze kracht.</h6>

                            <p class="card-text mb-3">
                                We ontvangen suggesties van mensen en de code van het Vlaams Woordenboek is open-source.
                                Daarom zoeken we mensen die samen met onze ploeg het in leven willen houden.
                            </p>

                            <a href="{{ route('support.volunteers') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                Ja, ik heb interesse
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-sidenav border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">Wil je meer weten over dit project?</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">Onze missie en doelen uitgelegd.</h6>

                            <p class="card-text mb-3">
                                Omdat we ons willen focussen op duurzaamheid en de toekomst.
                                Vinden we het belangrijk om je daar duidelijk me de nodige transparantie over te informeren.
                            </p>

                            <a href="{{ route('project-information') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                Ja, ik wil meer weten.
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
