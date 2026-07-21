<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

/**
 * Represents the Rejected state within the sentence lifecycle state machine.
 *
 * This implementation acts as a concrete strategy for the SentenceState abstract class.
 * It encapsulates the UI specific representation for sentences that have failed validation,
 * quality review, or have been explicitly declined by an administrator.
 *
 * This class ensures that rejected items are visually distinct within the Filament dashboard,
 * facilitating quick identification of content requiring corrective action.
 */
final class Rejected extends SentenceState
{
    /**
     * Determines the UI color varian for the state.
     * Uses 'danger' (Typically red) to provide an immediate visual alert that the sentence has been rejected or contains errors.
     *
     * @return string The color identifier used by the Filament them engine.
     */
    public function getColor(): string
    {
        return 'danger';
    }

    /**
     * Retrieves the visual identifier (icon) for the state.
     * Utilizes the X-mark icon to clearly symbolize that the entity has been invalidated or stopped in the current workflow.
     *
     * @return BackedEnum The Heroicon instance representing the rejection status.
     */
    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedXMark;
    }

    /**
     * Provides the display label for the UI.
     *
     * Returns the localized string 'Afgewezen', clearly indicating that the sentence is
     * no longer eligible for the published lifecycle.
     *
     * @return string The human-readable label.
     */
    public function getLabel(): string
    {
        return 'Afgewezen';
    }
}
