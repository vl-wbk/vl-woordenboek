<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Represents a boolean feedback response, typically "Ja" (Yes) or "Nee" (No).
 *
 * This enum provides a structured way to handle binary feedback, associating each case with a human-readable label, a specific color, and an icon for enhanced visual representation in user interfaces.
 * It implements the `HasLabel`, `HasColor`, and `HasIcon` interfaces from Filament, making it directly compatible with Filament's form fields, tables, and other UI components that require these display properties.
 *
 * @package App\Enums
 */
enum FeedbackTrueFalse: string implements HasLabel, HasColor, HasIcon
{
    /**
     * Represents a positive feedback response, typically "Ja" (Yes).
     * Associated with a 'success' color and a checkmark icon.
     */
    case true =  'Ja';

    /**
     * Represents a negative feedback response, typically "Nee" (No).
     * Associated with a 'danger' color and an 'x' circle icon.
     */
    case false = 'Nee';

    /**
     * Retrieves the human-readable label for the current feedback status.
     * This method directly returns the string value of the enum case, which is already localized (e.g., 'Ja' or 'Nee').
     *
     * @return string The localized label of the feedback status.
     */
    public function getLabel(): string
    {
        return $this->value;
    }

    /**
     * Retrieves the color associated with the current feedback status.
     * This method maps the enum case to a Filament-compatible color string, typically used for visual styling in the UI (e.g., 'success' for true, 'danger' for false).
     *
     * @return string The color string for the feedback status.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::true => 'success',
            self::false => 'danger',
        };
    }

    /**
     * Retrieves the icon associated with the current feedback status.
     * This method maps the enum case to a Heroicons icon string, used for visual representation in the UI (e.g., a checkmark for true, an 'x' for false).
     *
     * @return string The icon string for the feedback status.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::true => 'heroicon-o-check-circle',
            self::false => 'heroicon-o-x-circle',
        };
    }
}
