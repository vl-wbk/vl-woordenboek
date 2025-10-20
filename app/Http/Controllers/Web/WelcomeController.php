<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class WelcomeController
{
    #[Get(uri: '/', name: 'home')]
    public function index(): Renderable
    {
        $baseQuery = Article::published();

        return view('welcome', data: [
            'searchPatterns' => SearchPatterns::cases(),
            'randomArticle' => $baseQuery->inRandomOrder()->first(),
            'articleCount' => $baseQuery->count('id'),
        ]);
    }
}
