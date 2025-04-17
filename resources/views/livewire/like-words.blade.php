<div>
    @if (! auth()->user()->hasLiked($article))
        <button wire:click="likeArticle" class="btn border-0 btn-light position-relative">
            <x-heroicon-s-hand-thumb-up class="icon me-1 text-success"/> upvote
            <span class="badge rounded-pill bg-dark ms-1">{{ $upvotes }}</span>
        </button>
    @elseif (auth()->user()->hasLiked($article))
        <button wire:click="dislikeArticle" class="btn border-0 btn-light position-relative">
            <x-heroicon-s-hand-thumb-down class="icon me-1 text-danger"/> downvote
            <span class="badge rounded-pill bg-dark ms-1">{{ $upvotes }}</span>
        </button>
    @endif
</div>
