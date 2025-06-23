<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Actions\Posts\StoreGuestArticle;
use App\Features\GuestEditors;
use App\Http\Requests\Posts\StoreGuestArticleRequest;
use App\Models\Article;
use App\Models\Blog;
use App\Models\Category;
use App\Queries\SearchArticlesQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo Document this controller
 */
final readonly class PostsController
{
    #[Get(uri: '/nieuws', name: 'news:index')]
    public function index(Request $request, SearchArticlesQuery $searchArticlesQuery): Renderable
    {
        abort_unless(Blog::count() > 0, Response::HTTP_NOT_FOUND);

        return view('blog.index', data: [
            'posts' => $searchArticlesQuery->execute($request),
            'categories' => Category::with('posts')->get(),
        ]);
    }

    #[Get(uri: '/nieuws/{blog}', name: 'news:show')]
    public function show(Blog $blog): Renderable
    {
        $blog->increment('views');

        return view('blog.show', data: [
            'post' => $blog,
            'comments' => $blog->comments()->orderBy('created_at', 'desc')->simpleFastPaginate(6)->appends(request()->query()),
            'categories' => Category::with('posts')->get(),
        ]);
    }

    #[Get(uri: '/nieuw-nieuwsartikel', name: 'news:create', middleware: ['auth', 'verified', 'forbid-banned-user', 'can:create,App\Models\Blog'])]
    public function create(Request $request): Renderable
    {
        return view('blog.create', data: [
            'writtenArticles' => $request->user()->articles()->count(),
            'categories' => Category::query()->guestCategories()->select('name', 'id')->get(),
        ]);
    }

    #[Post(uri: '/nieuw-nieuwsartikel', name: 'news:store', middleware: ['auth', 'verified', 'forbid-banned-user'])]
    public function store(StoreGuestArticleRequest $storeGuestArticleRequest, StoreGuestArticle $storeGuestArticle): RedirectResponse
    {
        $storeGuestArticle->handle($storeGuestArticleRequest->getData());
        flash('We hebben uw artikel goed ontvangen. Een adminstrator zal het spoedig nalezen en publiceren.', 'alert-success');

        return back();
    }
}
