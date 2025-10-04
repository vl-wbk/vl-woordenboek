<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Actions;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Represents a custom Filament Action designed to transition an `Etymology` record to the `EtymologyStatus::UnderReview` state.
 *
 * This action provides a declarative interface for integrating a specific state transition into the Filament administration panel.
 * It encapsulates the logic for user authorization, visual presentation, user interaction (via a confirmation modal), and the execution of the underlying state change on the `Etymology` model.
 * The action leverages Filament's built-in features for process customization, confirmation dialogues, and notification management to ensure a robust user experience.
 */
final class UnderReviewEtymology extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the default, human-readable name (label) for this action.
     * This static method retrieves the localized label directly from the `EtymologyStatus::UnderReview` enum case, ensuring consistency with the defined etymology statuses across the application.
     *
     * @return string The default display name for the action, or `null` if not explicitly set.
     */
    public static function getDefaultName(): string
    {
        return EtymologyStatus::UnderReview->getLabel();
    }

    /**
     * Configures the action's properties, behavior, and execution logic.
     *
     * This method is invoked during the action's initialization phase to set up its various operational aspects.
     * It begins by calling the parent `setUp()` method to inherit any foundational action configurations.
     * Subsequently, a policy-based authorization check is applied using `authorize('underReview', $this->record)`, which is crucial for
     * ensuring that the currently authenticated user possesses the necessary permissions to transition the given `Etymology` record to the 'under review' status.
     *
     * For visual presentation within the Filament interface, the action's icon is set to `heroicon-o-paper-airplane` and its color is configured as `success`.
     * To enhance data integrity and prevent accidental state changes, a user confirmation step is enforced before the action's core logic is executed.
     * The confirmation modal's appearance and content are extensively customized: its `modalIcon` is set, the `modalCloseButton` is disabled to require explicit user interaction,
     * a `modalHeading` defines the title, and a `modalDescription` provides a detailed explanation of the action's consequences, such as preventing further edits.
     * Furthermore, the text on the modal's primary confirmation button is customized via `modalSubmitActionLabel`, and the `modalCancelAction` is also disabled.
     *
     * Notification titles for both successful and failed action completions are defined to provide clear feedback to the user.
     * Finally, the core execution logic of the action is registered within a closure passed to `action()`.
     * This closure attempts to transition the `$this->record`'s state to 'under review' by invoking `->state()->transitionToUnderReview()`.
     * The `process()` method is then utilized to handle the execution of this state transition,
     * automatically managing the dispatching of appropriate success or failure notifications based on the outcome.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('underReview');

        $this->icon('heroicon-o-paper-airplane');
        $this->color('success');

        $this->requiresConfirmation();

        $this->modalIcon('heroicon-o-paper-airplane');
        $this->modalCloseButton(false);
        $this->modalHeading(heading: __('etymology-resource.custom-actions.under-review.modal.heading'));
        $this->modalDescription(description: __('etymology-resource.custom-actions.under-review.modal.description'));
        $this->modalSubmitActionLabel(label: __('etymology-resource.custom-actions.under-review.modal.submit-label'));
        $this->modalCancelAction(false);

        $this->successNotificationTitle(title: __('etymology-resource.custom-actions.under-review.notifications.success-title'));
        $this->failureNotificationTitle(title: __('etymology-resource.custom-actions.under-review.notifications.failure-title'));

        $this->action(function (): void {
            if ($this->process(fn (Etymology $etymology): bool|int => $etymology->state()->transitionToUnderReview())) {
                $this->success();

                return;
            }

            $this->failure();
        });
    }
}
