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
use Symfony\Component\HttpFoundation\Response;

final readonly class LabelController
{
    #[Get(uri: '/labels', name: 'label:index')]
    public function index(Request $request): Renderable
    {
        $labels = Label::query()
            ->where('private', false)
            // We tellen het aantal gekoppelde woorden voor de badge-count
            ->withCount('articles')

            // Filter op zoekterm (naam of beschrijving)
            ->when($request->zoekterm, function ($query, $zoekterm) {
                $query->where(function ($q) use ($zoekterm) {
                    $q->where('name', 'like', "%{$zoekterm}%")
                        ->orWhere('description', 'like', "%{$zoekterm}%");
                });
            })

            // Sortering logica
            ->when($request->sortering, function ($query, $sortering) {
                switch ($sortering) {
                    case 'woorden':
                        // Sorteer op het aantal gekoppelde items (meeste eerst)
                        $query->orderBy('articles_count', 'desc');
                        break;
                    case 'recent':
                        // Sorteer op de laatste update
                        $query->orderBy('updated_at', 'desc');
                        break;
                    case 'naam':
                    default:
                        // Standaard alfabetisch
                        $query->orderBy('name', 'asc');
                        break;
                }
            }, function ($query) {
                // Default sortering als er geen request is
                $query->orderBy('name', 'asc');
            })

            // Pagineren met behoud van de query parameters in de links
            ->paginate(12)
            ->withQueryString();

        return view('definitions.labels.index', data: [
            'labels' => $labels
        ]);
    }

    #[Get(uri: 'label/{label}', name: 'label:show')]
    public function show(Request $request, Label $label, LabelAnalytics $labelAnalytics): Renderable
    {
        abort_if($label->private, Response::HTTP_NOT_FOUND);

        return view('definitions.labels.show', data: [
            'label' => $label,
            'relatedArticles' => $this->getRelatedArticleSearch($request, $label),
            'popularWord' => $label->articles()->whereNotNull('published_at')->orderBy('views', 'desc')->first(),
            'analytics' => $labelAnalytics->fetch(label: $label),
        ]);
    }

    /**
     * @return LengthAwarePaginator<int, int>
     */
    private function getRelatedArticleSearch(Request $request, Label $label): LengthAwarePaginator
    {
        $searchInput = $request->get('zoekterm');
        $sorting = $this->getSortBy($request->string('sortering'));

        return $label->articles()
            ->published()
            ->where(function ($query) use ($searchInput): void {
                $query->where('word', 'LIKE', "%$searchInput%")
                    ->orWhere('keywords', 'LIKE', "%$searchInput%");
            })
            ->orderBy($sorting['column'], $sorting['order'])
            ->fastPaginate()
            ->fragment('woorden');
    }

    /**
     * @return array{column: string, order: string}
     */
    private function getSortBy(?Stringable $sort): array
    {
        /** @phpstan-ignore-next-line */
        return match ($sort->toString()) {
            'populariteit' => ['column' => 'views', 'order' => 'DESC'],
            'recent' => ['column' => 'published_at', 'order' => 'ASC'],
            default => ['column' => 'word', 'order' => 'ASC'],
        };
    }
}
