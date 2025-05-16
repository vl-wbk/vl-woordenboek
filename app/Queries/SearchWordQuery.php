<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Article;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class SearchWordQuery
{
    public function execute(?string $searchTerm = null): mixed
    {
        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->whereNotNull('published_at')
            ->where(function ($query) use ($searchTerm): void {
                $query->where('word', 'like', "%{$searchTerm}%")
                    ->orWhere('word', 'like', "%{$searchTerm}%");
            })
            ->orderBy('word')
            ->paginate(6)
            ->appends(request()->query());
    }

    /**
     * @return array<int, AllowedSort>
     */
    private function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('alfabetisch', 'word'),
            AllowedSort::field('publicatie', 'published_at'),
            AllowedSort::field('weergaves', 'views'),
        ];
    }
}
