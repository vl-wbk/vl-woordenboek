<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use Spatie\LaravelData\Exceptions\InvalidDataClass;
use App\Actions\Blog\StoreGuestArticle;
use App\Http\Requests\Blog\StoreGuestArticleRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Queries\SearchArticlesQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

	#[Get(uri: 'nieuws/artikel-insturen', name: 'news:create', middleware: ['auth', 'forbid-banned-user', 'can:submitPost,App\Models\Blog'])]
	public function create(): Renderable
	{
		return view('blog.create');
	}

	/**
     * @throws InvalidDataClass when the data transfer object contains invalid data
     * @throws Throwable        when the guest article couldn't be store successfully.
     */
    #[Post(uri: 'nieuws/artikel-insturen', name: 'news:store', middleware: ['auth', 'forbid-banned-user', 'can:submitPost,App\Models\Blog'])]
	public function store(StoreGuestArticleRequest $storeGuestArticleRequest, StoreGuestArticle $storeGuestArticle): RedirectResponse
	{
		$storeGuestArticle->handle($storeGuestArticleRequest->getData());
		flash('We hebben uw artikel goed ontvangen een kernlid zal er spoedig naar kijken.', 'alert-success');

		return back();
	}

    #[Get(uri: '/nieuws/{blog}', name: 'news:show')]
    public function show(Blog $blog): Renderable
    {
        /** @phpstan-ignore-next-line */
        $blog->incrementQuietly(column: 'views', extra: ['updated_at' => $blog->updated_at]);

        return view('blog.show', data: [
            'post' => $blog,
            'comments' => $blog->comments()->orderBy('created_at', 'desc')->simpleFastPaginate(6)->appends(request()->query()),
            'categories' => Category::with('posts')->get(),
        ]);
    }
}
