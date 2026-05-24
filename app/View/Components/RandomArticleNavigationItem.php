<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class RandomArticleNavigationItem extends Component
{
    public function render(): View
    {
        return view('components.random-article-navigation-item', data: [
            'article' => Article::query()->whereNull('deleted_at') // Equality first
            ->where('published_at', '<=', now()) // Range second
            ->select('id')
            ->inRandomOrder()
            ->first(),
        ]);
    }
}
