@extends('layouts.application-blank', ["title" => 'welkom'])

@section('jumbotron')
    <div class="bg-light bg-blend-hard-light rounded-3 shadow-sm">
        <div class="container-fluid">
            <div class="px-5 py-5">
                <div class="row">
                    <h1 class="display-6 fw-bold">Welkom op het <span class="text-warning">Vlaams Woordenboek</span></h1>

                    <div class="col-12">
                        <p class="border-bottom pb-3 fs-5">
                            {{ __('pages/welcome.jumbotron.leading-paragraph', ['articleCount' => $articleCount]) }}
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
                            <h5 class="card-title fw-bold color-green">{{ __('pages/welcome.call-outs.search.title') }}</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">{{ __('pages/welcome.call-outs.search.subtitle') }}</h6>

                            <p class="card-text mb-3">
                                {{ __('pages/welcome.call-outs.search.text') }}
                            </p>

                            <a href="{{ route('search.results', ['zoekterm' => $randomArticle->word]) }}" class="btn btn-sm btn-outline-dark mt-auto">
                                {{ __('pages/welcome.call-outs.search.actionText') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card h-100 bg-sidenav border-0 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">{{ __('pages/welcome.call-outs.suggestion.title') }}</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">{{ __('pages/welcome.call-outs.suggestion.subtitle') }}</h6>

                            <p class="card-text mb-3">
                                {{ __('pages/welcome.call-outs.suggestion.text') }}
                            </p>

                            <a href="{{ route('definitions.create') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                {{ __('pages/welcome.call-outs.suggestion.actionText') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-sidenav border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">{{ __('pages/welcome.call-outs.volunteer.title') }}</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">{{ __('pages/welcome.call-outs.volunteer.subtitle') }}</h6>

                            <p class="card-text mb-3">
                                {{ __('pages/welcome.call-outs.volunteer.text') }}
                            </p>

                            <a href="{{ route('support.volunteers') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                {{ __('pages/welcome.call-outs.volunteer.actionText') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-sidenav border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold color-green">{{ __('pages/welcome.call-outs.information.title') }}</h5>
                            <h6 class="card-subtitle mb-2 fst-italic text-body-secondary">{{ __('pages/welcome.call-outs.information.subtitle') }}</h6>

                            <p class="card-text mb-3">
                                {{ __('pages/welcome.call-outs.information.text') }}
                            </p>

                            <a href="{{ route('project-information') }}" class="btn btn-sm btn-outline-dark mt-auto">
                                {{ __('pages/welcome.call-outs.information.actionText') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
