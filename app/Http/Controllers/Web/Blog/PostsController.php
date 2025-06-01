<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class PostsController
{
    #[Get(uri: '/nieuws', name: 'news:index')]
    public function index(): Renderable
    {
        return view('blog.index');
    }
}
