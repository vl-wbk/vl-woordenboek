<?php

namespace App\Livewire\ArticleComments;

use App\Models\Comment;
use Livewire\Component;

class PostComment extends Component
{
    public Comment $comment;

    public function mount(Comment $comment)
    {
        $this->comment = $comment->load('commentator');
    }

    public function render()
    {
        return view('livewire.post-comment', data: [
            //
        ]);
    }
}
