<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CategoryController
{
    #[Get(uri: 'nieuws-categorie/{category}', name: 'categories:show')]
    public function show(Category $category): Renderable
    {
        return view('blog.index', data: [
            'category' => $category,
            /** @phpstan-ignore-next-line */
            'posts' => $category->posts()->simpleFastPaginate(6)->appends(request()->query()),
            'categories' => Category::with('posts')->get(),
        ]);
    }
}
