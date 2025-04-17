
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
                            <a href="#" class="btn border-0 btn-light active" aria-current="page">
                                <x-heroicon-o-magnifying-glass-circle class="icon color-green"/> opzoeking
                            </a>
                            <a href="#" class="btn border-0 btn-light">
                                <x-heroicon-o-bookmark class="icon color-green"/> bewaarde woorden
                            </a>
                            <a href="#" class="btn border-0 btn-light">
                                <x-heroicon-o-list-bullet class="icon color-green"/> mijn suggesties
                            </a>
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
                        Artikel zoeken in het woordenboek
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row g-3">
                                <div class="col-10">
                                  <input type="text" class="form-control" placeholder="City" aria-label="City">
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
                <div class="row">
                    <div class="col-12">

                            <div class="float-start mb-2">
                                <span class="fw-bold">23</span> artikelen gevonden
                            </div>

                            <div class="float-end mb-2">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item fw-bold text-muted">Sorteer op:</li>

                                    <li class="list-inline-item">
                                        <a href="">Standaard</a>
                                    </li>

                                    <li class="list-inline-item text-muted">|</li>

                                    <li class="list-inline-item">
                                        <a href="">Publicatie</a>
                                    </li>

                                    <li class="list-inline-item text-muted">|</li>

                                    <li class="list-inline-item">
                                        <a href="">Weergaves</a>
                                    </li>
                                </ul>
                            </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title color-green">Woord</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">(het; o; meervoud: woorden)</h6>
                        <p class="card-text">groep van spraakklanken met een eigen betekenis; (bij uitbreiding) het gesprokene: de daad bij het woord voegen doen wat je hebt aangekondigd, beloofd; ...</p>

                        <p class="card-text fw-bold my-2">
                            Op basis van de suggestie ingestuurd door <span class="color-green">Jan met de pet</span>
                        </p>

                        <a href="{{ route('word-information.show', \App\Models\Article::first()) }}" class="card-link text-decoration-none">
                            <x-heroicon-o-eye class="icon color-green"/> bekijk
                        </a>

                        <a href="" class="card-link text-decoration-none">
                            <x-heroicon-o-bookmark class="icon color-green"/> bewaar
                        </a>
                    </div>
                </div>

                <hr>

                <div class="card border-0 bg-transparent">
                    <div class="card-body p-0">
                        <div class="float-start">
                            toont 1 tot 23 van de 1000 resultaten
                        </div>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Vorige</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Volgende</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- END results & sidebar --}}
@endsection
