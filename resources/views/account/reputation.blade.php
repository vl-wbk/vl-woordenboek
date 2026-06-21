<x-public-profile :user="$user">
    {{-- Feedback section --}}
    @if ($displayFeedbackDialog)
        <div class="alert alert-info alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-start gap-2 mb-2">
                <x-heroicon-o-chat-bubble-left-right class="text-primary flex-shrink-0 mt-1" style="width: 18px;"/>
                <div class="flex-grow-1">
                    <span class="fw-semibold" style="font-size: 0.85rem;">Feedback of vragen?</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.5rem;"></button>
            </div>
            <p class="text-secondary mb-3 ps-4" style="font-size: 0.8rem; line-height: 1.4;">
                We willen gebruikers meer vertrouwen geven in het Vlaams Woordenboek. Daarom bouwen we een reputatiesysteem dat gebruikers op termijn meer machtigingen geeft. Jouw input is hiervoor essentieel!
            </p>
            <div class="d-flex gap-2 ps-4">
                <a href="{{ route('feedback:create') }}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.75rem;">
                    <x-tabler-message class="icon me-1"/> Feedback formulier
                </a>
                <a href="https://discord.gg/MzQDA4qWSM" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.75rem;">
                    <x-tabler-brand-discord class="icon me-1"/> Discord
                </a>
                <a href="https://github.com/vl-wbk/vl-woordenboek" target="_blank" class="btn btn-sm btn-dark" style="font-size: 0.75rem;">
                    <x-tabler-brand-github class="icon me-1"/> GitHub
                </a>
            </div>
        </div>
    @endif
    {{-- ### END feedback section ### --}}

    {{-- Reputation Overview --}}
    <div class="card-shadcn p-4">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 bg-primary bg-opacity-10 rounded shadow-sm text-primary">
                    <x-heroicon-s-queue-list style="width: 18px;"/>
                </div>
                <div class="ms-2">
                    <div class="fw-semibold" style="font-size: 0.9rem;">Reputatie</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $user->reputationLevel() }}</div>
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold h5 mb-0 text-primary">{{ number_format($user->reputation) }}</div>
                <div class="text-secondary" style="font-size: 0.72rem;">punten</div>
            </div>
        </div>

        {{-- Progress bar --}}
        @if (!$user->isExpert())
            <div class="mb-2">
                <div class="d-flex justify-content-between mb-1" style="font-size: 0.75rem; color: var(--muted-foreground);">
                    <span>Voortgang naar volgend niveau</span>
                    <span>{{ $user->reputationToNextLevel() }} punten nodig</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 99px; background-color: var(--muted);">
                    <div class="progress-bar bg-primary"
                        role="progressbar"
                        style="width: {{ $user->reputationProgress() }}%; border-radius: 99px;"
                        aria-valuenow="{{ $user->reputationProgress() }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
            </div>
        @else
            <div class="text-success" style="font-size: 0.78rem;">
                <x-heroicon-s-check-badge style="width: 14px;" class="me-1"/>
                Maximaal niveau bereikt
            </div>
        @endif

        {{-- Level badges --}}
        <div class="d-flex gap-2 mt-3 flex-wrap">
            @foreach($user->reputationThresholds() as $level)
                @php $reached = $user->reputation >= $level['threshold']; @endphp
                <span class="badge-chip {{ $reached ? 'text-primary border-primary' : 'text-secondary' }}"
                    style="{{ $reached ? 'background-color: #eff6ff; border-color: #bfdbfe;' : 'opacity: 0.5;' }}">
                    @if ($reached)
                        <x-heroicon-s-check-circle class="icon-xs me-1 text-primary"/>
                    @else
                        <x-heroicon-o-lock-closed class="icon-xs me-1"/>
                    @endif
                    {{ $level['label'] }}
                    <span class="ms-1" style="opacity: 0.6;">{{ $level['threshold'] }}+</span>
                </span>
            @endforeach
        </div>

        <hr class="my-3" style="border-color: var(--border);">

        {{-- Unlocked actions --}}
        <div class="sidenav-label ps-0 mb-2">Ontgrendelde acties</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($user->availableActions() as $action)
                    <span class="badge-chip text-success" style="background-color: #f0fdf4; border-color: #bbf7d0;">
                        <x-heroicon-s-check-circle class="icon-xs me-1 text-success"/>
                        {{ $action }}
                    </span>
                @endforeach
                @foreach ($user->unavailableActions() as $item)
                    <span class="badge-chip text-secondary" style="opacity: 0.6;" title="{{ $item['threshold'] - $user->reputation }} punten nodig">
                        <x-heroicon-o-lock-closed class="icon-xs me-1"/>
                        {{ str($item['action'])->title() }}
                        <span class="ms-1" style="font-size: 0.65rem;">({{ $item['threshold'] - $user->reputation }}pt)</span>
                    </span>
                @endforeach
            </div>

            <hr class="my-3" style="border-color: var(--border);">

            {{-- Tabs --}}
            <ul class="nav rep-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="rep-tab {{ request('tab', 'activiteit') === 'activiteit' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-activiteit"
                        type="button"
                        role="tab"
                        onclick="updateTabParam('activiteit')">
                        <x-heroicon-o-clock style="width: 13px;" class="me-1"/>
                        Activiteit
                        @if (!$reputationLogs->isEmpty())
                            <span class="rep-tab-count ms-1">{{ $reputationLogs->total() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="rep-tab {{ request('tab') === 'beroepen' ? 'active' : '' }}"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-beroepen"
                        type="button"
                        role="tab"
                        onclick="updateTabParam('beroepen')">
                        <x-heroicon-o-scale style="width: 13px;" class="me-1"/>
                        Beroepen
                        @if (!$appeals->isEmpty())
                            <span class="rep-tab-count ms-1">{{ $appeals->count() }}</span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content">

                {{-- Tab: Activiteit --}}
                <div class="tab-pane fade {{ request('tab', 'activiteit') === 'activiteit' ? 'show active' : '' }}" id="tab-activiteit" role="tabpanel">
                    @if ($reputationLogs->isEmpty())
                        <div class="card-shadcn p-4 text-center d-flex flex-column align-items-center justify-content-center"
                            style="background-color: #fafafa; border-style: dashed; border-color: #e4e4e7;">
                            <div class="p-3 bg-muted rounded-circle mb-3 text-secondary">
                                <x-heroicon-o-clock style="width: 24px; height: 24px; opacity: 0.5;"/>
                            </div>
                            <h6 class="fw-bold mb-1">Nog geen activiteit</h6>
                            <p class="text-secondary mb-0" style="font-size: 0.8rem;">
                                Zodra je bijdraagt aan het woordenboek, verschijnen hier je behaalde punten.
                            </p>
                        </div>
                    @else
                        <div class="list-group list-group-flush border mb-3 rounded overflow-hidden">
                            @foreach ($reputationLogs as $log)
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2" style="font-size: 0.8rem;">
                                    <div>
                                        <div class="fw-medium">{{ $log->reason }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            <x-heroicon-o-clock class="icon me-1" style="font-size: 0.7rem;"/>
                                            {{ $log->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span class="{{ $log->type === 'award' ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ $log->type === 'deduction' ? '-' : '+' }}{{ $log->points }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <div class="text-secondary" style="font-size: 0.8rem;">
                                <span class="fw-medium text-dark me-1">{{ $reputationLogs->total() }}</span> registraties
                                <span class="vr mx-2 opacity-50"></span>
                                <a href="" class="text-decoration-none text-primary fw-medium">Wat is reputatie?</a>
                            </div>
                            @if ($reputationLogs->hasPages())
                                <div>{{ $reputationLogs->appends(array_merge(request()->query()))->links('pagination::bootstrap-5') }}</div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Tab: Beroepen --}}
                <div class="tab-pane fade {{ request('tab') === 'beroepen' ? 'show active' : '' }}" id="tab-beroepen" role="tabpanel">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2 p-2 rounded flex-grow-1 me-3"
                            style="background-color: #fffbeb; border: 0.5px solid #fde68a; font-size: 0.75rem; color: #92400e;">
                            <x-heroicon-o-information-circle style="width: 13px; flex-shrink: 0;"/>
                            <span>Max. <strong>3 beroepen per maand</strong> &mdash; {{ $user->monthlyAppeals }}/3 gebruikt.</span>
                        </div>

                        @if ($user->can('create', \App\Models\Appeal::class))
                            <a href="{{ route('appeal:create') }}" class="btn btn-sm btn-primary flex-shrink-0" style="font-size: 0.75rem;">
                                <x-heroicon-o-plus style="width: 12px;"/> Nieuw beroep
                            </a>
                        @else
                            <span class="badge-chip text-secondary flex-shrink-0" style="opacity: 0.6; font-size: 0.7rem;">
                                <x-heroicon-o-lock-closed class="icon-xs me-1"/> Limiet bereikt
                            </span>
                        @endif
                    </div>

                    {{-- Filter --}}
                    <div class="d-flex gap-1 mb-3">
                        @php
                            $filters = [
                                null       => 'Alles',
                                'pending'  => 'In behandeling',
                                'approved' => 'Toegekend',
                                'rejected' => 'Afgewezen',
                            ];
                        @endphp

                        @foreach ($filters as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'beroepen', 'appeal_status' => $value, 'appeal_page' => null]) }}"
                                class="btn btn-sm {{ request('appeal_status', '') === (string) $value ? 'btn-primary' : 'btn-outline-secondary' }}"
                                style="font-size: 0.72rem;"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    @if ($appeals->isEmpty())
                        <div class="text-center text-secondary py-4" style="font-size: 0.78rem;">
                            <x-heroicon-o-scale style="width: 24px; opacity: 0.3; display: block; margin: 0 auto 6px;"/>
                            Geen beroepen gevonden.
                        </div>
                    @else
                        <div class="list-group list-group-flush border rounded overflow-hidden mb-3">
                            @foreach ($appeals as $appeal)
                                @php
                                    $statusMap = [
                                        'pending'  => ['label' => 'In behandeling', 'class' => 'text-warning', 'bg' => '#fffbeb', 'border' => '#fde68a'],
                                        'approved' => ['label' => 'Toegekend',      'class' => 'text-success', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
                                        'rejected' => ['label' => 'Afgewezen',      'class' => 'text-danger',  'bg' => '#fef2f2', 'border' => '#fecaca'],
                                    ];
                                    $s = $statusMap[$appeal->status] ?? $statusMap['pending'];
                                @endphp

                                <div class="list-group-item d-flex justify-content-between align-items-start py-2 gap-3" style="font-size: 0.78rem;">
                                    <div style="min-width: 0;">
                                        <div class="fw-medium text-truncate">{{ $appeal->reputationLog->reason }}</div>
                                        @if ($appeal->moderator_note)
                                            <div class="py-1 rounded" style="font-size: 0.7rem; color: #374151;">
                                                <span class="fw-semibold" style="color: #2563eb;">Eindbesluit:</span>
                                                {{ $appeal->moderator_note }}
                                            </div>
                                        @endif
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                            <x-heroicon-o-clock class="icon me-1"/>{{ $appeal->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <span class="badge-chip {{ $s['class'] }} flex-shrink-0"
                                        style="background-color: {{ $s['bg'] }}; border-color: {{ $s['border'] }}; font-size: 0.68rem;">
                                        {{ $s['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <div class="text-secondary" style="font-size: 0.8rem;">
                                <span class="fw-medium text-dark me-1">{{ $appeals->total() }}</span> beroepen
                            </div>

                            @if ($appeals->hasPages())
                                <div>{{ $appeals->appends(array_merge(request()->query(), ['tab' => 'beroepen']))->links('pagination::bootstrap-5') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @section('styles')
            <style>
                .rep-tabs {
                    border-bottom: 1px solid #e4e4e7;
                    gap: 0;
                }
                .rep-tab {
                    display: inline-flex;
                    align-items: center;
                    padding: 6px 12px;
                    font-size: 0.9rem;
                    font-weight: 500;
                    color: #71717a;
                    background: none;
                    border: none;
                    border-bottom: 2px solid transparent;
                    margin-bottom: -1px;
                    cursor: pointer;
                    transition: color 0.1s, border-color 0.1s;
                }
                .rep-tab:hover { color: #18181b; }
                .rep-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
                .rep-tab-count {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 0.65rem;
                    font-weight: 600;
                    padding: 1px 5px;
                    border-radius: 999px;
                    background-color: #e4e4e7;
                    color: #52525b;
                    line-height: 1.4;
                }
                .rep-tab.active .rep-tab-count {
                    background-color: #dbeafe;
                    color: #1d4ed8;
                }
            </style>
        @endsection

        @section('scripts')
            <script>
                function updateTabParam(tab) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);

                    // Clear tab-specific params when switching away
                    if (tab === 'activiteit') {
                        url.searchParams.delete('appeal_status');
                        url.searchParams.delete('appeal_page');
                    } else {
                        url.searchParams.delete('page');
                    }

                    window.history.replaceState({}, '', url);
                }
            </script>
        @endsection

</x-public-profile>
