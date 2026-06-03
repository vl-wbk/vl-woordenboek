@extends('layouts.application-blank', ['title' => __('pages/statistics.page-title')])

@section('content')
    <div class="py-4 py-md-5">
        {{-- Header --}}
        <div class="container-fluid mb-4 mb-md-5">
            <h2 class="color-green fw-bold">Statistieken: status van ons {{ config('app.name', 'Laravel') }}</h2>
            <p class="text-muted lead">Wij houden onze groei graag transparant.</p>
        </div>

        {{-- 1. Wiki-Gezondheid Dashboard --}}
        <div class="container-fluid mb-4 mb-md-5">
            <div class="row g-3">
                <div class="col-12 col-lg-9">
                    <div class="card bg-white border-0 shadow-sm p-3 p-md-4 h-100">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3">
                            <h5 class="fw-bold color-green mb-2 mb-sm-0">Hoe is onze data verdeeld in het {{ config('app.name', 'Laravel') }}?</h5>
                            <span class="badge bg-success-subtle text-success fw-bold">{{ toHumanReadableNumber($articleCount) }} Artikelen</span>
                        </div>

                        <div class="prog-bar-track" id="prog-track">
                            @php $total = $articleCount ?: 1; @endphp
                            <div class="prog-segment" id="seg-published" data-pct="{{ ($publishedCount/$total)*100 }}" style="background:#1D9E75"></div>
                            <div class="prog-segment" id="seg-review"    data-pct="{{ ($reviewCount/$total)*100 }}"    style="background:#EF9F27"></div>
                            <div class="prog-segment" id="seg-draft"     data-pct="{{ ($draftCount/$total)*100 }}"     style="background:#888780"></div>
                            <div class="prog-segment" id="seg-archived"  data-pct="{{ ($archivedCount/$total)*100 }}"  style="background:#E24B4A"></div>
                            <div class="prog-segment" id="seg-external"  data-pct="{{ ($externalCount/$total)*100 }}"  style="background:#378ADD"></div>
                            <div class="prog-segment" id="seg-rejected"  data-pct="{{ ($rejectedCount/$total)*100 }}"  style="background:#5DCAA5"></div>
                            <div class="prog-segment" id="seg-new"       data-pct="{{ ($newCount/$total)*100 }}"       style="background:#2C2C2A"></div>
                        </div>

                        <div class="legend-grid mt-3" id="prog-legend">
                            @php
                                $statuses = [
                                    ['key'=>'published', 'label'=>'Publicaties',         'count'=>$publishedCount, 'color'=>'#1D9E75'],
                                    ['key'=>'review',    'label'=>'In beoordeling',      'count'=>$reviewCount,    'color'=>'#EF9F27'],
                                    ['key'=>'draft',     'label'=>'In behandeling',      'count'=>$draftCount,     'color'=>'#888780'],
                                    ['key'=>'archived',  'label'=>'Archief',             'count'=>$archivedCount,  'color'=>'#E24B4A'],
                                    ['key'=>'external',  'label'=>'Extern',              'count'=>$externalCount,  'color'=>'#378ADD'],
                                    ['key'=>'rejected',  'label'=>'Tijdelijk afgewezen', 'count'=>$rejectedCount,  'color'=>'#5DCAA5'],
                                    ['key'=>'new',       'label'=>'Suggesties',          'count'=>$newCount,       'color'=>'#2C2C2A'],
                                ];
                            @endphp
                            @foreach($statuses as $s)
                                <div class="legend-item" data-key="{{ $s['key'] }}">
                                    <span class="legend-dot" style="background:{{ $s['color'] }}"></span>
                                    <span class="legend-label">{{ $s['label'] }}</span>
                                    <span class="legend-pct">({{ round(($s['count']/$total)*100, 1) }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card border-0 shadow-sm p-4 h-100 bg-success text-white">
                        <h5 class="fw-bold mb-1">Onze doelstelling</h5>
                        <p class="opacity-75">We streven naar {{ toHumanReadableNumber($targetArticleCount) }} artikelen voor het einde van het jaar.</p>
                        <h3 class="fw-bold mt-3">{{ round(($articleCount / $targetArticleCount) * 100) }}% bereikt</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. KPI's --}}
        <div class="container-fluid mb-4 mb-md-5">
            <div class="metrics-grid">
                @foreach($metrics as $metric)
                    <div class="metric-card shadow-sm">
                        <div class="card-accent accent-{{ $metric['color'] }}"></div>
                        <div class="metric-top">
                            <div>
                                <p class="metric-label">{{ $metric['title'] }}</p>
                                <p class="metric-value">{{ $metric['value'] }}</p>
                            </div>
                            <div class="metric-icon icon-{{ $metric['color'] }}" aria-hidden="true">
                                <x-dynamic-component :component="'heroicon-s-'.$metric['icon']" style="width:20px;height:20px;" />
                            </div>
                        </div>
                        <div class="metric-footer">
                            <div class="footer-left">
                                <x-heroicon-o-chart-bar style="width:13px;height:13px;" />

                                @if ($metric['title'] === 'Nieuwe gebruikers')
                                    <span class="footer-text">Sinds deze week</span>
                                @else
                                    <span class="footer-text">Sinds registratie</span>
                                @endif
                            </div>
                            <x-heroicon-o-chevron-right style="width:13px;height:13px;" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 3. Grafieken --}}
        <div class="container-fluid mb-4 mb-md-5">
            <div class="row g-3">
                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <div class="card-header bg-white border-bottom px-4 pt-4 pb-3">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                <div>
                                    <h6 class="fw-semibold text-dark mb-1">
                                        <x-heroicon-o-signal class="icon color-green me-1"/> Artikelactiviteit
                                    </h6>
                                    <small class="text-muted">Suggestieve, gepubliceerde en gearchiveerde artikelen</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach([['#dc3545', 'Gearchiveerd', 0], ['#212529', 'Suggesties', 1], ['#198754', 'Publicaties', 2]] as [$color, $label, $index])
                                    <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="toggleDataset(this, {{ $index }})" style="cursor: pointer; transition: opacity 0.2s;">
                                        <span style="width:12px; height:12px; border-radius:2px; background:{{ $color }}; flex-shrink:0;"></span>
                                        <span class="small text-muted fw-medium">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-body bg-white px-4 pb-4 pt-3">
                            <canvas id="articleEdits" style="height:175px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <div class="card-header bg-white border-bottom px-4 pt-4 pb-3">
                            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                <div>
                                    <h6 class="fw-semibold text-dark mb-1">
                                        <x-heroicon-o-user-plus class="icon color-green me-1"/> Registratietrend
                                    </h6>
                                    <small class="text-muted">Overzicht van nieuwe gebruikers</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3">
                                {{-- Legend for the single bar chart dataset --}}
                                <div class="d-flex align-items-center gap-2" style="cursor: pointer; transition: opacity 0.2s;">
                                    <span style="width:12px; height:12px; border-radius:2px; background:#198754; flex-shrink:0;"></span>
                                    <span class="small text-muted fw-medium">Nieuwe Registraties</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white px-4 pb-4 pt-3">
                            <canvas id="myChart" style="height: 175px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Recentste Wiki-Bijdragen --}}
        <div class="container-fluid">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                {{-- Card Header --}}
                <div class="card-header bg-white border-bottom px-4 pt-4 pb-0">
                    <div class="d-flex color-green align-items-center justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                            <x-heroicon-o-queue-list class="text-success" style="width:16px;height:16px;" />
                            Recent in het {{ config('app.name', 'Laravel') }}
                        </h6>
                    </div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs border-0 gap-1" id="activityTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-muted fw-medium px-3 py-2" id="published-tab" data-bs-toggle="tab" data-bs-target="#pane-published" type="button" role="tab" aria-controls="pane-published" aria-selected="true">
                                Publicaties
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Tab Content --}}
                <div class="card-body p-0">
                    <div class="tab-content" id="activityTabContent">
                        {{-- Pane: Gepubliceerd --}}
                        <div class="tab-pane fade show active" id="pane-published" role="tabpanel" aria-labelledby="published-tab">
                            @if ($recentArticles->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light text-uppercase text-muted" style="font-size: 11px; letter-spacing: .05em;">
                                            <tr>
                                                <th class="ps-4">#</th>
                                                <th>Auteur</th>
                                                <th>Artikel</th>
                                                <th>Woordsoort</th>
                                                <th>Afbeelding</th>
                                                <th class="pe-4" colspan="2">Publicatie tijdstip</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentArticles as $change)
                                                <tr @can ('view', $change) onclick="window.location='{{ route('word-information.show', $change) }}'" @endcan>
                                                    <td class="ps-4 fw-semibold">
                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            #{{ $change->id }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $change->author->name ?? $change->contributor_name ?? 'Anonieme gebruiker' }}</td>
                                                    <td>{{ $change->word }}</td>
                                                    <td>{{ $change->partOfSpeech->name ?? '-' }}</td>

                                                    <td>
                                                        @if ($change->image_url)
                                                            <span class="badge bg-success-subtle text-success">
                                                                <x-heroicon-o-check class="icon icon-sm me-1"/> Afbeelding
                                                            </span>

                                                            @if ($change->image_alt)
                                                                <span class="badge bg-success-subtle text-success">
                                                                    <x-heroicon-o-check class="icon icon-sm me-1"/> Alt tekst
                                                                </span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger">
                                                                    <x-heroicon-o-x-mark class="icon icon-sm me-1"/> Alt tekst
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">
                                                                <x-heroicon-o-x-mark class="icon icon-sm me-1"/> Afbeelding
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $change->published_at->diffForHumans() }}</td>

                                                    <td class="pe-4">
                                                        <a href="{{ route('word-information.show', $change) }}" class="float-end">
                                                            Bekijk
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-center text-muted">
                                    <x-heroicon-o-book-open class="mb-2 opacity-25" style="width:36px;height:36px;" />
                                    <p class="fw-semibold text-dark mb-1 small">Geen gepubliceerde artikelen</p>
                                    <p class="mb-0" style="font-size: 14px;">Gepubliceerde artikelen verschijnen hier zodra ze live zijn.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const commonOptions = { responsive: true, maintainAspectRatio: false, scales: { x: { stacked: true }, y: { stacked: true } } };

        new Chart(document.getElementById('myChart'), {
            type: 'bar',
            data: { labels: @json($userRegistrations['labels']), datasets: [{ label: 'Nieuwe Registraties', data: @json($userRegistrations['data']), backgroundColor: '#198754' }] },
            options: {
                ...commonOptions,
                plugins: {
                    legend: { display: false } // Schakelt standaard legenda uit
                }
            }
        });

        const ctx = document.getElementById('articleEdits').getContext('2d');

        // Jouw specifieke Chart configuratie
        const articleChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($articleChart['labels']),
                datasets: [
                    { label: 'Gearchiveerd', data: @json($articleChart['archived']), backgroundColor: '#dc3545' },
                    { label: 'Suggesties', data: @json($articleChart['created']), backgroundColor: '#212529' },
                    { label: 'Publicaties', data: @json($articleChart['published']), backgroundColor: '#198754' }
                ]
            },
           options: {
               ...commonOptions,
               plugins: {
                   legend: { display: false } // Schakelt standaard legenda uit
               }
           }
       });

        // Functie voor de custom legenda die we in HTML hebben
        function toggleDataset(el, index) {
            const meta = articleChart.getDatasetMeta(index);

            // Toggle visibility
            meta.hidden = meta.hidden === null ? !articleChart.data.datasets[index].hidden : null;

            // Visuele feedback op de knop/legenda-item
            // el.style.opacity = meta.hidden ? '0.4' : '1';
            el.style.textDecoration = meta.hidden ? 'line-through' : 'none';

            articleChart.update();
        }

        const hidden = new Set();
        const items = document.querySelectorAll('#prog-legend .legend-item');
        const segs  = document.querySelectorAll('#prog-track .prog-segment');

        const pcts = {};
        segs.forEach(s => pcts[s.id.replace('seg-','')] = parseFloat(s.dataset.pct));

        function reflow() {
            const visKeys = [...Object.keys(pcts)].filter(k => !hidden.has(k));
            const sum = visKeys.reduce((a, k) => a + pcts[k], 0) || 1;

            segs.forEach(s => {
                const key = s.id.replace('seg-', '');
                s.style.borderRadius = '0';

                if (hidden.has(key)) {
                    s.classList.add('hidden');
                    s.style.flex = 0;
                } else {
                    s.classList.remove('hidden');
                    s.style.flex = pcts[key] / sum;
                }
            });

            // Re-apply rounded corners to the new first and last visible segments
            const visSegs = [...segs].filter(s => !hidden.has(s.id.replace('seg-', '')));
            if (visSegs.length === 1) {
                visSegs[0].style.borderRadius = '10px';
            } else if (visSegs.length > 1) {
                visSegs[0].style.borderRadius = '10px 0 0 10px';
                visSegs[visSegs.length - 1].style.borderRadius = '0 10px 10px 0';
            }
        }

        items.forEach(item => {
            item.addEventListener('click', () => {
                const key = item.dataset.key;
                if (hidden.has(key)) { hidden.delete(key); item.classList.remove('hidden'); }
                else if (hidden.size < items.length - 1) { hidden.add(key); item.classList.add('hidden'); }
                reflow();
            });
        });

        reflow();
    </script>
@endsection
