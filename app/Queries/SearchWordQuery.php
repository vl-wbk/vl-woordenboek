<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Article;

final readonly class SearchWordQuery
{
    public function execute(?string $searchTerm = null): mixed
    {
        return Article::query()
            ->published()
            ->where('word', 'LIKE', "%{$searchTerm}%")
            ->orWhere('keywords', 'LIKE', "%{$searchTerm}%")
            ->paginate()
            ->withQueryString();
    }
}
