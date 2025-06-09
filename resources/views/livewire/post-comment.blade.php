<li class="list-group-item d-flex">
    <div class="flex-shrink-0">
        <img class="rounded-circle avatar-img shadow-sm" src="{{ gravatar($comment->commentator->email) }}" alt="avatar van {{ $comment->commentator->name }}" />
    </div>

    <div class="ms-3 flex-grow-1">
        <div class="fw-bold">
            {{ $comment->commentator->name }}
        </div>

        <span>{{ $comment->comment }}</span>
        <livewire:article-comments.comment-interaction :comment=$comment />
    </div>
</li>


