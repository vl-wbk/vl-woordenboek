<?php

declare(strict_types=1);

namespace App\Queries;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

final readonly class SearchArticlesQuery
{
    public function execute(Request $request): Paginator
    {
        return Blog::query()
            ->with(['category', 'author'])
            ->where('status', Status::Published)
            ->when($request->has('zoekterm') && $request->get('zoekterm') !== null, function (Builder $builder) use ($request) {
                $builder->where('title', 'LIKE', "%{$request->get('zoekterm')}%");
            })
            ->simplePaginate(6)
            ->appends(request()->query());
    }
}
