<li class="list-group-item d-flex">
    <div class="flex-shrink-0">
        <img class="rounded-circle avatar-img shadow-sm" src="{{ gravatar($comment->commentator->email) }}" alt="avatar van {{ $user->name }}" />
    </div>

    <div class="ms-3 flex-grow-1">
        <div class="fw-bold">
            {{ $comment->commentator->name }}
        </div>

        <span>{{ $comment->comment }}</span>

        @auth
            <ul class="list-inline pt-2 d-flex justify-content-between align-items-center">
                <div> {{-- Wrap like/dislike in a div --}}
                    <li class="list-inline-item">
                        <a href="" class="text-decoration-none text-success">
                            <x-heroicon-o-hand-thumb-up class="icon me-1"/> 0
                        </a>
                    </li>

                    <li class="list-inline-item">
                        <a href="" class="text-decoration-none text-danger">
                            <x-heroicon-o-hand-thumb-down class="icon me-1"/> 0
                        </a>
                    </li>
                </div>

                <li class="list-inline-item"> {{-- Removed float-end from here --}}
                    <a href="" class="text-danger text-decoration-none">
                        <x-heroicon-o-shield-exclamation class="icon"/> rapporteren
                    </a>

                    <span class="text-muted mx-1">|</span>

                    <a href="" class="text-danger text-decoration-none">
                        <x-heroicon-o-trash class="icon"/>
                    </a>
                </li>
            </ul>
        @endauth
    </div>
</li>


