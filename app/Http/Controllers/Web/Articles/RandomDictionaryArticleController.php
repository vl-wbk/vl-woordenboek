<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class RandomDictionaryArticleController
{
    #[Get(uri: '/willekeurig-woordenboek-artikel', name: 'word-information.random')]
    public function __invoke(): Renderable
    {
        $article = Article::whereNotNull('published_at')->inRandomOrder()->first();
        $article->increment('views', 1);

        return view('definitions.show', data: [
            'word' => $article,
        ]);
    }
}
