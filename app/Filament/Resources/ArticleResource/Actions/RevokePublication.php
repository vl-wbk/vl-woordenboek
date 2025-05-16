<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;

/**
 * Class RevokePublication
 *
 * This class defines a Filament Action for revoking the publication of an article.
 * It allows authorized users to unpublish a previously published article, making it no longer accessible to regular users.
 * This action is typically used when an article needs to be reworked or corrected.
 *
 * @property \App\Models\Article $record The dictionary article being unpublished.
 *
 * @package App\Filament\Resources\ArticleResource\Actions
 */
final class RevokePublication extends Action
{
    use CanCustomizeProcess;

    /**
     * Returns the default name for the action.
     *
     * This method provides a human-readable name for the action, which is displayed in the user interface.
     * The name is retrieved from the translation file using the `trans()` helper function.
     *
     * @return string The translated name of the action (e.g., "Ongedaan maken").
     */
    public static function getDefaultName(): string
    {
        return trans('ongedaan maken');
    }

    /**
     * Configures the action.
     *
     * This method sets up the action's properties, such as its authorization requirements, icon, color, confirmation modal, form, and success/failure notifications.
     * It also defines the action's logic, which transitions the article to the "editing" state.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Authorize the action based on the 'unpublish' ability and the current record.
        $this->authorize('unpublish', $this->record);

        // Customize the action's appearance.
        $this->icon('tabler-arrow-back-up');
        $this->color('danger');

        // Require user confirmation before proceeding.
        $this->requiresConfirmation();

        // Configure the confirmation modal.
        $this->modalIcon('tabler-arrow-back-up');
        $this->modalCloseButton(false);
        $this->modalHeading('Publicatie ongedaan maken');
        $this->modalDescription('Bij het ongemaken van een publicatie zal het artikel niet meer raadpleegbaar zijn voor gebruikers. Dit kan handig zijn voor een herwerking van het artikel.');
        $this->modalSubmitActionLabel('Ongedaan maken');

        // Define the form for providing a reason for unpublishing.
        $this->form([
            Textarea::make('reason')
                ->label('Reden van de handeling')
                ->placeholder('Beschrijf kort waarom je de publicatie ongedaan wilt maken.')
                ->rows(5)
                ->required()
        ]);

        // Set up notifications for success and failure.
        $this->successNotificationTitle('Publicatie ongedaan gemaakt');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets migelopen.');

        // Define the action's execution logic.
        $this->action(function (): void {
            // Attempt to transition the article to the "editing" state, providing the reason.
            if ($this->process(fn (array $data): bool => $this->record->articleStatus()->transitionToEditing($data['reason']))) {
                // If successful, display a success message.
                $this->success();
                return;
            }

            // If the transition fails, display a failure message.
            $this->failure();
        });
    }
}
