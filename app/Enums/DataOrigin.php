<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Represents the origin of data for an article within the application.
 *
 * This enum defines distinct sources from which article data can originate.
 * It's crucial for tracking data provenance, aiding in data quality management, and understanding the context of information within the system.
 *
 * Implementing the `HasLabel` interface, as required by Filament, ensures that each enum case can provide a user-friendly, localized label.
 * This label is typically used in user interfaces (e.g., forms, tables, filters) to present clear and understandable options to the end-user, enhancing the overall user experience.
 *
 * @package App\Enums
 */
enum DataOrigin: int implements HasLabel
{
    /**
     * Indicates that the data for the article was imported or retrieved from an **external system or third-party source**.
     *
     * This might include data fetched from APIs, legacy databases, or other content management systems.
     * Data from external sources often undergoes a specific validation or transformation process upon ingestion.
     */
    case External = 0;

    /**
     * Indicates that the data for the article was **suggested or manually entered**
     * by a user, editor, or administrator within the application itself.
     *
     * Data originating from suggestions typically goes through an internal review and approval workflow before being fully integrated.
     * This helps maintain data quality and consistency.
     */
    case Suggestion = 1;

    /**
     * Get the human-readable, localized label for the current data origin case.
     *
     * This method provides a user-friendly string representation for each `DataOrigin` enum value.
     * These labels are designed for display in the user interface, such as dropdowns, table columns, or notification messages,
     * ensuring that users can easily understand the source of the data.
     *
     * The `match` expression is used here for its conciseness and exhaustiveness,
     * ensuring that every case of the enum is explicitly handled.
     *
     * @return string The translated, human-readable label corresponding to the enum case.
     */
    public function getLabel(): string
    {
        $label = match ($this) {
            self::External => 'Externe bron',
            self::Suggestion => 'Suggestie',
        };

        return trans($label);
    }
}
