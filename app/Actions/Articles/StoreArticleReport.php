<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\ArticleReportData;
use App\Http\Requests\Articles\StoreReportRequest;
use App\Models\Article;
use App\Models\ArticleReport;
use Illuminate\Support\Facades\DB;

final readonly class StoreArticleReport
{
    public function execute(StoreReportRequest $storeReportRequest, Article $article): void
    {
        DB::transaction(function () use ($storeReportRequest, $article): void {
            $reportData = $storeReportRequest->getData();
            $articleReport = $this->newArticleReportInstance($article, $reportData);

            tap($articleReport, function (ArticleReport $articleReport): void {
                $articleReport->setCurrentUserAsAuthor();
            });
        });
    }

    private function newArticleReportInstance(Article $article, ArticleReportData $articleReportData): ArticleReport
    {
        return $article->reports()->save(
            model: new ArticleReport(attributes: $articleReportData->toArray())
        );
    }
}
