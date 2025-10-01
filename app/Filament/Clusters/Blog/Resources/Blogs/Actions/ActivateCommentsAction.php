<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Actions;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * @todo Document this class
 * @property Comment $record The database entity from the given comment.
 */
final class ActivateCommentsAction extends Action
{
    use CanCustomizeProcess;

    private string $actionIcon = 'tabler-message';

    public static function getDefaultName(): string
    {
        return 'activate-comments';
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Customize the action's appearance.
        $this->icon(icon: $this->actionIcon);
        $this->label('Reacties inschakelen');
        $this->color('success');
        $this->visible(condition: $this->canPerformTheAction());

        // Require user confirmation before proceeding
        $this->requiresConfirmation();

        // Configure the confirmation modal.
        $this->modalIcon(icon: $this->actionIcon);
        $this->modalCloseButton(false);
        $this->modalHeading('Reacties inschakelen');
        $this->modalDescription('Indien u de reacties inschakeled kunnen gebruikers reageren op het nieuwsartikel. Bent u zeker dat u ze wilt inschakelen?');

        // Set up notifications for success and failure of the action.
        $this->successNotificationTitle('Reacties zijn inschakeld');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        // Define the action's execution logic.
        $this->action(function (): void {
            if ($this->process(fn(): bool => $this->record->update(attributes: ['comments_enabled' => true]))) {
                $this->success(); // If successful, display a success message.
                return;
            }

            // If the transition fails, display a failure message.
            $this->failure();
        });
    }

    private function canPerformTheAction(): bool
    {
        return ($this->record->hasCommentsDisabled() && $this->record->isPublished())
            && (auth()->user()->isDeveloper() || auth()->user()->isAdministrator());
    }
}
