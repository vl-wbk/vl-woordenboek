<section class="mb-4">
    <h5 class="fw-semibold mb-3">
        <span class="color-green fw-semibold me-1">
            {{-- icon --}}
        </span>
        Mening van de gebruikers
    </h5>

    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        {{-- Upvote --}}
        <button
            type="button"
            wire:click="vote(1)"
            wire:loading.attr="disabled"
            @class([
                'btn shadow-sm',
                'btn-success' => $article->hasUpVoted(),
                'btn-outline-success' => ! $article->hasUpVoted(),
            ])
            @disabled($article->hasUpVoted())
        >
            <x-heroicon-s-hand-thumb-up class="icon me-1" />

            Plezant

            <span class="ms-1 fw-semibold">
                {{ $article->upVotesCount() }}
            </span>
        </button>

        {{-- Downvote --}}
        <button
            type="button"
            wire:click="vote(-1)"
            wire:loading.attr="disabled"
            @class([
                'btn shadow-sm',
                'btn-danger' => $article->hasDownVoted(),
                'btn-outline-danger' => ! $article->hasDownVoted(),
            ])
            @disabled($article->hasDownVoted())
        >
            <x-heroicon-s-hand-thumb-down class="icon me-1" />

            Stom

            <span class="ms-1 fw-semibold">
                {{ $article->downVotesCount() }}
            </span>
        </button>

        {{-- Reset --}}
        @if ($article->hasVoted())
            <button
                type="button"
                class="btn btn-outline-secondary shadow-sm"
                wire:click="removeVote"
                wire:loading.attr="disabled"
            >
                <x-heroicon-s-x-mark class="icon me-1" />

                Stem verwijderen
            </button>
        @endif
    </div>
</section>
