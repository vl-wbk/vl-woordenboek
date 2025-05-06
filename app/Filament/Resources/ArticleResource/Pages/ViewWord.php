<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Actions\RevokePublication;
use App\Filament\Resources\ArticleResource\Actions\States as ArticleStateActions;
use Filament\Actions as FilamentActions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

/**
 * Represents the page for viewing a single article in the admin panel.
 *
 * The `ViewWord` class extends Filament's `ViewRecord` class to provide a detailed view of an article's data.
 * It is part of the `ArticleResource` and integrates various actions for managing the article's state and lifecycle.
 *
 * This page is designed for administrators and moderators to review article details and perform actions such as editing, publishing, archiving, or deleting the article.
 * The available actions are displayed in the page header for quick access.
 *
 * @package App\Filament\Resources\ArticleResource\Pages
 */
final class ViewWord extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links the `ViewWord` page to the `ArticleResource`, ensuring that
     * the correct resource configuration is used for displaying and managing articles.
     *
     * @var string
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Defines the actions displayed in the page header.
     *
     * The header actions provide tools for managing the article, including editing, publishing, archiving, and deleting.
     * These actions are configured to include icons and colors for better visual representation in the admin panel.
     *
     * @return array<FilamentActions\Action> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            FilamentActions\EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            ArticleStateActions\PublishArticleAction::make(),
            ArticleStateActions\ArchiveArticle::make(),

            ActionGroup::make([
                ArticleStateActions\AcceptPublishingProposal::make(),
                ArticleStateActions\RejectPublishingAction::make(),
                RevokePublication::make(),
            ])
            ->color('gray')
            ->icon('tabler-world-upload')
            ->label('Publicatie')
            ->authorizeAny(['publish', 'unpublish'], $this->record)
            ->button(),

            ArticleStateActions\UnarchiveAction::make(),
            FilamentActions\DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }
}
