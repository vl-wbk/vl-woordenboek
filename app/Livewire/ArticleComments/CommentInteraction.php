<?php

declare(strict_types=1);

namespace App\Livewire\ArticleComments;

use App\Models\Comment;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class CommentInteraction extends Component
{
    public Comment $comment;

    public function mount(Comment $comment)
    {
        $this->comment = $comment->load('commentator');
    }

    public function render(): Renderable
    {
        return view('livewire.article-comments.comment-interaction');
    }
}
