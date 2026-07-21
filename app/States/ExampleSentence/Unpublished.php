<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

/**
 * Represents the 'Unpublished' state within the sentence lifecycle.
 *
 * This implementation acts as a concrete strategy for the `SentenceState` abstract class.
 * It encapsulates the UI-specific representation for sentences that were previously
 * active but have been explicitly withdrawn from public view.
 *
 * This class ensures that offline content is visually identified in the Filament
 * dashboard, allowing administrators to differentiate between content requiring
 * editorial review versus content that has been intentionally taken down.
 *
 * @package App\States\ExampleSentence
 */
final class Unpublished extends SentenceState
{
    /**
     * Determines the UI color variant for the state.
     * * Uses 'warning' (typically amber/orange) to signify a state of
     * suspension or reduced visibility that requires attention.
     *
     * @return string The color identifier used by the Filament theme engine.
     */
    public function getColor(): string
    {
        return 'warning';
    }

    /**
     * Retrieves the visual identifier (icon) for the state.
     * Utilizes the 'eye-slash' icon to intuitively convey that the content is currently hidden or inaccessible to end-users.
     *
     * @return BackedEnum The Heroicon instance representing the unpublished status.
     */
    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedEyeSlash;
    }

    /**
     * Provides the display label for the UI.
     * Returns the localized string 'Offline gehaald', signaling that the  sentence is currently withdrawn from active production.
     *
     * @return string The human-readable label.
     */
    public function getLabel(): string
    {
        return 'Offline gehaald';
    }
}
