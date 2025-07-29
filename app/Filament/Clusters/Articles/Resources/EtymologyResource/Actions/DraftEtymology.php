<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\Action;

/**
 * Represents a custom Filament Action designed to transition an `Etymology` record
 * to the `EtymologyStatus::Draft` state.
 *
 * This action provides a declarative interface for integrating a specific state transition into the Filament administration panel.
 * It encapsulates the logic for user authorization, visual presentation, user interaction (via a confirmation modal), and the execution of the underlying state change on the `Etymology` model.
 * The action leverages Filament's built-in features for process customization, confirmation dialogues, and notification management to ensure a robust and
 * user-friendly experience for managing etymology submissions, specifically placing them into a non-public, editable draft state.
 *
 * @property \App\Models\Etymology $record The Eloquent model instance of `Etymology`
 * on which this action is being performed. This property
 * is automatically resolved by Filament.
 * @package category
 */
final class DraftEtymology extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the default, human-readable name (label) for this action.
     *
     * This static method retrieves the localized label directly from the `EtymologyStatus::Draft` enum case, ensuring consistency with the defined etymology statuses across the application.
     * This label is typically used as the text displayed on the action button.
     *
     * @return string|null The default display name for the action, or `null` if not explicitly set.
     */
    public static function getDefaultName(): ?string
    {
        return EtymologyStatus::Draft->getLabel();
    }

    /**
     * Configures the action's properties, behavior, and execution logic.
     *
     * This method is invoked during the action's initialization phase to set up its various operational aspects.
     * It begins by calling the parent `setUp()` method to inherit any foundational action configurations. Subsequently, a policy-based authorization check is applied using `authorize('draft', $this->record)`,
     * which is crucial for ensuring that the currently authenticated user possesses the necessary permissions to transition the given `Etymology` record to the 'draft' status.
     *
     * For visual presentation within the Filament interface, the action's icon is set to `heroicon-o-pencil-square` and its color is configured as `warning`, signaling a state of modification or non-finality.
     * To enhance data integrity and prevent accidental state changes, a user confirmation step is enforced before the action's core logic is executed.
     * The confirmation modal's appearance and content are extensively customized: its `modalIcon` is set, the `modalCloseButton` is disabled to require explicit user interaction, a `modalHeading` defines the title,
     * and a `modalDescription` provides a detailed explanation of the action's consequences, specifically that the etymology will no longer be publicly accessible. The text on the  modal's primary confirmation button is customized via `modalSubmitActionLabel`.
     *
     * Notification titles for both successful and failed action completions are defined to provide clear feedback to the user.
     * Finally, the core execution logic of the action is registered within a closure passed to `action()`. This closure attempts to transition the `$this->record`'s state to 'draft' by invoking`->state()->transitionToDraft()`.
     * The `process()` method is then utilized to handle the execution of this state transition, automatically managing the dispatching of appropriate success or failure notifications based on the outcome.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('draft', $this->record);

        $this->icon('heroicon-o-pencil-square');
        $this->color('warning');

        $this->requiresConfirmation();

        $this->modalIcon('heroicon-o-pencil-square');
        $this->modalCloseButton(false);
        $this->modalHeading('Gegevens in onderhoud plaatsen');
        $this->modalDescription('U staat op het punt om de etymologische gegevens in onderhoud te plaatsen. In deze fase zullen de gegevens niet publiekelijk raadpleegbaar zijn. Bent u zeker dat u dit wilt doen?');
        $this->modalSubmitActionLabel('Ja, ik ben zeker');

        $this->successNotificationTitle('De etymologische gegevens zijn nu in onderhoud');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');

        $this->action(function (): void {
            if ($this->process(fn(array $data): bool => $this->record->state()->transitionToDraft())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
