@forelse($results as $result)
    <div class="card bg-white p-3 mb-2 shadow-sm border-start">
        <div class="d-flex align-items-start gap-4">

            <!-- Main Content -->
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h5 class="fw-bold color-green m-0 mb-1">{{ $result->word }}</h5>

                    @if ($result->isArchived())
                        <span class="badge bg-danger-subtle text-danger rounded-pill" style="font-size: 0.6rem;">
                            <x-heroicon-s-archive-box class="icon-sm me-1"/>gearchiveerd
                        </span>
                    @endif

                    @if ($wordOfTheday && $result->is($wordOfTheDay->article))
                        <span class="badge bg-dark-subtle text-dark rounded-pill" style="font-size: 0.6rem;">
                        <x-heroicon-s-sparkles class="icon-sm me-1"/>woord van de dag
                    </span>
                    @endif
                </div>

                <!-- Regions -->
                @if($result->regions->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach($result->regions as $region)
                            <span class="d-inline-flex align-items-center text-primary small fw-semibold">
                                <x-heroicon-o-map-pin style="width: 14px; height: 14px;" class="me-1"/>
                                {{ $region->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="text-secondary mb-3" style="font-size: .85rem;">
                    {!! str($result->description)->words(25)->markdown()->sanitizeHtml() !!}
                </div>

                <!-- Contextual Footer -->
                <div class="small text-muted d-flex align-items-center flex-wrap gap-2">
                    <span class="badge bg-success-subtle text-success border">Door {{ $result->author->name ?? $result->contributor_name ?? config('app.name') }}</span>
                    <span>•</span>
                    <span>{{ __('Weergaves: :count', ['count' => $result->views]) }}</span>
                </div>
            </div>

            <!-- Action Column -->
            <div class="d-flex flex-column align-items-center gap-2 flex-shrink-0">
                <a href="{{ route('word-information.show', $result) }}" class="btn btn-dark btn-sm w-100">
                    <x-heroicon-o-eye class="icon-sm me-1"/>Ontdek
                </a>

                <div class="d-flex align-items-center gap-2 text-muted mt-1">
                    <span>
                        <x-heroicon-c-hand-thumb-up class="icon me-1 text-success"/> {{ $result->totalUpvotes() }}
                    </span>

                    <span class="vr mx-1"></span>

                    <button type="button" onclick="shareWord('{{ $result->word }}', '{{ route('word-information.show', $result) }}')" class="btn btn-link text-decoration-none text-muted p-0 border-0 d-flex align-items-center">
                        <x-heroicon-o-share class="icon-sm me-2"/> Delen
                    </button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card bg-sidenav border-0 shadow-sm overflow-hidden">
    <div class="card-body p-4 p-md-5">
        <div class="row g-5">

            <div class="col-lg-7">
                <div class="mb-4 text-start">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill small fw-bold text-uppercase">Niet gevonden</span>
                    <h2 class="h2 fw-bold mb-3">"{{ request()->get('zoekterm', 'Antwerpse koffie') }}"</h2>
                    <p class="text-muted lead fs-6 mb-4">
                        @if (request()->has('filter.published_after'))
                            Er zijn sinds <strong>{{ \Carbon\Carbon::parse(request('filter.published_after'))->format('d/m/Y') }}</strong> geen nieuwe termen toegevoegd die aan je criteria voldoen.
                        @else
                            Deze term is nog niet toegevoegd aan het Vlaams Woordenboek. Als een door de gemeenschap gedreven platform zijn we afhankelijk van bijdragers/lezers zoals jij.
                        @endif
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @if (request()->has('filter.published_after'))
                            <a class="btn btn-primary px-4 py-2 fw-bold shadow-sm" href="{{ request()->fullUrlWithQuery(['filter' => array_merge(request('filter', []), ['published_after' => null])]) }}" class="btn-remove">
                                <x-heroicon-o-arrow-uturn-left class="icon me-2"/> Verwijder publicatie filter
                            </a>
                        @else
                            <a href="{{ route('definitions.create', ['woord' => request()->get('zoekterm')]) }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                <x-heroicon-o-document-plus class="icon me-2"/> Dien het in als suggestie
                            </a>
                        @endif

                        @if (app(\App\Settings\VolunteerSettings::class)->pageActive)
                            <a href="{{ route('support.volunteers') }}" class="btn btn-outline-dark px-4 py-2 fw-bold shadow-sm">
                                <x-heroicon-s-user-plus class="icon me-2"/> Zin om vrijwilliger te worden?
                            </a>
                        @endif
                    </div>
                </div>

                <section class="border-top pt-4 mt-5">
                    <h6 class="text-uppercase small fw-bold text-muted mb-4 tracking-wider">
                        <x-heroicon-s-light-bulb class="icon me-2"/> Hoe vind je wat je zoekt?
                    </h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div>
                                    <h6 class="fw-bold mb-1 small text-dark">Zoekmethode</h6>
                                    <p class="small text-muted mb-0">Zet de filter op <strong>"Bevat"</strong> voor bredere resultaten als exact niet werkt.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <i class="bi bi-archive text-primary fs-5"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 small text-dark">Check het Archief</h6>
                                    <p class="small text-muted mb-0">Veel oudere of minder courante woorden staan in ons <strong>Archief</strong>.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div>
                                    <h6 class="fw-bold mb-1 small text-dark">Let op spelling</h6>
                                    <p class="small text-muted mb-0">Zoek op de <strong>stam</strong> van het woord of probeer een variant (bijv. 'ou' ipv 'au').</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div>
                                    <h6 class="fw-bold mb-1 small text-dark">Zoek in beschrijving</h6>
                                    <p class="small text-muted mb-0">Activeer de optie om ook in <strong>definities</strong> te zoeken naar termen.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <div class="p-4 bg-white rounded-4 border border-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-shuffle me-2 text-primary"></i> Ontdek eens een Vlaams woord
                        </h6>
                        <span class="badge bg-white text-muted border fw-normal">Willekeurig</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach ($randomArticles as $article)
                            <a href="{{ route('word-information.show', $article) }}" class="btn btn-white btn-sm border bg-white shadow-sm px-3 rounded-pill hover-lift cursor-pointer">
                                <x-heroicon-o-document-text class="icon me-1"/>{{ $article->word }}
                            </a>
                        @endforeach
                    </div>

                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 mt-2">
                        <p class="small text-dark mb-0">
                            <strong>Wist je dat?</strong> Veel Vlaamse termen verschillen per regio.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endforelse
