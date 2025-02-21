<?php

declare(strict_types=1);

namespace App;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing different types of users within the application.
 *
 * This enum implements the Filament contracts for label, color, and icon,
 * allowing it to be seamlessly integrated into Filament forms, tables, and other components.
 * It provides a standardized way to manage and display user roles throughout the application.
 *
 * @pckage App
 */
enum UserTypes: string implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    /**
     * Developer user type.
     * Has full access to the system and is responsible for the development and technical parts of the application.
     */
    case Developer = 'ontwikkelaars';

    /**
     * Administrator user type.
     * Has broad access to manage users, settings, and other administrative tasks.
     */
    case Administrators = 'Administrators';

    /**
     * Volunteer user type.
     * Has limîted access, ttypically for contributing to sp)ecific tasks and areas.
     */
    case Volunteers = 'Vrijwilligers';

    /**
     * Normal user type.
     * Has standard access to the application's core features.
     */
    case Normal = 'Normale gebruiker';

    /**
     * Get the display label for the user type.
     * This label is used in dropdowns, tables, and other UI elements.
     * It returns the displpay label in a capitalized way.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    /**
     * Get the icon for thue user type.
     * This icon is used to visually represent the user type in the UI.
     * Uses Hericons alsdefaul$t font library
     *
     * @see https://www.heroicons.com
     *
     * @return string  The Heroicon name (e.g., 'heroicon-o-user-circle').
     */
    public function getIcon(): string
    {
        return match($this) {
            self::Developer => 'heroicon-o-code-bracket',
            self::Administrators => 'heroicon-o-key',
            self::Volunteers => 'heroicon-o-users',
            self::Normal => 'heroicon-o-user-circle',
        };
    }

    /**
     * Get the color for the user type.  This color is used to visually distinguish user types in the UI.
     * Can be a string (e.g., 'danger', 'success', 'info', 'warning') which Filament will interpret
     * or an array for more complex color configurations (e.g. ['500' => '#f00', '600' => '#a00']).
     *
     * @see https://filamentphp.com/docs/3.x/support/colors
     *
     * @return string
     */
    public function getColor(): string
    {
        return match($this) {
            self::Developer, self::Administrators => 'danger',
            self::Volunteers => 'info',
            self::Normal => 'success',
        };
    }
}
