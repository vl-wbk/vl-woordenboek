<?php

use App\Filament\Clusters\Articles\Resources\ArticleReportResource\Pages\ViewArticleReport;
use App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers\ReportsRelationManager;
use App\Models\Article;
use App\Models\ArticleReport;

use function Pest\Livewire\livewire;

it('can render the relation manager', function (): void {
    $article = Article::factory()
        ->has(ArticleReport::factory()->count(5), 'reports')
        ->create();

        livewire(ReportsRelationManager::class, ['ownerRecord' => $article, 'pageClass' => ViewArticleReport::class])
            ->assertSuccessful()
            ->assertCanSeeTableRecords($article->reports);
})->group('reports', 'articles');
