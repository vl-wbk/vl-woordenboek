<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Response;

final readonly class PostsController
{
    #[Get(uri: '/nieuws', name: 'news:index')]
    public function index(): Renderable
    {
        abort_unless(Blog::count() > 0, Response::HTTP_NOT_FOUND);

        return view('blog.index', data: [
            'posts' => Blog::query()->with(['category', 'author'])->where('status', Status::Published)->simplePaginate(6),
            'categories' => Category::with('posts')->get(),
        ]);
    }

    #[Get(uri: '/nieuws/{blog}', name: 'news:show')]
    public function show(Blog $blog): Renderable
    {
        dd('works');
    }
}
