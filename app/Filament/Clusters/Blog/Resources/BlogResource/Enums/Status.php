<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Manages the lifecycle states of blog posts within the application.
 *
 * In our content management workflow, blog posts move through different states as they progress from initial creation to public visibility.
 * Each post begins its life as a Draft, allowing authors to work on content privately without exposing it to site visitors.
 * When the content meets quality standards and is ready for the public eye, it transitions to the Published state.
 *
 * This enumeration seamlessly integrates with Filament's admin interface by implementing several key interfaces.
 * The HasLabel interface ensures each status displays an appropriate, translated name in the UI.
 * HasColor provides visual distinction through color-coding - drafts are marked with cautionary orange, while published posts show a reassuring green.
 * HasIcon supplements these colors with intuitive icons - a pencil for drafts in progress, and a globe for published content visible to the world.
 *
 * To facilitate easy status comparisons in your code, this enum includes the
 * Comparable trait. Rather than direct equality checks, you can write more expressive conditions.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Enums
 */
enum Status: int implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    /**
     * A draft post that is still being worked on.
     *
     * Draft posts are only visible to authenticated users with appropriate permissions.
     * They allow content to be prepared and reviewed before making it public.
     */
    case Draft = 0;

    /**
     * A published post that is publicly visible.
     *
     * Published posts appear on the public website and can be viewed by all visitors.
     * They represent the final, approved version of the content.
     */
    case Published = 1;

    /**
     * Provides a human-readable label for the current status in the user's language.
     *
     * This method converts our internal status values into user-friendly text that appears throughout the admin interface.
     *
     * The labels are defined in Dutch but go through our translation system, allowing for:
     * - Consistent status terminology across the interface
     * - Future internationalization if needed
     * - Clear communication of content state to users
     *
     * Current translations:
     * - Draft → "Klad versie" (Draft version)
     * - Published → "Gepubliceerd" (Published)
     *
     * The resulting label is used in:
     * - Status badges on post listings
     * - Filter dropdowns in the admin panel
     * - Status indicators on edit forms
     */
    public function getLabel(): string
    {
        $label = match ($this) {
            self::Draft => 'Klad versie',
            self::Published => 'Gepubliceerd',
        };

        return trans($label);
    }

    /**
     * Retrieves the translated display label for the current status.
     *
     * These labels appear throughout the admin interface to indicate the current state of a blog post.
     * They are automatically translated based on the user's selected language.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Published => 'success',
        };
    }

    /**
     * Associates a meaningful icon with each status state.
     *
     * Our admin interface uses icons to provide quick visual feedback about a post's status.
     *
     * We specifically chose icons that intuitively represent each state:
     * - For drafts, we use a pencil icon to indicate ongoing editing
     * - For published content, we use a globe to show public visibility
     *
     * These icons come from the Heroicons library (outline variant) to maintain consistent styling throughout the admin interface.
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-pencil-square',
            self::Published => 'heroicon-o-globe-europe-africa',
        };
    }

    /**
     * Checks if the current status represents published content.
     *
     * This helper method provides a semantic way to check if content is publicly visible.
     * Instead of comparing status values directly, this method makes your code more readable and maintainable.
     */
    public function isPublished(): bool
    {
        return $this->is(self::Published);
    }

    /**
     * Checks if the current status represents draft content.
     *
     * This helper method provides a clear way to check if content is still in the draft state.
     * Using this method instead of direct status comparisons makes your code more expressive and easier to understand.
     */
    public function isDraft(): bool
    {
        return $this->is(self::Draft);
    }
}
