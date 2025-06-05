<div>
    @auth
        <ul class="list-inline pt-2 d-flex justify-content-between align-items-center">
            <div> {{-- Wrap like/dislike in a div --}}
                <li class="list-inline-item">
                    <a href="" class="text-decoration-none text-success">
                        <x-heroicon-o-hand-thumb-up class="icon me-1" /> 0
                    </a>
                </li>

                <li class="list-inline-item">
                    <a href="" class="text-decoration-none text-danger">
                        <x-heroicon-o-hand-thumb-down class="icon me-1" /> 0
                    </a>
                </li>
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
