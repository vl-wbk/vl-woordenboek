<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleReports\Pages;

use App\Filament\Clusters\Articles\Resources\ArticleReports\Actions\CloseArticleReportAction;
use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use App\Filament\Resources\Articles\Actions\PreviewArticleAction;
use App\Filament\Resources\Users\UserResource;
use App\Models\ArticleReport;
use App\Models\User;
use App\Policies\ArticlePolicy;
use App\States\Reporting\Status;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

/**
 * Represents the page for viewing a single article report in the admin panel.
 *
 * The `EditArticleReport` class extends Filament's `ViewRecord` class to provide a detailed view of an article report's data.
 * It is part of the `ArticleReportResource` and integrates various actions for managing the report's lifecycle.
 *
 * This page is designed for administrators and moderators to review the details of a report and take actions such as assigning the report to themselves, closing the report, or deleting it.
 * The available actions are displayed in the page header for quick access.
 */
final class EditArticleReport extends EditRecord
{
    /**
     * Links this page to the main article report resource.
     *
     * @var string
     */
    protected static string $resource = ArticleReportResource::class;

    /**
     * Controls whether the success notification is shown to the user after saving.
     * When true, the visual feedback popup is completely suppressed.
     *
     * @var bool
     */
    public bool $suppressSavedNotification = false;

    /**
     * Sets the breadcrumb label for this page to reflect its active moderation state.
     *
     * @return string
     */
    public function getBreadcrumb(): string
    {
        return 'Behandelen';
    }

    /**
     * Sets the main page heading to indicate that the report is currently being processed.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'Melding behandelen';
    }

    /**
     * Determines which notification to send to the UI after a successful save.
     * If the notification supression flag is active, this returns null to bypass the UI popup.
     *
     * @return Notification|null
     */
    protected function getSavedNotification(): ?Notification
    {
        if ($this->suppressSavedNotification) {
            return null;
        }

        return parent::getSavedNotification();
    }

    /**
     * Saves the form changes silently to the database.
     *
     * This temporarily supresses and then restores the success notification to prevent
     * cluttering the user interface during automated state updates.
     *
     * @return void
     */
    public function saveQuietly(): void
    {
        $this->suppressSavedNotification = true;
        $this->save();
        $this->suppressSavedNotification = false;
    }

    /**
     * Configures the header actions for the moderation workflow.
     *
     * Offers quick-access buttons to:
     *
     * - View the reporter's user profile (if authorized and reporter still exists).
     * - Close/Resolve the report.
     * - Safely cancel the operation with a confirmation model that warns of unsaved changes.
     * - Delete the report entirely.
     *
     * @return array<Action> An array of configured header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make(actions: [
                Action::make('reporter-information')
                    ->hidden(fn (ArticleReport $articleReport): bool => $articleReport->author()->doesntExist())
                    ->authorize('viewAny', User::class)
                    ->label('bekijk melder')
                    ->icon('tabler-user-search')
                    ->color('gray')
                    ->url(fn (ArticleReport $articleReport): string => UserResource::getUrl('view', ['record' => $articleReport->author])),
            ])->buttonGroup(),

            ActionGroup::make(actions: [
                CloseArticleReportAction::make(),

                Action::make('cancel')
                    ->hidden(fn (ArticleReport $articleReport): bool => $articleReport->state->is(Status::Closed))
                    ->icon(Heroicon::OutlinedXCircle)
                    ->label('Annuleren')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalCloseButton(false)
                    ->modalIcon(Heroicon::OutlinedXCircle)
                    ->modalIconColor('danger')
                    ->modalHeading('Behandeling annuleren')
                    ->modalDescription('U staat op het punt om de behandeling van een melding te annuleren. Daardoor zullen de wijzigingen in het formulier niet worden opgeslagen. Ben je zeker dat je dit wilt doen?')
                    ->action(fn () => $this->redirect($this->previousUrl ?? static::getResource()::getUrl('index')))
                    ->modalSubmitActionLabel('Ja, ik ben zeker')
                    ->modalSubmitAction(fn (\Filament\Actions\Action $action) => $action->color('danger')),
            ])->buttonGroup(),

            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    /**
     * Disables the defualt footer actions.
     *
     * By returning an empty array, the standard "Save" and "Cancel" buttons at the bottom of the form are hidden,
     * funneling all moderation flow decisions through the header actions.
     *
     * @return array
     */
    protected function getFormActions(): array
    {
        return [];
    }
}
