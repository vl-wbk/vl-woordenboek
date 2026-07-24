<section class="mb-4">
    <h5 class="fw-semibold mb-3">
        <span class="color-green fw-semibold me-1">//</span> Mening van de gebruikers
    </h5>

    <div class="d-flex flex-wrap gap-3 align-items-start mb-3">
        {{-- Upvote --}}
        <div class="text-center">
            <button
                wire:click="upvote"
                class="btn {{ $hasUpvoted ? 'btn-success' : 'btn-outline-success' }} shadow-sm"
            >
                <x-heroicon-s-hand-thumb-up class="icon me-1"/> Plezant
            </button>
            <div class="text-muted small mt-1">
                <span id="count-helpful">
                    {{ trans_choice('{1} :count stem|[2,*] :count stemmen', $upvotesCount) }}
                </span>
            </div>
        </div>

        {{-- Downvote --}}
        <div class="text-center">
            <button
                wire:click="downvote"
                class="btn {{ $hasDownvoted ? 'btn-danger' : 'btn-outline-danger' }} shadow-sm"
            >
                <x-heroicon-s-hand-thumb-down class="icon me-1"/> Stom
            </button>
            <div class="text-muted small mt-1">
                <span id="count-nothelpful">
                    {{ trans_choice('{1} :count stem|[2,*] :count stemmen', $downvotesCount) }}
                </span>
            </div>
        </div>
    </div>
</section>
