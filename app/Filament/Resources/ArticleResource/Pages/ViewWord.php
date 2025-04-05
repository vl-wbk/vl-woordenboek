<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Actions\RemoveEditorAction;
use App\Filament\Resources\ArticleResource\Actions\States as ArticleStateActions;
use Filament\Actions as FilamentActions;
use Filament\Resources\Pages\ViewRecord;

/**
 * ViewWord is the page used to display a detailed view of a single article in the admin panel.
 *
 * This class extends Filament's ViewRecord to present all relevant data about an article.
 * It defines a series of header actions that allow administrators to manage the article,
 * including editing, publishing, handling publishing proposals, archiving, rejection, removing
 * the assigned editor, and deletion. These actions are arranged in a header bar for quick access.
 *
 * Future developers will find this class useful as a centralized point for customizing article management actions.
 * New actions can easily be added or existing ones modified, thanks to the clear structure built on Filament's standard components.
 *
 * @package App\Filament\Resources\ArticleRepository\Pages
 */
final class ViewWord extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     * This links the page to the ArticleResource, which defines how articles are handled throughout the admin panel.
     *
     * @var string
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Returns an array of header actions for article management.
     *
     * The actions defined here enable various operations including editing, publishing, accepting or rejecting proposals, archiving, removing an editor, and deletion.
     *
     * Each action is configured with an icon, color, and other properties to ensure consistency in the user interface.
     * This modular approach allows developers to easily customize which actions are available and how they behave.
     *
     * @return array<int, mixed> The set of configured header actions.
     */
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
