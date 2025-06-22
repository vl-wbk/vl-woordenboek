<?php

declare(strict_types=1);

namespace App\Queries;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Provides a focused and efficient way to search through published blog articles.
 *
 * This query class is responsible for retrieving blog posts that are publicly available, optionally filtering them by a search term provided in the request.
 * It also ensures that related data, such as the article's category and author, are loaded efficiently to avoid performance issues.
 * Results are paginated to improve usability and performance, and any applied search parameters are preserved across pagination links.
 *
 * @package App\Queries
 */
final readonly class SearchArticlesQuery
{
    /**
     * Searches for published blog articles and returns paginated results.
     *
     * This method constructs a query to retrieve blog posts that are marked as published.
     * If the request contains a 'zoekterm' parameter, the query will filter articles whose titles contain the provided search term.
     * The method also ensures that related category and author data are loaded efficiently to avoid performance issues.
     *
     * Results are paginated with six articles per page, and any search or filter parameters from the request are preserved in the pagination links to maintain user context across pages.
     *
     * @param  Request $request         The current HTTP request, which may include a 'zoekterm' for filtering.
     * @return Paginator<int, Model>    Paginated list of published blog articles, optionally filtered by title.
     */
    public function execute(Request $request): Paginator
    {
        return Blog::query()
            ->with(['category', 'author'])
            ->where('status', Status::Published)
            /** @param Builder<Blog> $blog */
            ->when($request->has('zoekterm') && $request->get('zoekterm') !== null, function (Builder $builder) use ($request): void {
                $builder->where('title', 'LIKE', "%{$request->get('zoekterm')}%");
            })
            ->simpleFastPaginate(6)
            ->appends(request()->query());
    }
}
