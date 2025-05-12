<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Middleware(middleware: ['auth', 'forbid-banned-user'])]
final readonly class BookmarkController
{
    /**
     * @todo PLace the search query in its own separate function logic.
     */
    #[Get(uri: 'bookmarks', name: 'bookmarks:index')]
    public function index(Request $request): Renderable
    {
        $searchTerm = $request->get('zoekterm');
        $searchQuery = auth()->user()->bookmarks() // @phpstan-ignore-line (because lack of knowledge)
            ->where(function (Builder $query) use ($searchTerm) {
                $query->where('word', 'like', "%{$searchTerm}%")->orWhere('description', 'like', "%{$searchTerm}%");
            })->paginate();

        return view('definitions.bookmarks', data: [
            'results' => $searchQuery,
        ]);
    }

    #[Get(uri: 'bookmark/{article}', name: 'bookmark:create')]
    public function store(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->doesntContain($article)) { // @phpstan-ignore-line (because lack of knowledge)
            $request->user()->bookmarks()->attach($article);
        }

        return redirect()->action(DictionaryArticleController::class, $article);
    }

    #[Get(uri: 'unbookmark/{article}', name: 'bookmark:remove')]
    public function delete(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->contains($article)) { // @phpstan-ignore-line (because lack of knowledge)
            $request->user()->bookmarks()->detach($article);
        }

        return back();
    }
}
