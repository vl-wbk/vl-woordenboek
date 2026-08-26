<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Concerns\InteractsWithAuthenticatedUser;
use App\Models\Article;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;

final class ThemaListComponent extends Component
{
    use InteractsWithAuthenticatedUser;

    /**
     * @var Collection<int, int>
     */
    public Collection $userWordLists;

    public function __construct(public Article $word)
    {
        $this->userWordLists = auth()->check()
            ? $this->authenticatedUser()->wordLists()
                ->withExists(['words as contains_word' => function ($query) {
                    $query->where('articles.id', $this->word->id); // pas aan naar jouw pivot-kolomnaam
                }])
                ->get()
            : collect();
    }

    public function render(): View
    {
        return view('components.thema-list-component');
    }
}
