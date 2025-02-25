@extends('layouts.application-blank', ['title' => $user->name])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <x-account.profile-information-banner :user=$user/>
            </div>
        </div>

        <div class="row py-4">
            {{-- Side navigation --}}
            <div class="col-3">
                <div class="list-group shadow-sm">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" aria-current="true">
                        Suggesties
                        <span class="badge bg-green rounded-pill">0</span>
                    </a>
                </div>
            </div>
            {{-- END - Side navigation --}}

            <div class="col-9">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                            Geaccepteerde suggesties
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                            Suggesties in behandeling
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card bg-white border-0 shadow-sm">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">Volgnr.</th>
                                                <th scope="col">Woord</th>
                                                <th scope="col">Beschrijving</th>
                                                <th scope="col">Geaccepteerd op</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row"><code>#1</code></th>
                                                <td>Woord</td>
                                                <td>Wat is de betekenis van een woord?</td>
                                                <td>13/02/2025</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <nav aria-label="...">
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">1</span>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card bg-white border-0 py-3 shadow-sm">
                            <div class="card-body text-center">
                                <h3 class="text-gold">Geen suggesties gevonden.</h3>
                                <p class="card-text text-muted">Het lijkt erop dat er geen suggesties in behandeling zijn die door jouw zijn ingestuurd.</p>

                                <p class="card-text mb-0">
                                    <a href="{{ route('definitions.create') }}" class="btn shadow-sm btn-suggestion-submit">
                                        <x-heroicon-o-plus class="icon me-1"/> Suggestie insturen
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
