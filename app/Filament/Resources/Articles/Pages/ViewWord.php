<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\Actions\DuplicationArticleAction;
use App\Filament\Resources\Articles\Actions\PreviewArticleAction;
use App\Filament\Resources\Articles\Actions\RevokePublication;
use App\Filament\Resources\Articles\Actions\SoftDeleteArticleAction;
use App\Filament\Resources\Articles\Actions\States as ArticleStateActions;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Filament\Actions\ActionGroup;
use Filament\Actions as FilamentActions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kirschbaum\Commentions\Filament\Actions\CommentsAction;

/**
 * Class ViewWord
 *
 * This page serves as the primary administrative interface for viewing individual article entities within the system.
 * It extends the base Filament ViewRecord page to provide a highly customized, robust environment for editorial workflows.
 *
 * Key Responsibilities:
 * - Rendering comprehensive article details for moderator review.
 * - Providing a centralized hub for article lifecycle management (publishing, archiving, deletion).
 * - Enabling collaborative editorial feedback loops via integrated comment threads.
 * - Handling record resolution with support for soft-deleted entities to ensure consistent historical auditability.
 *
 * @property Article $record The specific Article model instance currently being viewed in the administrative panel.
 */
final class ViewWord extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     * This property links the `ViewWord` page to the `ArticleResource`, ensuring that the correct resource configuration is used for displaying and managing articles.
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Provides an action to generate a real-time preview of the article content.
     * This utility allows editors to verify formatting, media placement, and content accuracy before finalizing state changes.
     */
    public function getPreviewAction(): FilamentActions\Action
    {
        return PreviewArticleAction::make();
    }

    /**
     * Orchestrates the collaborative commenting component using the Commentions plugin.
     *
     * Configuration details:
     * - Scope: Restricted to non-trashed articles to prevent editing of orphaned discussions.
     * - Security: Filters mentionable users based on explicit article-specific update permissions.
     * - UI/UX: Presented as a slide-over modal for non-disruptive feedback input.
     */
    public function getCommentsAction(): CommentsAction
    {
        return CommentsAction::make()
            ->modalWidth(Width::SevenExtraLarge)
            ->hidden(fn (Article $article): bool => $article->trashed())
            ->modalIconColor('primary')
            ->modalIcon(Heroicon::ChatBubbleLeftRight)
            ->modalHeading(fn (Article $article): string => "$article->word - opmerkingen")
            ->modalDescription('Alle opmerkingen en reacties omtrent het artikel')
            ->slideOver()
            ->mentionables(User::permission(['update:article', 'update-published:article'])->get())
            ->perPage(5);
    }

    /**
     * Defines the actions displayed in the page header.
     *
     * The header actions provide tools for managing the article, including editing, publishing, archiving, and deleting.
     * These actions are configured to include icons and colors for better visual representation in the admin panel.
     *
     * @return array<FilamentActions\Action|ActionGroup> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->getCommentsAction(),
            $this->getPreviewAction(),
            $this->getManagementActionGroup(),
            $this->getPublicationActionGroup(),

            SoftDeleteArticleAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }

    /**
     * Custom resolution logic for the page's record.
     *
     * Overrides the default resolution process to explicitly include soft-deleted records.
     * This is critical for administrative interfaces where moderators may need to recover or review articles that have been moved to the trash.
     *
     * @param  int|string $key  The primary key of the Article to be retrieved.
     * @return Model            The resolved Article instance.
     *
     * @throws ModelNotFoundException If no record matches the given key.
     */
    protected function resolveRecord(int|string $key): Model
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = self::getResource()::getModel();

        return $modelClass::query()
            ->withTrashed()
            ->findOrFail($key);
    }

    /**
     * Builds the "Publicatie" action group for state transitions related to article release.
     *
     * Groups actions focused on moving an article through the approval and publishing pipeline:
     * - Accepting/Rejecting proposals.
     * - Revoking previously published status.
     */
    private function getPublicationActionGroup(): ActionGroup
    {
        return ActionGroup::make([
            ArticleStateActions\AcceptPublishingProposal::make(),
            ArticleStateActions\RejectPublishingAction::make(),
            RevokePublication::make(),
        ])
            ->color('gray')
            ->icon('tabler-world-upload')
            ->label('Publicatie')
            ->button();
    }

    /**
     * Builds the "Management" action group for standard CRUD operations and state maintenance.
     *
     * Provides quick access to common modifications and structural changes:
     * - Editing existing fields.
     * - Duplicating content for new iterations.
     * - Moving articles to the archive.
     * - Explicit publishing trigger.
     */
    private function getManagementActionGroup(): ActionGroup
    {
        return ActionGroup::make([
            FilamentActions\EditAction::make()->icon('heroicon-o-pencil-square')->color('gray'),
            DuplicationArticleAction::make(),
            ArticleStateActions\ArchiveArticle::make(),
            ArticleStateActions\PublishArticleAction::make(),
        ])->buttonGroup();
    }
}
