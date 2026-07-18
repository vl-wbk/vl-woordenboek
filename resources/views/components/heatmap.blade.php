@php
    $startDate = now()->subYear()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endDate   = now();
    $period    = \Carbon\CarbonPeriod::create($startDate, $endDate);

    $days = [];
    foreach ($period as $date) {
        $count  = $contributionData[$date->format('Y-m-d')] ?? 0;
        $days[] = ['date' => clone $date, 'count' => $count];
    }

    $max         = collect($days)->max('count') ?: 1;
    $total       = collect($days)->sum('count');
    $paddingDays = $startDate->dayOfWeekIso - 1;
    $colWeeks    = (int) ceil(($paddingDays + count($days)) / 7);

    $monthPositions = [];
    $lastMonth      = null;
    for ($w = 0; $w < $colWeeks; $w++) {
        for ($d = 0; $d < 7; $d++) {
            $idx = $w * 7 + $d - $paddingDays;
            if ($idx >= 0 && $idx < count($days)) {
                $m = $days[$idx]['date']->month;
                if ($m !== $lastMonth) {
                    $monthPositions[$w] = $days[$idx]['date']->locale(app()->getLocale())->isoFormat('MMM');
                    $lastMonth = $m;
                }
            }
        }
    }

    function activityLevel(int $count, int $max): int {
        if ($count === 0) return 0;
        $pct = $count / $max;
        return $pct > 0.75 ? 4 : ($pct > 0.5 ? 3 : ($pct > 0.25 ? 2 : 1));
    }
@endphp

<div class="card-shadcn border-0 shadow-sm mb-5 p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="h6 fw-bold mb-0">Bijdragen van het afgelopen jaar</h3>
        <div class="d-flex align-items-center gap-2" style="font-size: 12px; color: #6e7681;">
            Minder
            <div class="d-flex gap-1">
                @foreach ([0, 1, 2, 3, 4] as $lvl)
                    <div class="day-cell level-{{ $lvl }}"
                         style="width: 12px; height: 12px; cursor: default; flex-shrink: 0;"
                         aria-hidden="true"></div>
                @endforeach
            </div>
            Meer
        </div>
    </div>

    <div class="hm-root" style="--col-weeks: {{ $colWeeks }}">

        <div class="hm-month-row">
            <div class="hm-month-spacer"></div>
            <div class="hm-months">
                @for ($w = 0; $w < $colWeeks; $w++)
                    <span>{{ $monthPositions[$w] ?? '' }}</span>
                @endfor
            </div>
        </div>

        <div class="hm-content">
            <div class="hm-day-labels" id="hm-day-labels" aria-hidden="true">
                <span>Ma</span>
                <span>Di</span>
                <span>Wo</span>
                <span>Do</span>
                <span>Vr</span>
                <span>Za</span>
                <span>Zo</span>
            </div>

            <div class="hm-grid" id="contribution-grid" role="grid" aria-label="Bijdragen heatmap">
                @for ($i = 0; $i < $paddingDays; $i++)
                    <div class="day-cell level-0" aria-hidden="true"></div>
                @endfor

                @foreach ($days as $day)
                    @php
                        $count = $day['count'];
                        $level = activityLevel($count, $max);
                        $label = $count === 1
                            ? "1 bijdrage op {$day['date']->translatedFormat('d M Y')}"
                            : "{$count} bijdragen op {$day['date']->translatedFormat('d M Y')}";
                    @endphp
                    <div class="day-cell level-{{ $level }}"
                         role="gridcell"
                         aria-label="{{ $label }}"

                         data-bs-toggle="tooltip"
                         data-bs-placement="top"
                         title="{{ $label }}"></div>
                @endforeach
            </div>
        </div>

    </div>

    <p class="mt-2 mb-0" style="font-size: 12px; color: #6e7681;">
        {{ number_format($total) }} bijdrage{{ $total !== 1 ? 'n' : '' }} in het afgelopen jaar
    </p>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Make grid square: derive cell size from column width, fix grid height
    function fixSquareCells() {
        const grid = document.getElementById('contribution-grid');
        if (!grid) return;

        // Width of one column = (grid width - gaps) / colWeeks
        const colWeeks = {{ $colWeeks }};
        const gap = 2;
        const gridWidth = grid.clientWidth;
        const cellSize = (gridWidth - gap * (colWeeks - 1)) / colWeeks;

        // Height = 7 cells + 6 gaps
        grid.style.height = (cellSize * 7 + gap * 6) + 'px';

        // Sync day label row heights
        const labels = document.querySelectorAll('#hm-day-labels span');
        labels.forEach(el => el.style.height = cellSize + 'px');
    }

    fixSquareCells();
    window.addEventListener('resize', fixSquareCells);

    // Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover focus' });
    });

    document.getElementById('contribution-grid')?.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const tip = bootstrap.Tooltip.getInstance(e.target);
            tip?.show();
            setTimeout(() => tip?.hide(), 2000);
        }
    });
});
</script>
