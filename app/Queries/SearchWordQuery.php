<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\ArticleBuilder;
use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Queries
 */
final readonly class SearchWordQuery
{
    public function execute(Request $request): LengthAwarePaginator
    {
        $includeDescription = $request->boolean('uitgebreid');
        $includeArchive = $request->boolean('archief');
        $isExact = $request->get('zoekpatroon') === SearchPatterns::Exact->value;

        return QueryBuilder::for(Article::class)
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters())
            ->with(['author', 'regions', 'bookmarkers'])
            ->where(function ($q) use ($includeArchive) {
                $q->whereNotNull('published_at');

                if ($includeArchive) {
                    $q->orWhereNotNull('archived_at');
                }
            })
            ->where(function ($query) use ($request, $includeDescription, $isExact): void {

                // EXACT search blijft ongewijzigd
                $patternType = $request->get('zoekpatroon');

// EXACT = volledige string
if ($patternType === SearchPatterns::Exact->value) {
    $pattern = $this->getSearchPattern($request);

    $query->where('word', '=', $pattern['pattern'])
        ->orWhere('keywords', '=', $pattern['pattern'])
        ->when(
            $includeDescription,
            fn (ArticleBuilder $builder): Builder =>
                $builder->orWhere('description', '=', $pattern['pattern'])
        );

    return;
}

// STARTS WITH = eerste token
if ($patternType === SearchPatterns::StartsWith->value) {
    if ($token = $this->getBoundaryToken($request, true)) {
        $query->where('word', 'LIKE', "{$token}%");
    }

    return;
}

// ENDS WITH = laatste token
if ($patternType === SearchPatterns::EndsWith->value) {
    if ($token = $this->getBoundaryToken($request, false)) {
        $query->where('word', 'LIKE', "%{$token}");
    }

    return;
}

// DEFAULT = token-based AND-search
foreach ($this->getSearchTokens($request) as $token) {
    $query->where(function ($q) use ($token, $includeDescription) {
        $pattern = "%{$token}%";

        $q->where('word', 'LIKE', $pattern)
          ->orWhere('keywords', 'LIKE', $pattern);

        if ($includeDescription) {
            $q->orWhere('description', 'LIKE', $pattern);
        }
    });
}
                }
            })
            ->orderBy('word')
            ->fastPaginate(6)
            ->appends(request()->query());
    }

    /**
 * Geeft het eerste of laatste zoekwoord terug (voor starts/ends-with).
 */
private function getBoundaryToken(Request $request, bool $first = true): ?string
{
    $tokens = $this->getSearchTokens($request);

    if ($tokens === []) {
        return null;
    }

    return $first
        ? $tokens[0]
        : $tokens[array_key_last($tokens)];
}


    /**
     * Splitst de zoekterm in losse tokens (woorden).
     *
     * @return array<int, string>
     */
    private function getSearchTokens(Request $request): array
    {
        return collect(
            preg_split('/\s+/', $request->string('zoekterm')->trim()->toString())
        )
            ->filter(fn (string $token) => mb_strlen($token) >= 2)
            ->values()
            ->all();
    }

    /**
     * Exact / klassieke LIKE-search (blijft bestaan voor specifieke zoekpatronen).
     */
    private function getSearchPattern(Request $request): array
    {
        $searchTerm = $request->string('zoekterm')->trim();
        $isExact = $request->get('zoekpatroon') === SearchPatterns::Exact->value;

        $formattedTerm = $isExact
            ? $searchTerm->toString()
            : $searchTerm->replace(' ', '%')->toString();

        $pattern = match ($request->get('zoekpatroon')) {
            SearchPatterns::StartsWith->value => "{$formattedTerm}%",
            SearchPatterns::EndsWith->value   => "%{$formattedTerm}",
            SearchPatterns::Exact->value      => $formattedTerm,
            default                           => "%{$formattedTerm}%",
        };

        return [
            'pattern'  => $pattern,
            'operator' => $isExact ? '=' : 'LIKE',
        ];
    }

    private function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('alfabetisch', 'word'),
            AllowedSort::field('publicatie', 'published_at'),
            AllowedSort::field('weergaves', 'views'),
        ];
    }

    private function getAllowedFilters(): array
    {
        return [
            AllowedFilter::scope('published_after'),
        ];
    }
}
