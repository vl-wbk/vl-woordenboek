<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * ArticleStates defines the possible lifecycle states of an article.
 *
 * This enumeration assigns specific integer values to each state an article can be in, such as "New" for a suggestion,
 * "Draft" for an in-progress article, "Approval" for articles awaiting review, "Published" for released content,
 * "Archived" for outdated entries, and "ExternalData" for articles based on external information.
 *
 * By implementing HasLabel, HasIcon, and HasColor interfaces, this enum provides Filament-compatible methods that return a
 * human-readable Dutch label, an associated icon, and a display color for each state. The getLabel() method uses a match
 * expression to map each state to its Dutch label. The getColor() method returns a uniform color ("gray") for all states, and
 * the getIcon() method returns the same document text icon for every state.
 *
 * The Comparable trait is included to enable direct comparisons between state instances, which is essential for enforcing
 * business rules and ordering the articles based on their state.
 *
 * This design ensures that article state management is consistent, type-safe, and seamlessly integrated with the Filament UI,
 * facilitating a clear and maintainable approach to tracking an article’s progress through its lifecycle.
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
    case ExternalData = 5;

    /**
     * Returns the human-readable Dutch label for each state.
     * For each possible state, this method returns an appropriate label that can be used in user interfaces to describe the article's current status.
     *
     * @return string The label corresponding to the state.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Suggestie',
            self::Draft => 'Klad versie',
            self::Approval => 'In afwachting',
            self::Published => 'Publicatie',
            self::Archived => 'Gearchiveerd',
            self::ExternalData => 'Externe data',
        };
    }

    /**
     * Returns the display color associated with the state.
     * All states currently return the same color, which supports a uniform appearance in the user interface components that utilize this enum.
     *
     * @return string The color value (in this case, "gray").
     */
    public function getColor(): string
    {
        return 'gray';
    }

    /**
     * Returns the icon associated with the state.
     * The icon is used by Filament components to visually represent the article state in the UI.
     *
     * @return string The identifier of the icon (here, a document-text icon).
     */
    public function getIcon(): string
    {
        return 'heroicon-o-document-text';
    }
}
