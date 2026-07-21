<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Revisions;

use App\Models\Article;
use App\UserTypes;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class IndexController
{
    #[Get(uri: 'revisies/{article}', name: 'article:revisions', middleware: ['auth', 'forbid-banned-user', 'verified'])]
    public function __invoke(Request $request, Article $article): Renderable
    {
        abort_if($article->audits()->count() === 0 && $article->isPublished(), Response::HTTP_NOT_FOUND);

        return view('revisions.index', data: [
            'word' => $article,
            'audits' => $this->getArticleAudits($article),
            'activityByDay' => $this->getActivityByDay($article),
            'topContributors' => $this->getTopContributors($article),
            'topFields' => $this->getTopFields($article),
            'userTypes' => UserTypes::cases(),
        ]);
    }
    private function getArticleAudits(Article $article): LengthAwarePaginator
    {
        return $article->audits()
            ->with(['user', 'auditable'])
            ->when(request('event'), fn ($q, $e) => $q->where('event', $e))
            ->when(request('user'), fn ($q, $u) => $q->whereHas('user', fn ($q) => $q->where('name', 'like', "%$u%")))
            ->when(request('from'), fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when(request('to'), fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->when(request('user_type'), fn ($q, $t) => $q->whereHas('user', fn ($q) => $q->where('user_type', (int) $t)))
            ->latest()
            ->paginate(25);
    }

    private function getActivityByDay(Article $article): SupportCollection
    {

        return $this->getAllAudits($article)->isNotEmpty()
            ? collect(
                CarbonPeriod::create(
                    $this->getAllAudits($article)->min('created_at')->toDateString(),
                    $this->getAllAudits($article)->max('created_at')->toDateString()
                )
            )->mapWithKeys(fn ($date) => [
                $date->toDateString() => $this->getAllAudits($article)->filter(
                    fn ($a) => $a->created_at->toDateString() === $date->toDateString()
                )->count(),
            ]) : collect();
    }

    private function getAllAudits(Article $article): Collection
    {
        return $article->audits()->with(['user', 'auditable'])->get();
    }

    private function getTopContributors(Article $article): Collection
    {
        return $this->getAllAudits($article)
            ->groupBy('user_id')
            ->map(fn ($g) => (object) ['user' => $g->first()->user, 'count' => $g->count()])
            ->sortByDesc('count')
            ->take(5)
            ->values();
    }

    private function getTopFields(Article $article): SupportCollection
    {
        return collect($this->getAllAudits($article)->flatMap(fn ($a) => array_keys($a->getModified())))
            ->countBy()
            ->sortDesc()
            ->take(5);
    }
}
