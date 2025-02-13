<x-layouts.application-blank title="{{ $user->name }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex border-bottom pb-3">
                    <img src="https://cdn.pixabay.com/photo/2016/11/18/23/38/child-1837375_960_720.png" alt="{{ $user->name }}" class="flex-shrink-0 img-rounded" style="width:60px;height:60px;">
                    <div class="ms-4 w-100">
                        <h4 class="text-gold">
                            {{ $user->name }}

                            <div class="float-end">
                                <a class="btn btn-white shadow-sm">
                                    <x-heroicon-o-question-mark-circle class="icon me-1"/> Help
                                </a>

                                <a class="btn btn-white shadow-sm">
                                    <x-heroicon-o-adjustments-horizontal class="icon me-1"/> Instellingen
                                </a>
                            </div>
                        </h4>

                        <ul class="inline-list text-muted mb-0 p-0">
                            <li class="list-inline-item">
                                <x-heroicon-o-users class="icon me-1"/>
                                <span class="fw-bold">Gebruikersgroep:</span>  {{ $user->user_type->value }}
                            </li>

                            <li class="list-inline-item">|</li>

                            <li class="list-inline-item">
                                <x-heroicon-o-clock class="icon me-1"/>
                                <span class="fw-bold">Actief sinds:</span>  {{ $user->created_at->format('d/m/Y') }}
                            </li>

                            <li class="list-inline-item">|</li>

                            <li class="list-inline-item">
                                <x-heroicon-o-clock class="icon me-1"/>
                                <span class="fw-bold">Laast gezien:</span> 11/11/2025
                            </li>

                            <li class="list-inline-item">|</li>

                            <li class="list-inline-item">
                                <x-heroicon-o-pencil-square class="icon me-1"/>
                                <span class="fw-bold">Bijdrages:</span> 0
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-4">
            <div class="col-3">
                <div class="list-group shadow-sm">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" aria-current="true">
                        Suggesties
                        <span class="badge bg-green rounded-pill">14</span>
                    </a>
                </div>
            </div>

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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.application-blank>
