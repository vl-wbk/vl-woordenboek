<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

final class ArticleInformationToolbar extends Component
{
    public function __construct(
        public readonly Article $word,
    ) {}

    public function render(): ?Renderable
    {
        if (auth()->check()) {
            return view('components.articles.article-information-toolbar', [
                'word' => $this->word,
            ]);
        }

        return null;
    }
}
