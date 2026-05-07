<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CompareController 
{
    #[Get(uri: 'vergelijk/{word}', name: 'article:compare', middleware: ['auth', 'forbid-banned-user'])]
    public function show(Article $word, Request $request): Renderable
    {
        return view('definitions.compare', [
            'articleResource' => ArticleResource::class,
            'articleA'       => $word, 
            'articleB' => Article::findOrFail($request->input('second_word'))
        ]);
    }
}