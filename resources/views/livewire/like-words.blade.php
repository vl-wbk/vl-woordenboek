<div class="card border-0 bg-dark text-white rounded-4 overflow-hidden mb-4 shadow-sm">
    <div class="p-4">
        <h6 class="small fw-bold text-uppercase opacity-50 mb-3">Hoe ligt dit woord in de community?</h6>

        <div class="stats-dashboard mb-3">
            <div class="stat-item">
                <span class="d-block fw-bold">{{ toHumanReadableNumber((int) $article->totalUpvotes()) }}</span>
                <span class="extra-small opacity-50" style="font-size: 0.7rem;">Upvotes</span>
            </div>
            <div class="stat-item">
                <span class="d-block fw-bold">{{ toHumanReadableNumber((int) $article->totalVotes()) }}</span>
                <span class="extra-small opacity-50" style="font-size: 0.7rem;">Rating</span>
            </div>
            <div class="stat-item">
                <span class="d-block fw-bold">{{ toHumanReadableNumber($article->bookmarkers()->count()) }}</span>
                <span class="extra-small opacity-50" style="font-size: 0.7rem;">Downvotes</span>
            </div>
        </div>

        @auth
            <div class="d-flex gap-2">
                <button wire:click="upvote"
                    class="btn w-50 fw-bold border-0 d-flex align-items-center justify-content-center gap-2"
                    style="background: {{ auth()->user()->hasUpvoted($article) ? '#22c55e' : '#334155' }}; color: white;">
                    <x-heroicon-s-hand-thumb-up class="icon"/> Upvote
                </button>

                <button wire:click="downvote"
                    class="btn w-50 fw-bold border-0 d-flex align-items-center justify-content-center gap-2"
                    style="background: {{ auth()->user()->hasDownvoted($article) ? '#ef4444' : '#334155' }}; color: white;">
                    <x-heroicon-s-hand-thumb-down class="icon"/> Downvote
                </button>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary w-100 fw-bold border-0" style="background: #4338ca;">
                Log in om te stemmen
            </a>
        @endauth
    </div>
</div>
