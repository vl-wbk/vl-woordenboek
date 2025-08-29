<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;

/**
 * Represents a custom Filament Action designed to transition an `Etymology` record to the `EtymologyStatus::Rejected` state.
 *
 * This action provides a declarative interface for integrating a specific state transition into the Filament administration panel.
 * It encapsulates the logic for user authorization, visual presentation, user interaction (via a confirmation modal that includes a mandatory input field for a rejection reason),
 * and the execution of the underlying state change on the `Etymology` model.
 * The action leverages Filament's built-in features for process customization, confirmation dialogues, and notification management to ensure a robust and auditable user experience
 * for rejecting etymology submissions.
 *
 * @property \App\Models\Etymology $record The Eloquent model instance of `Etymology` on which this action is being performed. This property is automatically resolved by Filament.
 * @package  App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions
 */
final class RejectEtymology extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the default, human-readable name (label) for this action.
     *
     * This static method retrieves the localized label directly from the `EtymologyStatus::Rejected` enum case, ensuring consistency with the defined etymology statuses across the application.
     * This label is typically used as the text displayed on the action button.
     *
     * @return string The default display name for the action, or `null` if not explicitly set.
     */
    public static function getDefaultName(): string
    {
        return EtymologyStatus::Rejected->getLabel();
    }

    /**
     * Configures the action's properties, behavior, and execution logic.
     *
     * This method is invoked during the action's initialization phase to set up its various operational aspects.
     * It begins by calling the parent `setUp()` method to inherit any foundational action configurations.
     * Subsequently, a policy-based authorization check is applied using `authorize('reject', $this->record)`,
     * which is crucial for ensuring that the currently authenticated user possesses the necessary permissions to transition the given `Etymology` record to the 'rejected' status.
     *
     * For visual presentation within the Filament interface, the action's icon is set to `heroicon-o-hand-thumb-down` and its color is configured as `danger`,
     * signaling a potentially destructive or irreversible operation. To enhance data integrity and prevent accidental state changes, a user confirmation step is enforced before the
     * action's core logic is executed. The confirmation modal's appearance and content are extensively customized: its `modalIcon` is set, the `modalCloseButton` is
     * disabled to require explicit user interaction, a `modalHeading` defines the title, and a `modalDescription` provides a detailed explanation of the action's consequences.
     * The text on the modal's primary confirmation button is customized via `modalSubmitActionLabel`.
     *
     * Critically, this action includes a form within its modal, specifically a `Textarea` component named 'reason'.
     * This field is labeled 'Reden van de archivering' (though contextually it should be 'Reden van de afwijzing' for rejection), includes a placeholder, sets a row count, and is marked as `required()`,
     * ensuring that a justification for the rejection is always provided by the user.
     *
     * Notification titles for both successful and failed action completions are defined to provide clear feedback to the user.
     * Finally, the core execution logic of the action is registered within a closure passed to `action()`.
     * This closure attempts to transition the `$this->record`'s state to 'rejected' by invoking `->state()->transitionToRejected($data['reason'])`, passing the collected reason.
     * The `process()` method is then utilized to handle the execution of this state transition, automatically managing the dispatching of appropriate success or failure notifications based on the outcome.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('reject', $this->record);

        $this->icon('heroicon-o-hand-thumb-down');
        $this->color('danger');

        $this->requiresConfirmation();

        $this->modalIcon('heroicon-o-hand-thumb-down');
        $this->modalCloseButton(false);
        $this->modalHeading(heading: __('etymology-resource.custom-actions.reject.modal.heading'));
        $this->modalDescription(description: __('etymology-resource.custom-actions.reject.modal.description'));
        $this->modalSubmitActionLabel(label: __('etymology-resource.custom-actions.reject.modal.submit-label'));

        $this->form([
            Textarea::make('reason')
                ->label(label: __('etymology-resource.custom-actions.reject.form.label'))
                ->placeholder(placeholder: __('etymology-resource.custom-actions.reject.modal.form.placeholder'))
                ->rows(5)
                ->required(),
        ]);

        $this->successNotificationTitle(title: __('etymology-resource.custom-actions.reject.notifications.success-title'));
        $this->failureNotificationTitle(title: __('etymology-resource.custom-actions.reject.notifications.failure-title'));

        $this->action(function (): void {
            if ($this->process(fn(array $data): bool|int => $this->record->state()->transitionToRejected($data['reason']))) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
