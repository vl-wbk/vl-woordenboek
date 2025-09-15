@extends ('layouts.application-blank', ['title' => $user->name, 'paddingContent' => 'pb-4 mb-5'])

@section('content')
    <div class="container-lg py-5">
        <div class="row">

            <div class="col-lg-4 col-md-5 mx-auto">
                <div class="text-center mb-4">
                    <div class="d-inline-flex justify-content-center bg-white  shadow-sm align-items-center text-muted rounded-circle mb-3">
                        <x-heroicon-s-user-circle class="icon icon-lg" style="width: 150px; height: 150px;"/>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small {{ $user->bio ? 'mb-1' : 'mb-4' }}">
                        <i class="fas fa-calendar-alt me-1"></i> Lid sinds {{ $user->created_at->format('d/m/Y') }}
                    </p>

                    @if ($user->bio)
                        <p class="text-muted fst-italic mb-4">"Lover of words and their origins."</p>
                    @endif

                    @if ($user->twitter || $user->bluesky || $user->website)
                        <div class="mb-4 d-flex flex-wrap justify-content-center gap-3">
                            @if ($user->twitter)
                                <a href="https://www.x.com/{{ $user->twitter }}" class="text-dark text-decoration-none">
                                    <x-tabler-brand-x class="icon-lg"/>
                                </a>
                            @endif

                            @if ($user->bluesky)
                                <a href="https://bsky.app/profile/{{ $user->bluesky }}" class="text-dark text-decoration-none">
                                    <x-tabler-brand-bluesky class="icon-lg"/>
                                </a>
                            @endif

                            @if ($user->website)
                                <a href="{{ $user->website }}" class="text-dark text-decoration-none">
                                    <x-tabler-world class="icon-lg"/>
                                </a>
                            @endif
                        </div>
                    @endif

                    @if (auth()->user()->is($user))
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <x-heroicon-o-cog-8-tooth class="icon"/> Instellingen
                            </button>

                            <button type="button" class="btn btn-outline-dark rounded-pill px-4">
                                <x-heroicon-o-globe-europe-africa class="icon"/> Openbaar profiel
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8 col-md-7">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom-0">
                        <h5 class="fw-bold color-green d-flex mb-0 justify-content-between align-items-center">
                            <span>Activiteit</span>

                            <button class="btn btn-link p-0 text-decoration-none text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#journeyDetails" aria-expanded="true" aria-controls="journeyDetails">
                                <x-heroicon-o-chevron-up class="icon"/>
                            </button>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-4 g-4 mb-3">
                            <div class="col">
                                <p class="mb-0 text-muted">Words Saved</p>
                                <p class="fw-bold mb-0 fs-4">42</p>
                            </div>
                            <div class="col">
                                <p class="mb-0 text-muted">Unique Searches</p>
                                <p class="fw-bold mb-0 fs-4">157 <small class="text-success fst-italic fs-6">+4 deze week</small></p>
                            </div>
                            <div class="col">
                                <p class="mb-0 text-muted">Unique Searches</p>
                                <p class="fw-bold mb-0 fs-4">157</p>
                            </div>
                            <div class="col">
                                <p class="mb-0 text-muted">Unique Searches</p>
                                <p class="fw-bold mb-0 fs-4">157</p>
                            </div>
                        </div>
                        <div class="collapse show" id="journeyDetails">
                            <hr class="my-3">
                            <p class="mb-1 text-muted small">Progress towards "Word Master" badge:</p>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="mt-2 mb-0 text-muted small">75% complete</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                            Badges & Achievements 🏆
                            <button class="btn btn-link p-0 text-decoration-none text-secondary" type="button" data-bs-toggle="modal" data-bs-target="#badgesModal">
                                <small>View All <i class="fas fa-external-link-alt"></i></small>
                            </button>
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                  <span class="badge rounded-pill text-bg-warning p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Earned for your very first word save!">
                    <i class="fas fa-star me-1"></i> First Save
                  </span>
                            <span class="badge rounded-pill text-bg-info p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Awarded for searching 25 words in a single day.">
                    <i class="fas fa-trophy me-1"></i> Quick Learner
                  </span>
                            <span class="badge rounded-pill text-bg-success p-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Earned by viewing the etymology of 10 different words.">
                    <i class="fas fa-medal me-1"></i> Etymology Expert
                  </span>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs border-bottom-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold active border-0 border-bottom border-primary border-3 rounded-0 bg-transparent text-dark" id="words-tab" data-bs-toggle="tab" data-bs-target="#savedWords" type="button" role="tab" aria-controls="savedWords" aria-selected="true">Saved Words</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold border-0 border-bottom border-light border-3 rounded-0 bg-transparent text-dark-emphasis" id="activity-tab" data-bs-toggle="tab" data-bs-target="#recentActivity" type="button" role="tab" aria-controls="recentActivity" aria-selected="false">Recent Activity</button>
                    </li>
                </ul>

                <div class="tab-content pt-4" id="myTabContent">
                    <div class="tab-pane fade show active" id="savedWords" role="tabpanel" aria-labelledby="words-tab">

                    </div>

                    <div class="tab-pane fade" id="recentActivity" role="tabpanel" aria-labelledby="activity-tab">
                        <div class="card bg-white p-3 mb-3">
                            <small class="text-muted">Just now</small>
                            <p class="mb-0">Searched for "**Conundrum**" and viewed its synonyms.</p>
                        </div>
                        <div class="card bg-white p-3 mb-3">
                            <small class="text-muted">3 hours ago</small>
                            <p class="mb-0">Saved "**Petrichor**" to your list of favorite words.</p>
                        </div>
                        <div class="card bg-white p-3">
                            <small class="text-muted">Yesterday</small>
                            <p class="mb-0">Searched for "**Garrulous**" and checked its etymology.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
