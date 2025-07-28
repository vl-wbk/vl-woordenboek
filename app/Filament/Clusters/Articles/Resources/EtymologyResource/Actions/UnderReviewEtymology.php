<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions;

use App\Enums\Articles\EtymologyStatus;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Represents a custom Filament Action designed to transition an `Etymology` record to the `EtymologyStatus::UnderReview` state.
 *
 * This action provides a declarative interface for integrating a specific state transition into the Filament administration panel.
 * It encapsulates the logic for user authorization, visual presentation, user interaction (via a confirmation modal), and the execution of the underlying state change on the `Etymology` model.
 * The action leverages Filament's built-in features for process customization, confirmation dialogues, and notification management to ensure a robust user experience.
 *
 * @property \App\Models\Etymology $record  The Eloquent model instance of `Etymology` on which this action is being performed. This property is automatically resolved by Filament.
 * @package  App\Filament\Clusters\Articles\Resources\EtymologyResource\Actions
 */
final class UnderReviewEtymology extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the default, human-readable name (label) for this action.
     * This static method retrieves the localized label directly from the `EtymologyStatus::UnderReview` enum case, ensuring consistency with the defined etymology statuses across the application.
     *
     * @return string|null The default display name for the action, or `null` if not explicitly set.
     */
    public static function getDefaultName(): ?string
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

        $this->authorize('underReview', $this->record);

        $this->icon('heroicon-o-paper-airplane');
        $this->color('success');

        $this->requiresConfirmation();

        $this->modalIcon('heroicon-o-paper-airplane');
        $this->modalCloseButton(false);
        $this->modalHeading('Etymology in review plaatsen');
        $this->modalDescription('Bij het plaatsen van de etymologie in review. Zal deze ingezonden worden ter beoordeling. Onder deze status zal het niet meer mogelijk zijn om de etymologie te bewerken.');
        $this->modalSubmitActionLabel('Insturen');
        $this->modalCancelAction(false);

        $this->successNotificationTitle('De etymologie is ingestuurd ter beoordeling');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen.');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->record->state()->transitionToUnderReview())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }
}
