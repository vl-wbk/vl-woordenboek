<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Models\Blog;
use App\Models\Category;
use App\Queries\SearchArticlesQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Response;

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
}
