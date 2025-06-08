<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;

/**
 * Class DeactivateCommentsAction
 *
 * This class defines a custom Filament Action desgined to deactivate the commenting functionality for a specific blog post record.
 * It extends Filament's base 'Action' class and utilizs the 'CanCustomizeProcess' trait to allow for flexible execution flow.
 *
 * This primary purpose of this action is to provide an administrative tool within the Filament interface to toggle off comments for a selected blog entry,
 * ensuring proper authorization checks and user confirmation before execution.
 *
 * @package App\Filament\lusters\Blog\Resources\BlogResource\Actions
 */
final class DeactivateCommentsAction extends Action
{
    use CanCustomizeProcess;

    /**
     * The icon string used to represent this action visually.
     *
     * This icon is displayed on the action button itself within the Filament UI, and it is also utilized withing the confirmation model to provide a consistent visual cue to the user regarding the nature of the action.
     * The value 'tabler-message-off' corresponds to an icon from the Tabler Icons library, specifically chosen to signify the disabling or deactivation of comments or messages.
     * This helps users quickly understand the action's intent.
     */
    private string $actionIcon = 'tabler-message-off';

    /**
     * Get the unique, internal name for this Filament action.
     *
     * This static method is crucial for Filament to identify and register the action within the system.
     * It should be a consistent and descriptive string, typically in kebab-case, ensuring it is unique among actions within the same context.
     *
     * @return string The default, unique name for this action.
     */
    public static function getDefaultName(): string
    {
        return 'deactivate-comments';
    }

    /**
     * Set up the action's properties, appearance, behavior, and execution logic.
     *
     * This protected method is called by Filament during the action's initialization.
     * It's where all the declarative configuration for the action takes place, including visual styling, visibility rules, user interaction flow (e.g., confirmation), notification messages, and the ultimate operation to be performed.
     * This method is central to defining how the action looks, when it appears, and what it does.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Customize the action's appearance.
        $this->icon(icon: $this->actionIcon);
        $this->label('Reacties uitschakelen');
        $this->color('danger');
        $this->visible(condition: $this->canPerformTheAction());

        // Require user confirmation before proceeding.
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

    /**
     * Determines whether the current action can be performed by the authenticated user on the specific record.
     *
     * This private method encapsulates the business logic for action authorization and relevance.
     * The action is made visible and executable only under two strict conditions, ensuring proper access control and user experience:
     *
     * 1. The associated record (e.g., a blog post) must currently have its comments enabled. This prevents showing the "Deactivate Comments" action if comments are already disabled.
     * 2. The currently authenticated user must possess either the 'developer' role or the 'administrator' role. This ensures that only privileged users can modify comment settings for blog posts.
     *
     * @return bool True if the action is permitted and relevant in the current context, false otherwise.
     */
    private function canPerformTheAction(): bool
    {
        return $this->record->hasCommentsEnabled()
            && (auth()->user()->isDeveloper() || auth()->user()->isAdministrator());
    }
}
