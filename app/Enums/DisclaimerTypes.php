<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * DisclaimerTypes is a PHP enum that defines various types of disclaimers used within the application.
 * Each type is associated with a specific integer value, a display icon, a human-readable label, and a corresponding CSS class for frontend alert styling.
 *
 * This enum implements Filament's `HasIcon` and `HasLabel` contracts, which allows it to be directly used in Filament components (like select fields or tables) to automatically display the correct icon and label for each enum case.
 * It also provides a method to get a specific CSS class for frontend alerts, enabling dynamic styling based on the disclaimer type.
 *
 * @see HasIcon     - For defining an icon for each enum case.
 * @see HasLabel    - For defining a label for each enum case.
 *
 * @package App\Enums
 */
enum DisclaimerTypes: int implements HasIcon, HasLabel
{
    case Default = 0;   // Represents a standard, informational disclaimer.
    case Warning = 1;   // Represents a warning disclaimer, indicating caution or less severe issues.
    case Danger = 2;    // Represents a danger disclaimer, indicating critical or severe issues.

    /**
     * Returns the appropriate Heroicons SVG icon for each disclaimer type.
     *
     * This method is part of the `HasIcon` contract implementation.
     * It uses a `match` expression to return a specific icon string based on the current enum case.
     * These icons are typically used in Filament admin panels or other UI components to visually represent the disclaimer type.
     *
     * @return string The Heroicons icon class string (e.g., 'heroicon-s-information-circle').
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Default => 'heroicon-s-information-circle',
            self::Warning => 'heroicon-s-exclamation-triangle',
            self::Danger => 'heroicon-s-hand-raised',
        };
    }

    /**
     * Returns the human-readable label for each disclaimer type.
     *
     * This method is part of the `HasLabel` contract implementation.
     * It provides a descriptive string for each enum case, which is suitable for display in user interfaces, such as dropdowns, tables, or form labels in Filament.
     * The labels are in Dutch.
     *
     * @return string The human-readable label (e.g., 'Standaard disclaimer').
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Default => 'Standaard disclaimer',
            self::Warning => 'Waarschuwings disclaimer',
            self::Danger => 'Gevaren disclaimer',
        };
    }

    /**
     * Returns the corresponding CSS class string for frontend alert styling.
     *
     * This method provides a direct mapping from the disclaimer type to a CSS class, which can be used in frontend views to dynamically apply styling
     * (e.g., Bootstrap or Tailwind CSS alert classes) to display the disclaimer with appropriate visual feedback (info, warning, danger).
     *
     * @return string The CSS class string (e.g., 'alert alert-info').
     */
    public function getFrontendAlertClass(): string
    {
        return match ($this) {
            self::Default => 'alert alert-info',
            self::Warning => 'alert alert-warning',
            self::Danger => 'alert alert-danger',
        };
    }
}
