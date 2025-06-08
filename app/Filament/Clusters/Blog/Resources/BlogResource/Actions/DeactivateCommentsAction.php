<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

final class DeactivateCommentsAction extends Action
{
    use CanCustomizeProcess;

    private string $actionIcon = 'tabler-message-off';

    public static function getDefaultName(): string
    {
        return 'deactivate-comments';
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Customize the action's appearance.
        $this->icon(icon: $this->actionIcon);
        $this->color('danger');
        $this->visible(condition: $this->canPerformTheAction());

        // Require user ocnfirmation before proceeding.
        $this->requiresConfirmation();

        // Configure the confirmation modal.
        $this->modalIcon(icon: $this->actionIcon);
        $this->modalCloseButton(false);
        $this->modalHeading('Reacties deactiveren');
        $this->modalDescription('Het deactiveren van de reacties zal ervoor zorgen dat gebruikers niet meer in staat zijn om te reageren op het artikel. Weet je zeker dat je deze actie wilt uitvoeren?');

        // Set up notifications for success and failure of the action.
        $this->successNotificationTitle('De reacties zijn gedeactiveerd voor het nieuwsartikel');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');

        // Define the action's execution logic.
        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->update(attributes: ['comments_enabled' => false]))) {
                $this->success(); // If successful, display a success message.
                return;
            }

            // If the transition fails, display a failure message.
            $this->failure();
        });
    }

    private function canPerformTheAction(): bool
    {
        return $this->record->hasCommentsEnabled()
            && (auth()->user()->isDeveloper() || auth()->user()->isAdministrator());
    }
}
