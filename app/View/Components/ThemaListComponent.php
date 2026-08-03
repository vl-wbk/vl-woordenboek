<?php

namespace App\View\Components;

use App\Models\Article;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class ThemaListComponent extends Component
{
    public Collection $userWordLists;

    public function __construct(public Article $word)
    {
        $this->userWordLists = auth()->check()
            ? auth()->user()->wordLists()
                ->withExists(['words as contains_word' => function ($query) {
                    $query->where('articles.id', $this->word->id); // pas aan naar jouw pivot-kolomnaam
                }])
                ->get()
            : collect();
    }

    public function render()
    {
        return view('components.thema-list-component');
    }
}
