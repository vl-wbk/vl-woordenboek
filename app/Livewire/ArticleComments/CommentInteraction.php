<?php

declare(strict_types=1);

namespace App\Livewire\ArticleComments;

use App\Attributes\Todo;
use App\Models\Comment;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;
use Exception;

class CommentInteraction extends Component
{
    public Comment $comment;

    public function mount(Comment $comment): void
    {
        $this->comment = $comment->load('commentator');
    }

    public function render(): Renderable
    {
        return view('livewire.article-comments.comment-interaction', data: [
            'likes' => $this->comment->likers()->count(),
        ]);
    }

    public function likeComment(): void
    {
        auth()->user()->like($this->comment);
    }

    /**
     * @throws Exception when the reaction cannot be unliked.
     */
    #[Todo('Provide a custom exception with the rescue helper for this function', author: 'Tjoosten')]
    public function unlikeComment(): void
    {
        auth()->user()->unlike($this->comment);
    }
}
