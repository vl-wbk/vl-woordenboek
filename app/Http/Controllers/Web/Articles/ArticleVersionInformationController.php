<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\UserTypes;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Response;

final readonly class ArticleVersionInformationController
{
    #[Get(uri: '/versie-informatie/{audit}', name: 'change:information', middleware: ['auth', 'forbid-banned-user', 'verified'])]
    public function __invoke(Request $request, Audit $audit): Renderable
    {
        return view('versions.info', data: [
            'recentAudits' => $this->getRecentAudits($audit),
            'previous' => $this->getPreviousAudit($audit),
            'next' => $this->getNextAudit($audit),
            'audit' => $audit,
        ]);
    }

    public function getPreviousAudit(Audit $audit): ?Audit
    {
        return Audit::where('auditable_type', $audit->auditable_type)
            ->where('auditable_id', $audit->auditable_id)
            ->where('id', '<', $audit->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextAudit(Audit $audit): ?Audit
    {
        return Audit::where('auditable_type', $audit->auditable_type)
            ->where('auditable_id', $audit->auditable_id)
            ->where('id', '>', $audit->id)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * @return Collection<int, int>
     */
    private function getRecentAudits(Audit $audit): Collection
    {
        return Audit::with('auditable')
            ->where('auditable_type', 'App\Models\Article')
            ->where('auditable_id', $audit->auditable_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
