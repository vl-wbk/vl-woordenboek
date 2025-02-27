<?php

declare(strict_types=1);

namespace App;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * UserTypes defines the role hierarchy and permissions within the Vlaams Woordenboek.
 *
 * This enum implements Filament's visual contracts to provide consistent styling and representation across the application interface.
 * Each user type corresponds to specific permissions and capabilities within the system, creating a clear hierarchical structure for content management and administration.
 *
 * @package App
 */
enum UserTypes: int implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    /**
     * Regular users who can contribute basic content to the dictionary.
     * These users have limited permissions focused on content creation.
     */
    case Normal = 0;

    /**
     * Content editors with intermediate privileges for reviewing and moderating dictionary entries submitted by normal users.
     */
    case Editor = 1;

    /**
     * Senior editorial role with advanced permissions for content management and oversight of regular editors.
     * Can approve major content changes.
     */
    case EditorInChief = 2;

    /**
     * Technical administrators with full system access for development and maintenance.
     * Can modify system code and configuration.
     */
    case Developer = 3;

    /**
     * System administrators with complete control over the application, including user management and global settings configuration.
     */
    case Administrators = 4;

    /**
     * Provides human-readable labels for each user type in Dutch.
     *
     * The method translates technical role names into user-friendly titles that appear throughout the interface.
     * These translations are further processed through the application's translation system for potential localization.
     */
    public function getLabel(): string
    {
        $usertype = match($this) {
            self::Normal => 'Invoerder',
            self::Editor => 'Redacteur',
            self::EditorInChief => 'Eindredacteur',
            self::Developer => 'Ontwikkelaar',
            self::Administrators => 'Administrator',
        };

        return trans($usertype);
    }

    /**
     * Assigns appropriate icons to each user type for visual identification.
     *
     * Uses Heroicons for consistent styling across the application.
     * Icons are chosen to reflect the role's primary function or level of access.
     * The outline variant maintains a cohesive visual language throughout the interface.
     */
    public function getIcon(): string
    {
        return match($this) {
            self::Developer => 'heroicon-o-code-bracket',
            self::Administrators => 'heroicon-o-key',
            self::Editor, self::EditorInChief => 'heroicon-o-pencil',
            self::Normal => 'heroicon-o-users',
        };
    }

    /**
     * Defines color schemes for visual distinction between user types.
     *
     * Colors reflect the level of system access and responsibility:
     * - Danger (red) for high-privilege roles
     * - Gray for editorial roles
     * - Success (green) for standard users
     *
     * This visual hierarchy helps quickly identify user capabilities in the interface.
     */
    public function getColor(): string
    {
        return match($this) {
            self::Developer, self::Administrators => 'danger',
            self::Editor, self::EditorInChief => 'gray',
            self::Normal => 'success',
        };
    }
}
