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

/**
 * Handles the management of user bookmarks for dictionary articles.
 *
 * This controller manages the bookmarking functionality, allowing authenticated users to save and organize their favorite dictionary entries.
 * Users can view their bookmarked articles, add new bookmarks, and remove existing ones.
 *
 * Security is enforced through multiple middleware:
 * - auth: Ensures only logged-in users can access bookmarking features.
 * - forbid-banned-user: Prevents banned users from using bookmarks.
 * - verified: Requires users to have verified their email address.
 *
 * @package App\Http\Controllers\Web\Articles
 */
#[Middleware(middleware: ['auth', 'forbid-banned-user', 'verified'])]
final readonly class BookmarkController
{
    /**
     * Displays a paginated list of the user's bookmarked articles.
     *
     * This page sho)ws all articles that a user has bookmarked, with optional filtering through a search term.
     * The search looks for matches in both the word and description fields of bookmarked articles.
     *
     * @param  Request $request The instance that contaions all the request information.
     * @return Renderable
     */
    #[Get(uri: 'bookmarks', name: 'bookmarks:index')]
    public function index(Request $request): Renderable
    {
        $searchTerm = $request->get('zoekterm');
        $searchQuery = auth()->user()->bookmarks()
            ->where(function (Builder $query) use ($searchTerm): void {
                $query->where('word', 'like', "%{$searchTerm}%")->orWhere('description', 'like', "%{$searchTerm}%");
            })->fastPaginate();

        return view('definitions.bookmarks', data: [
            'results' => $searchQuery,
        ]);
    }

    /**
     * Adds a new articles to the user's bookmarks.
     *
     * This method checks if the article article isn't already bookmarked before adding it to prevent duplicate bookmarks.
     * After bookmarking, the user is redirected back to the article's detail page.
     *
     * @param  Request $request The request instance that contains all the request information.
     * @param  Article $article The database entity that contains the Article information
     * @return RedirectResponse
     */
    #[Get(uri: 'bookmark/{article}', name: 'bookmark:create')]
    public function store(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->doesntContain($article)) { // @phpstan-ignore-line (because lack of knowledge)
            $request->user()->bookmarks()->attach($article);
        }

        return back();
    }

    /**
     * Removes an article from the user's bookmarks.
     *
     * The method verifies that the articles is actually in the user's bookmarks before attempting removal.
     * After removing the bookmark, the user is returned to their previous page.
     *
     * @param  Request $request  The equest instance that contains all the request information
     * @param  Article $article  The database entity that contains the Article information
     * @return RedirectResponse
     */
    #[Get(uri: 'unbookmark/{article}', name: 'bookmark:remove')]
    public function delete(Request $request, Article $article): RedirectResponse
    {
        if ($request->user()->bookmarks->contains($article)) { // @phpstan-ignore-line (because lack of knowledge)
            $request->user()->bookmarks()->detach($article);
        }

        return back();
    }
}
