@forelse($results as $result)
    <div class="card {{ ($result->wotd || $result->archived_at) ? 'border-danger-subtle' : 'border-0' }} @if (! $loop->last) mb-3 @endif shadow-sm">
        <div class="card-header bg-white">
            <a href="{{ route('word-information.show', $result) }}" class="h5 text-decoration-none card-title fw-bold color-green d-flex justify-content-between align-items-start flex-wrap">
                <span class="me-2 mb-1">{{ $result->word }}</span>

                @if ($result->wotd)
                    <span class="badge badge-xsm badge-danger ms-auto flex-shrink-0">
                        <x-heroicon-o-star class="icon me-1 icon-sm"/> woord van de dag
                    </span>
                @elseif ($result->isArchived())
                    <span class="badge badge-xsm badge-danger ms-auto flex-shrink-0">
                        <x-heroicon-o-archive-box class="icon me-1 icon-sm"/> Gearchiveerd artikel
                    </span>
                @else
                    {{ $result->archived_at }}
                    <small class="fw-normal ms-auto flex-shrink-0 text-nowrap">
                        <x-heroicon-o-eye class="icon me-1"/> {{ $result->views }}
                    </small>
                @endif
            </a>

            <h6 class="card-subtitle mb-0 text-body-secondary">{{ $result->characteristics }}</h6>
        </div>
        <div class="card-body bg-white">
            <p class="card-text"> {!! str($result->description)->words(25)->markdown()->sanitizeHtml() !!}</p>

            @if ($result->author)
                <p class="card-text fw-bold my-2">
                    Op basis van de suggestie ingestuurd door <span class="color-green">{{ $result->author->name }}</span>
                </p>
            @endif
        </div>
        <div class="card-footer bg-white d-flex flex-wrap">
            @if ($result->isPublished() || $result->isArchived())
                <a href="{{ route('word-information.show', $result) }}" class="card-link text-decoration-none me-2 mb-1">
                    <x-heroicon-o-eye class="icon color-green"/> bekijk
                </a>
            @endif

            @if (! $result->isArchived() && $result->bookmarkers->contains(auth()->user()))
                <a href="{{ route('bookmark:remove', $result) }}" class="card-link text-decoration-none me-2 mb-1">
                    <x-heroicon-o-bookmark-slash class="icon text-danger"/> vergeten
                </a>
            @elseif (! $result->isArchived()) {{-- The user hasnt bookmarked the article --}}
                <a href="{{ route('bookmark:create', $result) }}" class="card-link text-decoration-none me-2 mb-1">
                    <x-heroicon-o-bookmark class="icon color-green"/> bewaar
                </a>
            @endif
        </div>
    </div>
@empty
    <div class="card bg-sidenav border-0 shadow-sm text-center">
        <div class="card-body p-4">
            <x-heroicon-o-book-open class="icon color-green icon-blankslate pb-3"/>
            <h5 class="card-title fw-bold">Spijtig, niks gevonden</h5>

            <p class="card-text text-muted mb-2">
                We vonden geen enkel artikel dat bij jouw zoekterm past.
                Heb je een typfout gemaakt? Misschien is het wel een woord dat of woordcombinatie die (nog) niet in ons woordenboek zit.
                Probeer het eens met een andere zoekterm of een andere schrijfwijze.
            </p>

            <p class="card-text text-muted">
                Nog steeds niks? Je mag altijd een suggestie indienen. Dat kan met de knop links bovenaan.
            </p>
        </div>
    </div>
@endforelse
