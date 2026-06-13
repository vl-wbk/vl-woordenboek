<x-public-profile :user="$user" x>
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
                <a href="{{  route('feedback:create') }}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.75rem;">
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

        {{-- Recent Activity --}}
        <div class="mt-4">
            <hr>
            <div class="sidenav-label ps-0 mb-2">Recente activiteit</div>

            @if ($user->reputationLogs->isEmpty())
                <div class="card-shadcn p-4 border-dashed text-center d-flex flex-column align-items-center justify-content-center"
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
                <div id="reputationHistory" class="list-group list-group-flush border mb-3 rounded overflow-hidden">
                    @foreach ($reputationLogs as $log)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2" style="font-size: 0.8rem;">
                            <div>
                                <div class="fw-medium">{{ $log->reason }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="{{ $log->points > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                            </span>
                        </div>
                    @endforeach
                </div>



    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
        {{-- Total count --}}
        <div class="text-secondary" style="font-size: 0.8rem;">
            <span class="fw-medium text-dark me-1">{{ $reputationLogs->total() }}</span> registraties
            <span class="vr mx-2 opacity-50"></span>
            <a href="" class="text-decoration-none text-primary fw-medium">
                Wat is reputatie?
            </a>
        </div>

        {{-- Pagination links --}}
        @if ($reputationLogs->hasPages())
        <div>
            {{ $reputationLogs->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
                </div>
            @endif

    </div>
</x-public-profile>
