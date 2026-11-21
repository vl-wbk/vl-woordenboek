@php
    $selectedIds = collect(explode(',', (string) request('regio', '')))
        ->map(fn ($id) => (int) $id)
        ->filter()
        ->unique()
        ->sort()
        ->values();

    // Builds the URL with a clean, sorted, comma-separated regio param.
    $buildUrl = function (array $ids): string {
        sort($ids);

        $url = request()->fullUrlWithQuery([
            'regio' => $ids === [] ? null : implode(',', $ids),
            'page'  => null,
        ]);

        return str_replace('%2C', ',', $url); // commas are valid in a query string
    };

    $regionUrl = function (int $id) use ($selectedIds, $buildUrl): string {
        $current = $selectedIds->all();

        return $buildUrl(
            in_array($id, $current, true)
                ? array_values(array_diff($current, [$id]))
                : [...$current, $id]
        );
    };

    $clearUrl = $buildUrl([]);
@endphp

@once
    <style>
        @property --fade-start { syntax: '<length>'; inherits: false; initial-value: 0px; }
        @property --fade-end   { syntax: '<length>'; inherits: false; initial-value: 2.5rem; }

        @keyframes region-fade {
            0%      { --fade-start: 0px;    --fade-end: 2.5rem; }
            5%, 95% { --fade-start: 2.5rem; --fade-end: 2.5rem; }
            100%    { --fade-start: 2.5rem; --fade-end: 0px; }
        }

        .region-scroll {
            --region-main: #0a4632;
            --region-main-rgb: 10, 70, 50;

            display: flex;
            gap: .3rem;
            overflow-x: auto;
            padding: .25rem 0; /* room for the focus outline, overflow would clip it */
            scrollbar-width: none;
            scroll-snap-type: x proximity;
            cursor: grab;

            -webkit-mask-image: linear-gradient(to right, transparent, #000 var(--fade-start), #000 calc(100% - var(--fade-end)), transparent);
                    mask-image: linear-gradient(to right, transparent, #000 var(--fade-start), #000 calc(100% - var(--fade-end)), transparent);

            animation: region-fade linear both;
            animation-timeline: scroll(self inline);
        }
        .region-scroll::-webkit-scrollbar { display: none; }

        .region-scroll.is-dragging {
            cursor: grabbing;
            scroll-snap-type: none;
            user-select: none;
        }
        .region-scroll.is-dragging .region-pill { pointer-events: none; }

        /* Pill */
        .region-pill {
            flex: 0 0 auto;
            scroll-snap-align: start;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .35rem .2rem .65rem;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 500;
            line-height: 1.1;
            white-space: nowrap;
            text-decoration: none;
            color: var(--region-main);
            background: transparent;
            border: 1px dashed rgba(var(--region-main-rgb), .45);
            transition: background-color .15s, border-color .15s, color .15s;
        }
        .region-pill:not(:has(.region-badge)) { padding-right: .65rem; }

        .region-pill:hover {
            color: var(--region-main);
            background: rgba(var(--region-main-rgb), .06);
            border-color: var(--region-main);
        }
        .region-pill:focus-visible {
            outline: 2px solid var(--region-main);
            outline-offset: 2px;
        }

        /* Active: filled, with a dashed ring inside to keep the dashed look */
        .region-pill.is-active {
            color: #fff;
            background: var(--region-main);
            border-color: var(--region-main);
            outline: 1px dashed rgba(255, 255, 255, .55);
            outline-offset: -3px;
        }
        .region-pill.is-active:hover {
            background: #0d5a41;
            border-color: #0d5a41;
        }
        .region-pill.is-active:focus-visible {
            outline: 2px solid var(--region-main);
            outline-offset: 2px;
        }

        .region-pill .icon { width: .85rem; height: .85rem; }

        .region-pill.is-empty { opacity: .55; }

        /* Badge */
        .region-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.15rem;
            height: 1.15rem;
            padding: 0 .3rem;
            border-radius: 999px;
            font-size: .68rem;
            font-weight: 600;
            line-height: 1;
            font-variant-numeric: tabular-nums;
            color: var(--region-main);
            background: rgba(var(--region-main-rgb), .1);
        }
        .region-pill.is-active .region-badge {
            color: var(--region-main);
            background: #fff;
        }
    </style>
@endonce

@if ($regions->isNotEmpty())
    <div class="card bg-white border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body py-2 px-3 d-flex align-items-center gap-3">

            <span class="filter-title mb-0 flex-shrink-0">Regio</span>

            <div class="region-scroll flex-grow-1" role="group" aria-label="Filter op regio">
                @foreach ($regions as $region)
                    @php $active = $selectedIds->contains($region->id); @endphp

                    <a href="{{ $regionUrl($region->id) }}" class="region-pill {{ $active ? 'is-active' : '' }} {{ ($region->results_count ?? 1) === 0 ? 'is-empty' : '' }}" aria-pressed="{{ $active ? 'true' : 'false' }}">
                        @if ($active)
                            <x-heroicon-s-check class="icon"/>
                        @endif

                        <span>{{ $region->name }}</span>


                       @isset($region->results_count)
                            <span class="region-badge">{{ $region->results_count }}</span>
                        @endisset
                    </a>
                @endforeach
            </div>

            @if ($selectedIds->isNotEmpty())
                <a href="{{ $clearUrl }}"
                   class="flex-shrink-0 small text-danger text-decoration-none d-inline-flex align-items-center gap-1">
                    <x-heroicon-o-x-mark class="icon"/>
                    Wissen ({{ $selectedIds->count() }})
                </a>
            @endif
        </div>
    </div>

    @if ($selectedIds->isNotEmpty())
        <script>
            (function () {
                var row = document.querySelector('.region-scroll');
                if (!row) return;

                var KEY = 'region-scroll';
                var saved = null;

                try {
                    saved = JSON.parse(sessionStorage.getItem(KEY) || 'null');
                    sessionStorage.removeItem(KEY);
                } catch (e) {}

                if (saved) {
                    var snap = row.style.scrollSnapType;
                    row.style.scrollSnapType = 'none'; // snapping would pull the row back to a pill

                    var apply = function () {
                        row.scrollLeft = saved.x;
                        window.scrollTo(0, saved.y);
                    };

                    apply();                                              // as early as possible
                    document.addEventListener('DOMContentLoaded', apply); // layout is complete
                    if (document.fonts && document.fonts.ready) {
                        document.fonts.ready.then(apply);                 // pills have their final width
                    }
                    window.addEventListener('load', function () {
                        apply();
                        row.style.scrollSnapType = snap;                  // re-enable snapping
                    });
                }

                // Save the position right before navigating via a pill or "Wissen".
                document.addEventListener('click', function (event) {
                    if (!event.target.closest('.region-pill, [data-region-clear]')) return;

                    try {
                        sessionStorage.setItem(KEY, JSON.stringify({
                            x: row.scrollLeft,
                            y: window.scrollY,
                        }));
                    } catch (e) {}
                });
            })();
        </script>
    @endif
@endif
