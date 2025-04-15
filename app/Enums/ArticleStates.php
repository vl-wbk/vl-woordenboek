<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Enum representing the possible states of an article within the application.
 *
 * This enum provides a structured way to manage the different stages an article can go through, from initial suggestion to being archived.
 * It implements the `HasLabel`, `HasIcon`, and `HasColor` interfaces from the Filament package, allowing for easy integration with Filament admin panels and other UI components.
 *
 * @implements HasLabel Provides a human-readable label for each state.
 * @implements HasIcon  Provides an icon for each state, enhancing visual representation.
 * @implements HasColor Provides a color associated with each state, for visual cues.
 *
 * @package App\Enums
 */
enum ArticleStates: int implements HasLabel, HasIcon, HasColor
{
    use Comparable;

    /**
     * The article is a new suggestion, meaning it has been recently submitted and requires review.
     */
    case New = 0;

    /**
     * The article is a draft, indicating it's a work in progress and not yet ready for publication.
     */
    case Draft = 1;

    /**
     * The article is awaiting approval from an editor or administrator before it can be published.
     */
    case Approval = 2;

    /**
     * The article has been published and is publicly visible on the website.
     */
    case Published = 3;

    /**
     * The article has been archived, meaning it's no longer actively displayed but is kept for historical or reference purposes.
     */
    case Archived = 4;

    /**
     * Returns the human-readable Dutch label for each state.
     * These labels are used in the user interface to clearly communicate the state of an article to users.
     *
     * @return string The Dutch label for the article state. Example: 'Suggestie' for the New state.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Suggestie',
            self::Draft => 'Klad versie',
            self::Approval => 'In afwachting',
            self::Published => 'Publicatie',
            self::Archived => 'Gearchiveerd',
        };
    }

    /**
     * Returns the color associated with the article state.
     * This color can be used to visually highlight articles based on their status in the UI.
     *
     * @return string The color name (e.g., 'gray').  Currently, all states use 'gray'.
     *                Consider customizing this to provide more informative visual cues.
     */
    public function getColor(): string
    {
        return 'gray';
    }

    /**
     * Returns the icon associated with the article state.
     * Icons provide a visual representation of the article's status, making it easier for users to quickly understand the state of an article.
     *
     * @return string The Heroicon name (e.g., 'heroicon-o-document-text').
     *                Currently, all states use the same icon.  Consider using
     *                different icons for different states to improve usability.
     */
    public function getIcon(): string
    {
        return 'heroicon-o-document-text';
    }
}
