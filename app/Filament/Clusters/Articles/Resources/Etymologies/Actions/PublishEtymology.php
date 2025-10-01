<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Actions;

use App\Models\Etymology;
use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Represents a custom Filament Action designed to transition an `Etymology` record to the `EtymologyStatus::Published` state.
 *
 * This action provides a declarative interface for integrating a specific state transition into the Filament administration panel.
 * It encapsulates the logic for user authorization, visual presentation, user interaction (via a confirmation modal), and the execution of the underlying state change on the `Etymology` model.
 * The action leverages Filament's built-in features for process customization, confirmation dialogues, and notification management to ensure a robust and user-friendly experience for publishing etymology submissions.
 *
 * @property Etymology $record The Eloquent model instance of `Etymology` on which this action is being performed. This property is automatically resolved by Filament.
 * @package  App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions
 */
final class PublishEtymology extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the default, human-readable name (label) for this action.
     *
     * This static method retrieves the localized label directly from the `EtymologyStatus::Published` enum case, ensuring consistency with the defined etymology statuses across the application.
     * This label is typically used as the text displayed on the action button.
     *
     * @return string The default display name for the action, or `null` if not explicitly set.
     */
    public static function getDefaultName(): string
    {
        return EtymologyStatus::Published->getLabel();
    }

    /**
     * Configures the action's properties, behavior, and execution logic.
     *
     * This method is invoked during the action's initialization phase to set up its various operational aspects.
     * It begins by calling the parent `setUp()` method to inherit any foundational action configurations. Subsequently,
     * a policy-based authorization check is applied using `authorize('publish', $this->record)`,
     * which is crucial for ensuring that the currently authenticated user possesses the necessary permissions to transition the given `Etymology` record to the 'published' status.
     *
     * For visual presentation within the Filament interface, the action's icon is set to `heroicon-o-globe-europe-africa` and its color is configured as `success`, signaling a positive and public-facing operation.
     * To enhance data integrity and prevent accidental state changes, a user confirmation step is enforced before the action's core logic is executed.
     * The confirmation modal's appearance and content are extensively customized: its `modalIcon` is set, the `modalCloseButton` is disabled to require explicit user interaction, a `modalHeading` defines the title,
     * and a `modalDescription` provides a detailed explanation of the action's consequences, specifically that the etymology will become publicly available.
     * The text on the modal's primary confirmation button is customized via `modalSubmitActionLabel`.
     *
     * Notification titles for both successful and failed action completions are defined to provide clear feedback to the user.
     * Finally, the core execution logic of the action is registered within a closure passed to `action()`.
     * This closure attempts to transition the `$this->record`'s state to 'published' by invoking `->state()->transitionToPublished()`.
     * The `process()` method is then utilized to handle the execution of this state transition, automatically managing the dispatching of appropriate success or failure notifications based on the outcome.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('publish', $this->record);

        $this->icon('heroicon-o-globe-europe-africa');
        $this->color('success');

        $this->requiresConfirmation();

        $this->modalIcon('heroicon-o-globe-europe-africa');
        $this->modalCloseButton(false);
        $this->modalHeading(heading: __('etymology-resource.custom-actions.publish.modal.heading'));
        $this->modalDescription(description: __('etymology-resource.custom-actions.publish.modal.description'));
        $this->modalSubmitActionLabel(label: __('etymology-resource.custom-actions.publish.modal.submit-label'));

        $this->successNotificationTitle(title: __('etymology-resource.custom-actions.publish.notifications.success-title'));
        $this->failureNotificationTitle(title: __('etymology-resource.custom-actions.publish.notifications.failure-title'));

        $this->action(function (): void {
            if ($this->process(fn(): bool|int => $this->record->state()->transitionToPublished())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
