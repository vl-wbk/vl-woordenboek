@forelse($results as $result)
    <div class="lexi-card {{ $result->isArchived() ? 'border-danger-subtle' : '' }}">
   @if ($result->regions()->exists())
    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach($result->regions as $region)
            <span class="lexi-tag-enhanced">
                <x-heroicon-o-map-pin class="icon me-1"/> {{ $region->name }}
            </span>
        @endforeach
    </div>
@endif

    <div class="content-body">
        <a href="{{ route('word-information.show', $result) }}" class="text-decoration-none">
            <h4 class="word-title mb-2">{{ $result->word }} <span class="word-type ms-2">{{ strtolower($result->characteristics) }}</span></h3>
        </a>

        <div class="text-secondary opacity-75 mb-2" style="font-weight: 400;">
            {!! str($result->description)->words(22)->markdown()->sanitizeHtml() !!}
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light-subtle">
        @if ($result->author)
            <span class="small text-muted">
                @if ($result->authtor()->exists())
                     Door <span class="text-dark fw-semibold">{{ $result->author->name ?? $result->contributor_name ?? config('app.name') }}</span>
                @else
                    Door <span class="text-dark fw-semibold">{{  $result->contributor_name ?? config('app.name') }}</span>
                @endif

                <span class="">•</span> {{  __('Weergaves: :count', ['count' => $result->views]) }}
            </span>
        @endif

        <div class="d-flex align-items-center gap-3">
            @auth
                @if ($result->bookmarkers->contains(auth()->user()))
                    <a href="{{ route('bookmark:remove', $result) }}" class="text-danger text-decoration-none p-2 hover-bg-light rounded-circle">
                        <x-heroicon-s-bookmark class="icon"/> Vergeet dit woord
                    </a>
                @else
                    <a href="{{ route('bookmark:create', $result) }}" class="text-dark text-decoration-none p-2 hover-bg-light rounded-circle">
                        <x-heroicon-o-bookmark class="icon"/> Bewaar
                    </a>
                @endif
            @endauth
           

            <a href="{{ route('word-information.show', $result) }}" 
               class="btn btn-sm rounded-pill btn-outline-dark fw-bold btn-sm shadow-sm">
                Ontdek <x-heroicon-o-arrow-right class="icon-sm ms-1"/>
            </a>
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
                        Deze term is nog niet toegevoegd aan het Vlaams Woordenboek. Als een door de gemeenschap gedreven platform zijn we afhankelijk van bijdragers/lezers zoals jij.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('definitions.create', ['woord' => request()->get('zoekterm')]) }}" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <x-heroicon-o-document-plus class="icon me-2"/> Dien het in als suggestie
                        </a>

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
                            <i class="bi bi-shuffle me-2 text-primary"></i> Ontdek eens een vlaams woord
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
