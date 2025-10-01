<?php

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Pages;

use App\Filament\Clusters\Articles\Resources\Etymologies\Actions\ArchiveEtymology;
use App\Filament\Clusters\Articles\Resources\Etymologies\Actions\RejectEtymology;
use App\Filament\Clusters\Articles\Resources\Etymologies\Actions\PublishEtymology;
use App\Filament\Clusters\Articles\Resources\Etymologies\Actions\DraftEtymology;
use App\Filament\Clusters\Articles\Resources\Etymologies\Actions\UnderReviewEtymology;
use Filament\Support\Enums\Width;
use App\Models\Etymology;
use App\Filament\Clusters\Articles\Resources\Etymologies\EtymologyResource;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Represents the Filament page for viewing a single Etymology record in detail.
 *
 * This class extends Filament's `ViewRecord` base class, providing a dedicated interface for administrators and authorized users to inspect all aspects of an etymology entry.
 * It offers a comprehensive overview of the etymology's data and includes a rich set of header actions, allowing for efficient management of the etymology's lifecycle,
 * such as changing its status (e.g., publishing, archiving, rejecting) and performing other related operations like editing or viewing its associated article.
 * The design aims to provide a clear, actionable, and user-friendly display for etymology records.
 *
 * @property Etymology $record The database entity from the given etymology.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages
 */
final class ViewEtymology extends ViewRecord
{
    /**
     * The resource associated with this view page.
     *
     * This static property links the `ViewEtymology` page to the `EtymologyResource`.
     * It ensures that the view correctly displays data for Etymology models, facilitating the retrieval and presentation of etymological information.
     *
     * @var string $resource - The fully qualified class name of the resource.
     */
    protected static string $resource = EtymologyResource::class;

    /**
     * Retrieves the array of header actions for the Etymology view page.
     * This method defines the primary interactive elements displayed at the top of the view page. These actions are logically grouped to enhance usability.
     *
     * Status Management Actions - A dropdown group allowing users to change the etymology's status (e.g., `ArchiveEtymology`, `RejectEtymology`, `PublishEtymology`, `DraftEtymology`, `UnderReviewEtymology`).
     * General Actions - A second dropdown group providing access to related functionalities, including:
     *
     * - `view-article`:    An action to navigate to the associated `ArticleResource` page for the current etymology's linked article.
     * - `EditAction`:      Allows modification of the etymology record, opening a modal with a specified maximum width.
     * - `DeleteAction`:    Provides the capability to delete the etymology record, with authorization checks in place to ensure only permitted users can perform this action.
     *
     * Each action is configured with appropriate labels, icons, and colors to provide a clear and intuitive user experience.
     *
     * @return array<Action|ActionGroup> An array of Filament Actions and ActionGroups that will be rendered in the page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                ArchiveEtymology::make(),
                RejectEtymology::make(),
                PublishEtymology::make(),
                DraftEtymology::make(),
                UnderReviewEtymology::make(),
            ])
                ->button()
                ->label(label: __('etymology-resource.actions.view-etymology.view-article.mark-label'))
                ->color('gray')
                ->icon('heroicon-o-tag'),

            ActionGroup::make([
                Action::make('view-article')
                    ->color('gray')
                    ->label(label: __('etymology-resource.actions.view-etymology.view-article.label'))
                    ->icon('heroicon-o-eye')
                    ->url(ArticleResource::getUrl('view', ['record' => $this->record->article])),

                EditAction::make()->icon('heroicon-o-pencil-square')
                    ->modalWidth(Width::SevenExtraLarge),

                ActionGroup::make([
                    DeleteAction::make()->icon('heroicon-s-trash'),
                ])
                    ->authorize('delete', $this->record)
                    ->dropdown(false),
            ])
                ->button()
                ->label(label: __('etymology-resource.actions.view-etymology.label'))
                ->color('gray')
                ->icon('heroicon-o-cog'),
        ];
    }
}
