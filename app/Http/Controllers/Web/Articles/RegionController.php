<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Region;
use App\Queries\Regions\RegionAnalytics;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Stringable;

final readonly class RegionController
{
    #[Get(uri: 'region/{region}', name: 'region:show')]
    public function __invoke(Request $request, Region $region, RegionAnalytics $regionAnalytics): Renderable
    {
        return view('definitions.regions.show', data: [
            'region' => $region,
            'relatedArticles' => $this->getRelatedArticleSearch($request, $region),
            'popularWord' => $region->articles()->whereNotNull('published_at')->orderBy('views', 'desc')->first(),
            'analytics' => $regionAnalytics->fetch($region)
        ]);
    }

    private function getRelatedArticleSearch(Request $request, Region $region): LengthAwarePaginator
    {
        $searchInput = $request->get('zoekterm');
        $sorting = $this->getSortBy($request->string('sortering'));

        return $region->articles()
            ->whereNotNull('published_at')
            ->where(function ($query) use ($searchInput): void {
                $query->where('word', 'LIKE', "%$searchInput%")
                    ->orWhere('keywords', 'LIKE', "%$searchInput%");
            })
            ->orderBy($sorting['column'], $sorting['order'])
            ->paginate()
            ->fragment('woorden');
    }

    private function getSortBy(?Stringable $sort): array
    {
        return match($sort->value) {
            'alfabetisch' => ['column' => 'word', 'order' => 'ASC'],
            'populariteit' => ['column' => 'views', 'order' => 'DESC'],
            'recent' => ['column' => 'published_at', 'order' => 'ASC'],
            default => ['column' => 'views', 'order' => 'DESC'],
        };
    }
}
