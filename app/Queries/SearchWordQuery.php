<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Word;

final readonly class SearchWordQuery
{
    public function execute(?string $searchTerm = null): mixed
    {
        return Word::query()
            ->where('word', 'LIKE', "%{$searchTerm}%")
            ->paginate();
    }
}
