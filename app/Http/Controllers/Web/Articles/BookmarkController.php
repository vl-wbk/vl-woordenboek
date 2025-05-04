<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Middleware(middleware: ['auth', 'forbid-banned-user'])]
final readonly class BookmarkController
{
    #[Get(uri: 'bookmark/{article}', name: 'bookmark:create')]
    public function store(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->doesntContain($article)) {
            $request->user()->bookmarks()->attach($article);
        }

        return redirect()->action(DictionaryArticleController::class, $article);
    }

    #[Get(uri: 'unbookmark/{article}', name: 'bookmark:remove')]
    public function delete(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->contains($article)) {
            $request->user()->bookmarks()->detach($article);
        }

        return redirect()->action(DictionaryArticleController::class, $article);
    }
}
