<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use App\Models\WordOfTheDay;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class WelcomeController
{
    #[Get(uri: '/', name: 'home')]
    public function index(): Renderable
    {
        return view('welcome', data: [
            'searchPatterns' => SearchPatterns::cases(),
            'trendingWords' => Article::published()->inRandomOrder()->limit(15)->get(),
            'wordOfTheDay' => WordOfTheDay::whereDate('scheduled_for', today())->first(),
            'recent' => Article::with('regions')->published()->orderBy('published_at', 'desc')->limit(3)->get(),
            'articleCount' => Article::published()->count('id'),
        ]);
    }
}
