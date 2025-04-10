<div>
    @auth
        <div class="float-start">
            @if (! auth()->user()->hasLiked($article))
                <button wire:click="likeArticle" class="btn btn-sm btn-outline-success">
                    <x-heroicon-s-hand-thumb-up class="icon me-1"/> Upvote
                </button>
            @elseif (auth()->user()->hasLiked($article))
                <button wire:click="dislikeArticle" class="btn btn-sm btn-outline-danger">
                    <x-heroicon-o-hand-thumb-down class="icon me-1"/> Downvote
                </button>
            @endif

            <span class="ms-2 text-dark">
                    Upvotes: <span class="fw-bold">{{ $upvotes }}</span>
                </li>
            </span>
        </div>
    @endauth

    <div class="float-end">
        <ul class="list-inline mb-0">
            <li class="list-inline-item fst-italic text-muted">
                Ingestuurd door: <span class="fw-bold">{{ $article->author->name ?? 'onbekende gebruiker' }}</span>
            </li>
        </ul>
    </div>
</div>
