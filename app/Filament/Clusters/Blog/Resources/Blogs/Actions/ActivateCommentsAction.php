<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Blogs\Actions;

use App\Models\{Blog, Comment};
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * ActivateCommentsAction
 * 
 * This class defines a specialized Filament action used to toggle the comment section visibility on for a specific Blog resource. 
 * It encapsulates the entire lifecycle of the action, including UI presentation, authorization, user confirmation, and execution logic.
 * 
 * Behavioral Flow:
 * 1. Checks the `activate-comments` permission for the authenticated user.
 * 2. Triggers a confirmation modal to prevent accidental activation.
 * 3. Updates the `comments_enabled` boolean in the database.
 * 4. Dispatches feedback notifications based on the result of the database transaction.
 * 
 * @property Comment $record The database entity from the given comment.
 * 
 * @package App\Filament\Clusters\Blog\Resources\Blogs\Actions
 */
final class ActivateCommentsAction extends Action
{
    use CanCustomizeProcess;

    /** 
     * The icon identifier used for both the action button and the confirmation modal.
     * 
     * @var string $actionIcon
     */
    private string $actionIcon = 'tabler-message';

    /**
     * Returns the internal name of the action. 
     * This is used by Filament to register the action in the component registry. 
     *
     * @return string Thee unique identifier for this action.
     */
    public static function getDefaultName(): string
    {
        return 'activate-comments';
    }

    /**
     * Set up the initial configuration and execution logic for the action.
     * 
     * This method defines the visual cues (labels/icons), security gates (authorization),
     * and the closure that handles the actual state change on the Blog model.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Customize the action's appearance.
        $this->icon(icon: $this->actionIcon);
        $this->label('Reacties inschakelen');
        $this->color('gray');
        $this->authorize('activate-comments');

        // Configure visual attributes and authorization requirements.
        $this->requiresConfirmation();

        // Define the modal layout and localized messaging for the confirmation dialog.
        $this->modalIcon(icon: $this->actionIcon);
        $this->modalIconColor('primary');
        $this->modalCloseButton(false);
        $this->modalHeading('Reacties inschakelen');
        $this->modalDescription('Indien u de reacties inschakelde kunnen gebruikers reageren op het nieuwsartikel. Bent u zeker dat u ze wilt inschakelen?');

        // Configure standard notification feedback for the end-user.
        $this->successNotificationTitle('Reacties zijn ingeschakeld');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        // Execute the database update within a process-managed closure.
        $this->action(function (): void {
            if ($this->process(fn(Blog $blog): bool => $blog->update(attributes: ['comments_enabled' => true]))) {
                $this->success(); // Triggers the success notification and UI updates.
                return;
            }

            // Fallback for failed transactions or unexpected state errors.
            $this->failure();
        });
    }
}
