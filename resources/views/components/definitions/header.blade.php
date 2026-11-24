@props(['word'])

<header id="word-header" class="border-bottom mt-4 pb-2 mb-3">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-3">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted">
                    <x-heroicon-o-home class="icon me-1"/>
                    {{ config('app.name', 'Laravel') }}
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('search.results') }}" class="text-decoration-none text-muted">
                    <x-heroicon-o-magnifying-glass class="icon me-1"/>
                    Zoeken
                </a>
            </li>

            <li class="breadcrumb-item active fw-semibold" aria-current="page">
                {{ $word->word }}
            </li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2">
        <div class="d-flex flex-wrap align-items-baseline gap-2">
            <h1 class="display-5 color-green fw-bold lh-1 mb-0">{{ $word->word }}</h1>

            @if ($word->characteristics)
                <span class="fst-italic text-muted">{{ $word->characteristics }}</span>
            @endif
        </div>

        <div class="d-flex align-items-center flex-wrap gap-1">
            <span class="badge rounded-pill bg-white text-dark border shadow-sm fw-normal me-1">
                <x-heroicon-o-eye class="icon me-1"/>
                {{ toHumanReadableNumber($word->views) }}
            </span>

            <span class="badge rounded-pill text-bg-dark shadow-sm fw-normal font-monospace">
                <x-heroicon-o-hashtag class="icon me-1"/>{{ $word->id }}
            </span>
        </div>
    </div>

    <div id="word-metadata" class="d-flex flex-wrap align-items-center gap-2 mt-3">
        <span class="badge rounded-pill text-bg-success">
            <x-heroicon-o-language class="icon icon-sm me-1"/>{{ __('Redactie artikel') }}
        </span>

        <span class="vr"></span>

        <span class="small text-muted">
            <x-heroicon-o-map-pin class="icon me-1"/>
            Regio(s):
        </span>

        <div class="d-flex flex-wrap gap-1">
            @forelse ($word->regions as $region)
                <a href="{{ route('region:show', $region) }}"
                   class="badge rounded-pill bg-white border text-dark text-decoration-none">
                    {{ $region->name }}
                </a>
            @empty
                <span class="badge rounded-pill text-bg-light border text-dark">Gans Vlaanderen</span>
            @endforelse
        </div>
    </div>
</header>
