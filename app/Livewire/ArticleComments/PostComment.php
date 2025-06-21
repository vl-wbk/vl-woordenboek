<?php

namespace App\Livewire\ArticleComments;

use App\Models\Comment;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class PostComment extends Component
{
    public Comment $comment;

    public function mount(Comment $comment): void
    {
        $this->comment = $comment->load('commentator');
    }

    public function render(): Renderable
    {
        return view('livewire.post-comment', data: [
            //
        ]);
    }
}
