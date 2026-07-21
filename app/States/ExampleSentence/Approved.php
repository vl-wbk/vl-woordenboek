<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

/**
 *
 * Represents the finalized 'Approved' state within the sentence lifecycle state machine.
 *
 * This implementation acts as a concrete strategy for the SentenceState abstract class.
 * It encapsulates the UI specific representation for sentences that have passed all quality
 * assurance gates and are considered active/published.
 *
 * This class is designed to be consumed by Filament components to maintain visual
 * consistency across the administrative dashboard.
 */
final class Approved extends SentenceState
{
    /**
     * Determines the UI color variant for the state.
     *
     * Uses 'success' (typically green) to provide immediate visual feedback that the
     * sentence is in a terminal, valid state.
     *
     * @return string The color identifier used by the Filament theme engine.
     */
    public function getColor(): string
    {
        return 'success';
    }

    /**
     * Retrieves the viaul identifier (icon) for the state.
     * Utilizes a document-check icon to represent completion and verification to the end-user.
     *
     * @return BackedEnum The heroicon instance representing the approved status.
     */
    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedDocumentCheck;
    }

    /**
     * Provides the display for the UI.
     *
     * Returns the localized string 'Gepubliceerd' (published), indicating that the sentence
     * is live withing the application content.
     *
     * @return string The human-readable label.
     */
    public function getLabel(): string
    {
        return 'gepubliceerd';
    }
}
