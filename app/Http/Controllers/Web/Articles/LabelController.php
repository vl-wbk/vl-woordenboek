<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Label;
use App\Queries\Labels\LabelAnalytics;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Stringable;

final readonly class LabelController
{
    #[Get(uri: 'label/{label}', name: 'label:show')]
    public function __invoke(Request $request, Label $label, LabelAnalytics $labelAnalytics): Renderable
    {
        return view('definitions.labels.show', data: [
            'label' => $label,
            'relatedArticles' => $this->getRelatedArticleSearch($request, $label),
            'popularWord' => $label->articles()->whereNotNull('published_at')->orderBy('views', 'desc')->first(),
            'analytics' => $labelAnalytics->fetch(label: $label),
        ]);
    }

    private function getRelatedArticleSearch(Request $request, Label $label): LengthAwarePaginator
    {
        $searchInput = $request->get('zoekterm');
        $sorting = $this->getSortBy($request->string('sortering'));

        return $label->articles()
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
            default => ['column' => 'word', 'order' => 'ASC'],
        };
    }
}
