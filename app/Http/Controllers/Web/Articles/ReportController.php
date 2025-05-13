<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleReport;
use App\Http\Requests\Articles\StoreReportRequest;
use App\Models\Article;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Middleware;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Middleware(middleware: ['auth', 'forbid-banned-user', 'verified'])]
final readonly class ReportController
{
    #[Post(uri: '/{article}/rapportering', name: 'article-report.create')]
    public function __invoke(StoreReportRequest $storeReportRequest, StoreArticleReport $storeArticleReport, Article $article): RedirectResponse
    {
        $storeArticleReport->execute($storeReportRequest, $article);
        $storeReportRequest->session()->flash('status', 'We hebben uw melding goed ontvangen!');

        return redirect()->action(DictionaryArticleController::class, ['word' => $article]);
    }
}
