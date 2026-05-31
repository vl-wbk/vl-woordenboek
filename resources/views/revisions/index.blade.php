@extends('layouts.application-blank', ['title' => __('pages/revision-history.page-heading')])

@section('content')
    <div class="py-5">
        <div class="container-fluid">
            <header class="mb-4">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-decoration-none">
                                <x-heroicon-o-home class="icon me-1"/> {{ config('app.name', 'Laravel') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('word-information.show', $word) }}" class="text-decoration-none">
                                {{ $word->word }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('pages/revision-history.breadcrumb') }}
                        </li>
                    </ol>
                </nav>

                <h1 class="h3 fw-bold mb-1">Bewerkingsgeschiedenis</h1>
                <p class="text-muted mb-0">Artikel: <a href="{{ route('word-information.show', $word) }}" class="text-decoration-none">{{ $word->word }}</a></p>
            </header>

            <div class="row mt-3 g-4">
                <main class="col-lg-8">

                    {{-- Filter / tools balk --}}
                    {{-- Filter / tools balk --}}
                    <div class="card bg-white border-0 shadow-sm mb-3">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between gap-2">

                                {{-- Links: event filters + teller --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="small text-muted me-1">
                    <x-heroicon-o-clock class="icon me-1" style="width:13px;"/>
                    {{ $audits->total() }} revisies
                </span>

                                    <span class="vr"></span>

                                    <a href="?{{ http_build_query(array_merge(request()->except('event'), [])) }}"
                                       class="small text-decoration-none {{ !request('event') ? 'fw-bold text-dark' : 'text-muted' }}">Alle</a>
                                    <a href="?{{ http_build_query(array_merge(request()->except('event'), ['event' => 'updated'])) }}"
                                       class="small text-decoration-none {{ request('event') === 'updated' ? 'fw-bold text-dark' : 'text-muted' }}">Bewerkingen</a>
                                    <a href="?{{ http_build_query(array_merge(request()->except('event'), ['event' => 'created'])) }}"
                                       class="small text-decoration-none {{ request('event') === 'created' ? 'fw-bold text-dark' : 'text-muted' }}">Aanmaken</a>
                                </div>

                                {{-- Rechts: filter toggle + actieve filter badges --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap">

                                    {{-- Actieve filter badges --}}
                                    @if(request('user'))
                                        <span class="badge bg-primary-subtle text-primary fw-normal d-flex align-items-center gap-1">
                        <x-heroicon-o-user class="icon" style="width:11px;"/>
                        {{ request('user') }}
                        <a href="?{{ http_build_query(request()->except('user')) }}" class="text-primary ms-1">
                            <x-heroicon-o-x-mark class="icon" style="width:11px;"/>
                        </a>
                    </span>
                                    @endif

                                    @if(request('user_type'))
                                        <span class="badge bg-primary-subtle text-primary fw-normal d-flex align-items-center gap-1">
                        <x-heroicon-o-user-group class="icon" style="width:11px;"/>
                        {{ \App\UserTypes::from((int) request('user_type'))->getLabel() }}
                        <a href="?{{ http_build_query(request()->except('user_type')) }}" class="text-primary ms-1">
                            <x-heroicon-o-x-mark class="icon" style="width:11px;"/>
                        </a>
                    </span>
                                    @endif

                                    @if(request('from') || request('to'))
                                        <span class="badge bg-primary-subtle text-primary fw-normal d-flex align-items-center gap-1">
                        <x-heroicon-o-calendar class="icon" style="width:11px;"/>
                        {{ request('from') ?? '…' }} – {{ request('to') ?? '…' }}
                        <a href="?{{ http_build_query(request()->except(['from', 'to'])) }}" class="text-primary ms-1">
                            <x-heroicon-o-x-mark class="icon" style="width:11px;"/>
                        </a>
                    </span>
                                    @endif

                                    {{-- Toggle knop --}}
                                    @php $activeFilterCount = collect(['user', 'user_type', 'from', 'to'])->filter(fn($k) => request($k))->count(); @endphp
                                    <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#filter-panel"
                                            aria-expanded="{{ $activeFilterCount > 0 ? 'true' : 'false' }}">
                                        <x-heroicon-o-funnel class="icon" style="width:13px;"/>
                                        <span class="small">Filters</span>
                                        @if($activeFilterCount > 0)
                                            <span class="badge bg-primary rounded-pill" style="font-size:.6rem;">
                            {{ $activeFilterCount }}
                        </span>
                                        @endif
                                    </button>
                                </div>
                            </div>

                            {{-- Uitklapbaar filtervenster --}}
                            <div class="collapse {{ $activeFilterCount > 0 ? 'show' : '' }}" id="filter-panel">
                                <form method="GET" class="border-top mt-2 pt-3">
                                    <input type="hidden" name="event" value="{{ request('event') }}">

                                    <div class="row g-3 align-items-end">

                                        {{-- Gebruiker --}}
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted mb-1">
                                                <x-heroicon-o-user class="icon me-1" style="width:13px;"/>
                                                Gebruiker
                                            </label>
                                            <input type="text"
                                                   name="user"
                                                   class="form-control form-control-sm"
                                                   placeholder="Naam of e-mail…"
                                                   value="{{ request('user') }}">
                                        </div>

                                        {{-- Gebruikersgroep --}}
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted mb-1">
                                                <x-heroicon-o-user-group class="icon me-1" style="width:13px;"/>
                                                Gebruikersgroep
                                            </label>
                                            <select name="user_type" class="form-select form-select-sm">
                                                <option value="">Alle groepen</option>
                                                @foreach($userTypes as $type)
                                                    <option value="{{ $type->value }}" {{ request('user_type') === (string) $type->value ? 'selected' : '' }}>
                                                        {{ $type->getLabel() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Datum van --}}
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">
                                                <x-heroicon-o-calendar class="icon me-1" style="width:13px;"/>
                                                Van
                                            </label>
                                            <input type="date"
                                                   name="from"
                                                   class="form-control form-control-sm"
                                                   value="{{ request('from') }}">
                                        </div>

                                        {{-- Datum tot --}}
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted mb-1">
                                                <x-heroicon-o-calendar class="icon me-1" style="width:13px;"/>
                                                Tot
                                            </label>
                                            <input type="date"
                                                   name="to"
                                                   class="form-control form-control-sm"
                                                   value="{{ request('to') }}">
                                        </div>

                                        {{-- Acties --}}
                                        <div class="col-md-2 d-flex gap-2">
                                            <button type="submit" class="btn btn-sm btn-dark flex-fill">Toepassen</button>
                                            <a href="?" class="btn btn-sm btn-outline-secondary">
                                                <x-heroicon-o-x-mark class="icon" style="width:13px;"/>
                                            </a>
                                        </div>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <hr>

                    @if($audits->isEmpty())
                        <div class="card bg-white border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <x-heroicon-o-clock class="icon mb-3 text-muted" style="width:40px; height:40px;"/>
                                <h3 class="h6 fw-bold mb-1">Geen revisies gevonden</h3>

                                @if(request()->hasAny(['event', 'user', 'user_types', 'from', 'to']))
                                    <p class="text-muted small mb-3">Er zijn geen revisies die overeenkomen met de huidige filters.</p>
                                    <a href="?" class="btn btn-sm btn-outline-secondary">
                                        <x-heroicon-o-x-mark class="icon me-1" style="width:13px;"/>
                                        Filters wissen
                                    </a>
                                @else
                                    <p class="text-muted small mb-0">Dit artikel heeft nog geen bewerkingsgeschiedenis.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Revisies gegroepeerd per dag --}}
                    @foreach($audits->groupBy(fn($a) => $a->created_at->translatedFormat('d F Y')) as $date => $dayAudits)
                        <div class="card bg-white border-0 shadow-sm mb-3">
                            <div class="card-header bg-secondary-subtle border-0 py-2 px-3">
                                <span class="small fw-bold text-muted text-uppercase" style="letter-spacing:.05em;">
                                    <x-heroicon-o-calendar class="icon me-1"/>{{ $date }}
                                </span>
                            </div>

                            <div class="card-body p-0">
                                @foreach($dayAudits as $audit)
                                    @php
                                        $modifiedCount = count($audit->getModified());
                                        $isCurrentRevision = $loop->parent->first && $loop->first;
                                    @endphp

                                    <div class="d-flex align-items-start gap-3 px-3 py-2 @if(!$loop->last) border-bottom @endif @if($isCurrentRevision) bg-warning-subtle @endif">

                                        {{-- Event badge --}}
                                        <div style="width:80px;" class="flex-shrink-0 pt-1">
                                            <span class="badge w-100 bg-{{ $audit->event === 'created' ? 'success' : ($audit->event === 'deleted' ? 'danger' : 'primary') }}">
                                                {{ $audit->event }}
                                            </span>
                                        </div>

                                        {{-- Tijdstip --}}
                                        <div class="flex-shrink-0 pt-1" style="width:52px;">
                                            <a href="{{ route('change:information', $audit) }}" class="small font-monospace text-decoration-none text-dark fw-bold">
                                                {{ $audit->created_at->format('H:i') }}
                                            </a>
                                        </div>

                                        {{-- Gebruiker --}}
                                        <div class="flex-fill pt-1">
                                            <span class="small fw-bold">
                                                {{ optional($audit->user)->name ?? __('Anoniem') }}
                                            </span>

                                            @if($audit->user?->user_type)
                                                <span class="badge bg-secondary-subtle text-secondary fw-normal ms-1" style="font-size:.7rem;">
                                                    {{ optional($audit->user->user_type)->getLabel() }}
                                                </span>
                                            @endif

                                            {{-- Gewijzigde velden --}}
                                            @if($modifiedCount > 0)
                                                <div class="mt-1">
                                                    @php
                                                        $totalByteDiff = 0;
                                                        foreach ($audit->getModified() as $value) {
                                                            $totalByteDiff += mb_strlen(toAuditString($value['new'] ?? ''), '8bit') - mb_strlen(toAuditString($value['old'] ?? ''), '8bit');
                                                        }
                                                    @endphp

                                                    {{-- Samenvatting --}}
                                                    <span class="small text-muted">
            {{ $modifiedCount }} {{ Str::plural('veld', $modifiedCount) }} gewijzigd
            <span class="{{ $totalByteDiff > 0 ? 'text-success' : ($totalByteDiff < 0 ? 'text-danger' : 'text-secondary') }}">
                ({{ $totalByteDiff > 0 ? '+' : '' }}{{ $totalByteDiff }}&nbsp;B)
            </span>
        </span>

                                                    {{-- Uitklap --}}
                                                    <a class="small text-muted text-decoration-none ms-1"
                                                       data-bs-toggle="collapse"
                                                       href="#fields-{{ $audit->id }}"
                                                       role="button">
                                                        <x-heroicon-o-chevron-down class="icon" style="width:12px;"/>
                                                    </a>

                                                    <div class="collapse" id="fields-{{ $audit->id }}">
                                                        <div class="mt-1 d-flex flex-wrap gap-1">
                                                            @foreach($audit->getModified() as $field => $value)
                                                                @php
                                                                    $diff = mb_strlen(toAuditString($value['new'] ?? ''), '8bit') - mb_strlen(toAuditString($value['old'] ?? ''), '8bit');
                                                                @endphp
                                                                <span class="badge bg-secondary-subtle text-secondary fw-normal" style="font-size:.7rem;">
                        {{ __("pages/version-info.columns.$field") }}
                        <span class="{{ $diff > 0 ? 'text-success' : ($diff < 0 ? 'text-danger' : 'text-secondary') }}">
                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}&nbsp;B
                        </span>
                    </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Tags/reden --}}
                                            @if($audit->tags)
                                                <div class="small text-muted fst-italic mt-1">"{{ $audit->tags }}"</div>
                                            @endif
                                        </div>

                                        {{-- Acties --}}
                                        <div class="flex-shrink-0 d-flex gap-2 align-items-center pt-1">
                                            @if($isCurrentRevision)
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size:.7rem;">huidig</span>
                                            @endif

                                            <a href="{{ route('change:information', $audit) }}" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size:.75rem;">
                                                bekijk
                                            </a>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if ($audits->hasPages())
                        <hr>

                        {{-- Paginatie --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $audits->links() }}
                        </div>
                    @endif

                </main>

                {{-- Zijbalk --}}
                <aside class="col-lg-4">

                    {{-- Artikel info --}}
                    <div class="card border border-secondary-subtle shadow-sm mb-4">
                        <div class="card-header bg-secondary-subtle fw-bold text-dark">
                            Over dit artikel
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th class="ps-3 pt-3 text-muted">Artikel</th>
                                    <td class="pt-3 pe-3 text-end fw-bold">{{ $word->word }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Totaal revisies</th>
                                    <td class="pe-3 text-end">{{ $audits->total() }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Eerste revisie</th>
                                    <td class="pe-3 text-end">{{ $audits->last()?->created_at->translatedFormat('d M Y') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 pb-3 text-muted">Laatste bewerking</th>
                                    <td class="pb-3 pe-3 text-end">{{ $audits->first()?->created_at->diffForHumans() ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Sprong naar datum --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary-subtle border-0 fw-bold text-dark">
                            <x-heroicon-o-calendar class="icon me-1"/>
                            Sprong naar datum
                        </div>
                        <div class="card-body py-2 px-3">
                            <form method="GET" class="d-flex gap-2">
                                <input type="hidden" name="event" value="{{ request('event') }}">
                                <input type="hidden" name="user" value="{{ request('user') }}">
                                <input type="date"
                                       name="from"
                                       class="form-control form-control-sm"
                                       value="{{ request('from') }}">
                                <button type="submit" class="btn btn-sm btn-dark flex-shrink-0">Ga</button>
                            </form>
                        </div>
                    </div>

                    {{-- Activiteitsgrafiek --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary-subtle border-0 fw-bold text-dark">
                            <x-heroicon-o-chart-bar class="icon me-1"/>
                            Activiteit
                        </div>
                        <div class="card-body px-3 py-2">
                            <canvas id="activityChart" height="50"></canvas>
                        </div>
                    </div>

                    {{-- Top bijdragers --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary-subtle border-0 fw-bold text-dark">
                            <x-heroicon-o-users class="icon me-1"/>
                            Top bijdragers
                        </div>
                        <div class="card-body p-0">
                            @foreach($topContributors as $contribution)
                                @php $name = optional($contribution->user)->name ?? 'Anoniem'; @endphp
                                <div class="d-flex align-items-center gap-3 px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    {{-- Initialen avatar --}}
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:32px; height:32px; font-size:.75rem; font-weight:600;">
                                        {{ collect(explode(' ', $name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                                    </div>

                                    <div class="flex-fill">
                                        <div class="small fw-bold">{{ $name }}</div>
                                        @if($contribution->user?->user_type)
                                            <div class="small text-muted" style="font-size:.7rem;">
                                                {{ optional($contribution->user->user_type)->getLabel() }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-end flex-shrink-0">
                                        <span class="small fw-bold">{{ $contribution->count }}</span>
                                        <div class="small text-muted" style="font-size:.7rem;">revisies</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Meest gewijzigde velden --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-secondary-subtle border-0 fw-bold text-dark">
                            <x-heroicon-o-pencil-square class="icon me-1"/>
                            Meest gewijzigde velden
                        </div>
                        <div class="card-body p-0">
                            @php $maxCount = $topFields->first() ?? 1; @endphp
                            @foreach($topFields as $field => $count)
                                <div class="px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="small">{{ __("pages/version-info.columns.$field") }}</span>
                                        <span class="small text-muted">{{ $count }}×</span>
                                    </div>
                                    <div class="progress" style="height:4px;">
                                        <div class="progress-bar bg-primary"
                                             style="width:{{ round(($count / $maxCount) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </aside>

                {{-- Chart.js voor activiteitsgrafiek --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('activityChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($activityByDay->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('d M Y'))->values()) !!},
                datasets: [{
                    data: {!! json_encode($activityByDay->values()) !!},
                    fill: true,
                    tension: 0.3,
                    backgroundColor: 'rgba(13,110,253,.08)',
                    borderColor: 'rgba(13,110,253,.7)',
                    borderWidth: 1.5,
                    pointRadius: 2,
                    pointHoverRadius: 4,
                }]
            },
            options: {
                plugins: { legend: { display: false }, tooltip: { callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => item.raw + ' revisie' + (item.raw !== 1 ? 's' : ''),
                        }}},
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            maxTicksLimit: 6,
                            maxRotation: 0,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 10 } },
                        grid: { color: 'rgba(0,0,0,.05)' }
                    }
                }
            }
        });
    </script>
@endsection
