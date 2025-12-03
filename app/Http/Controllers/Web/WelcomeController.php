<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use App\Queries\Articles\SelectRandomArticle;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class WelcomeController
{
    #[Get(uri: '/', name: 'home')]
    public function index(): Renderable
    {
        return view('welcome', data: [
            'searchPatterns' => SearchPatterns::cases(),
            'randomArticle' => SelectRandomArticle::fetch(),
            'articleCount' => Article::published()->count('id'),
        ]);
    }
}
