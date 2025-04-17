<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Article;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class SearchWordQuery
{
    public function execute(?string $searchTerm = null): mixed
    {
        return QueryBuilder::for(Article::class)
            ->allowedSorts(['published_at', 'views'])
            ->where('word', 'like', "%{$searchTerm}%")
            ->orWhere('word', 'like', "%{$searchTerm}%")
            ->paginate(6)
            ->appends(request()->query());
    }
}
