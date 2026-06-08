<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

/**
 * Represents the initial 'Pending' state within the sentence lifecycle state machine.
 *
 * This implementation serves as a concrete strategy for the SentenceState abst ract class.
 * It encapsulates the UI-specific representation for sentences that have seen submitted
 * but are currently awaiting review or further processing.
 *
 * This class is utilized by the Filament administrative dashboard to provide clear, neutral
 * visual cues, distinguishing pending items from finalized states.
 */
final class Pending extends SentenceState
{
    /**
     * Determines the UI color variant for the state.
     * Uses 'gray' to provide a neutral visual status, indicating that the sentence is an intermediate, non-finalized state.
     *
     * @return string The color identifier used by the Filament theme engine.
     */
    public function getColor(): string
    {
        return 'gray';
    }

    /**
     * Retrieves the visual identifier (icon) for the state.
     *
     * Utilizes a chat bubble icon, symbolizing that the sentence is  currently in
     * a state of discussion or awaiting editorial input.
     *
     * @return BackedEnum The Heroicon instance representing the pending status.
     */
    public function getIcon(): BackedEnum
    {
        return Heroicon::ChatBubbleBottomCenterText;
    }

    /**
     * Provides the display label for the UI.
     *
     * returns the localized string 'openstaande contributie' (pending contribution), signaling to
     * administrators that this item requires attention.
     *
     * @return string The human-readable label.
     */
    public function getLabel(): string
    {
        return 'openstaande contributie';
    }
}
