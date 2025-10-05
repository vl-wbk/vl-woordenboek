<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Pages;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Models\Blog;
use App\Filament\Clusters\Blog\Resources\Blogs\BlogResource;
use App\Filament\Clusters\Blog\Resources\Blogs\Actions as ResourceSpecificActions;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * * Represents the "View Blog" page in the Filament admin panel.
 *
 * This page allows users to view the details of a specific blog post.
 * It extends Filament's `ViewRecord` class and provides additional functionality through custom header actions. These actions include editing, publishing, managing comments, and deleting the blog post.
 *
 * @property Blog $record The database entity from the blog post in the database.
 */
final class ViewBlog extends ViewRecord
{
    /**
     * Specifies the resource associated with this page.
     * This links the page to the `BlogResource`, ensuring that it operates within the context of the blog resource.
     */
    protected static string $resource = BlogResource::class;

    /**
     * Defines the header actions available on the "View Blog" page.
     *
     * The header actions include:
     * - Editing the blog post.
     * - Activating or deactivating comments.
     * - Publishing or undoing the publication of the blog post.
     * - Deleting the blog post, with specific authorization checks.
     *
     * The actions are grouped logically to improve the user interface and ensure proper authorization where necessary.
     *
     * @return array<int, Actions\ActionGroup> The array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                EditAction::make()
                    ->color('gray')
                    ->icon('heroicon-o-pencil-square'),

                ResourceSpecificActions\ActivateCommentsAction::make(),
                ResourceSpecificActions\DeactivateCommentsAction::make(),
                ResourceSpecificActions\PublishArticleAction::make(),
                ResourceSpecificActions\UndoPublicationAction::make(),

                // Allows deleting the current blog record.
                // It's wrapped in its own ActionGroup to apply authorization specifically to the delete action.
                ActionGroup::make([
                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ])
                    ->dropdown(false)
                    ->authorize('delete', $this->record),
            ])
                ->button()
                ->color('gray')
                ->icon('heroicon-o-cog-8-tooth'),
        ];
    }
}
