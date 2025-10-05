<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Actions;

use App\Models\Blog;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * An action to "unpublish" a blog post.
 *
 * This action provides a user interface element within Filament to change the status of a published blog post back to a draft state.
 * It includes a confirmation modal, custom notifications, and authorization checks to ensure the action can only be performed on published articles.
 *
 * @property Blog $record The database entity from the blog post.
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Actions
 */
final class UndoPublicationAction extends Action
{
    use CanCustomizeProcess;

    /**
     * Get the default name for the action.
     * This name is used to identify the action internally within Filament.
     */
    public static function getDefaultName(): string
    {
        return 'undo-publication-article';
    }

    /**
     * Set up the action's configuration.
     *
     * This method configures the action's appearance, behavior, and core logic.
     * It's called automatically when the action is instantiated.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Configure the action's visual properties.
        $this->label('Offline halen');
        $this->icon('tabler-eye-cancel');
        $this->color('gray');

        // The action is only authorized if the user has permission to "undo-publication"
        $this->authorize('undo-publication', $this->record);

        // Configure the confirmation modal
        $this->requiresConfirmation();
        $this->modalHeading('Nieuwsartikel offline halen');
        $this->modalDescription('U staat op het punt om een nieuwsartikel offline te halen. Ij het offline halen zal het nog wel zichtbaar zijn in de beheersconsole. Maar niet meer voor het brede publiek. Bent u zeker dat u de actie wilt uitvoeren?');
        $this->modalIcon('tabler-eye-cancel');
        $this->modalIconColor('primary');
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        // Configure the notification messages
        $this->successNotificationTitle('Het nieuwsartikel is met success offline gehaald');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        // Define the core action logic
        $this->action(function (): void {
            // Use the process helper to execute the transition to draft
            if ($this->process(fn(): bool => $this->record->publicationStatus()->transitionToDraft())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
