<div>
    @if (! auth()->user()->hasLiked($article))
        <button wire:click="likeArticle" class="btn btn-primary w-100 fw-bold border-0" style="background: #4338ca;">
            Stem op dit woord
        </button>
    @elseif (auth()->user()->hasLiked($article))
        <button wire:click="dislikeArticle"  class="btn btn-primary w-100 fw-bold border-0" style="background: #4338ca;">
           Mijn stem intrekken
        </button>
    @endif
</div>
