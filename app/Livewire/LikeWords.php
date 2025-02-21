<?php

namespace App\Livewire;

use App\Models\Word;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class LikeWords extends Component
{
    public Word $word;

    public function mount(Word $word): void
    {
        $this->word = $this->word;
    }

    public function likeWord(): void
    {
        auth()->user()->like($this->word);
    }

    public function dislikeWord(): void
    {
        auth()->user()->unlike($this->word);
    }

    public function render(): Renderable
    {
        return view('livewire.like-words', [
            'word' => $this->word,
            'upvotes' => $this->word->likers()->count()
        ]);
    }
}
