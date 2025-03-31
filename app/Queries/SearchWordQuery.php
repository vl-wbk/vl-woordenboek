<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Article;

final readonly class SearchWordQuery
{
    public function execute(?string $searchTerm = null): mixed
    {
        return Article::query()
            ->whereFullText('word', $searchTerm, ['mode' => 'boolean'])
            ->orWhereFulltext('keywords', $searchTerm)
            ->paginate()
            ->withQueryString();
    }
}
