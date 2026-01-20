<div>
    @auth
        <ul class="list-inline pt-2 d-flex justify-content-between align-items-center">
            <div> {{-- Wrap like/dislike in a div --}}
                @if (! auth()->user()->hasLiked($comment))
                    <li class="list-inline-item">
                        <a href="#" wire:click="likeComment" class="text-decoration-none text-success">
                            <x-heroicon-o-hand-thumb-up class="icon me-1" /> {{ $likes }}
                        </a>
                    </li>
                @elseif (auth()->user()->hasLiked($comment))
                    <li class="list-inline-item">
                        <a href="#" wire:click='unlikeComment' class="text-decoration-none text-danger">
                            <x-heroicon-o-hand-thumb-down class="icon me-1" />{{ trans_choice('{0} :likes personen vinden dit leuk|{1} :likes persoon vindt dit leuk|[2,*] :likes personen vinden dit leuk', $likes, ['likes' => $likes]) }}
                        </a>
                    </li>
                @endif
            </div>

            <li class="list-inline-item"> {{-- Removed float-end from here --}}
                @can('delete', $comment)
                    <a href="{{ route('comment:delete', $comment) }}" class="text-danger text-decoration-none">
                        <x-heroicon-o-trash class="icon" /> Verwijderen
                    </a>
                @endcan
            </li>
        </ul>
    @endauth
</div>
