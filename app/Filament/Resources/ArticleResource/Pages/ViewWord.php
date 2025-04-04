<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Actions\RemoveEditorAction;
use App\Filament\Resources\ArticleResource\Actions\States as ArticleStateActions;
use Filament\Actions as FilamentActions;
use Filament\Resources\Pages\ViewRecord;

final class ViewWord extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
                FilamentActions\EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
                ArticleStateActions\PublishArticleAction::make(),
                ArticleStateActions\AcceptPublishingProposal::make(),
                ArticleStateActions\ArchiveArticle::make(),
                ArticleStateActions\RejectPublishingAction::make(),
                RemoveEditorAction::make(),
                FilamentActions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
