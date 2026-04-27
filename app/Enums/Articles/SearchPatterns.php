<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasLabel;

/**
 * Enum: SearchPatterns
 *
 * This enum defines a standardized set of patterns for performing text-based searches within the dictionary application.
 * It's particularly useful when you need to offer users flexible ways to search for content, such as articles, products, or any textual data stored in your database.
 *
 * By implementing the `Filament\Support\Contracts\HasLabel` interface, each enum case automatically provides a human-readable and translatable label,
 * making it ideal for populating dropdowns or select fields in user interfaces (e.g., using FilamentPHP).
 *
 * Think of it as providing a "search mode" selector to your users.
 *
 * @package App\Enums\Articles
 */
enum SearchPatterns: string implements HasLabel
{
    /**
     * Represents a "contains" search pattern.
     *
     * Corresponds to SQL `LIKE '%query%'` for substring matching anywhere within the target string.
     * This implies a full table scan or reliance on full-text indexing for performance on large datasets.
     *
     * @note Performance considerations:
     *
     * When dealing with very large text fields and frequent "contains" searches,
     * consider external full-text search engines like Elasticsearch or Solr for optimal performance,
     * as native database `LIKE` operations can be resource-intensive.
     */
    case Contains = 'contains';

    /**
     * Represents a "starts with" search pattern.
     *
     * Corresponds to SQL `LIKE 'query%'` for prefix matching.
     * This pattern can leverage standard B-tree indexes for optimized performance if the indexed column is the target of the search.
     */
    case StartsWith = 'startsWith';

    /**
     * Represents an "ends with" search pattern.
     *
     * Corresponds to SQL `LIKE '%query'` for suffix matching.
     * This pattern typically prevents the use of standard B-tree indexes for optimization, often resulting in full table scans unless specific database features like reverse indexes or full-text search are employed.
     */
    case EndsWith = 'endsWith';

    /**
     * Represents an "exact match" search pattern.
     *
     * Corresponds to SQL `WHERE column = 'query'` for precise string equality.
     * This pattern is highly optimizable and can efficiently utilize standard B-tree indexes on the target column. Case sensitivity depends on the database's collation settings.
     *
     * @tip Remember that a well-chosen index can make a slow query run like lightning.
     */
    case Exact = 'exact';

    /**
     * Retrieves the localized display label for the enum case.
     *
     * This method fulfills the `HasLabel` contract, providing a user-friendly and translatable string representation for each search pattern.
     * The translation mechanism relies on Laravel's `trans()` helper, which resolves the label against the application's current locale.
     *
     * @return string The translated label corresponding to the enum case.
     */
    public function getLabel(): string
    {
        return match ($this) { // The `match` expression (PHP 8+) is used here for concise mapping of enum cases to their labels.
            self::Contains => __('components/search-form.patterns.contains'),
            self::StartsWith => __('components/search-form.patterns.startsWith'),
            self::EndsWith => __('components/search-form.patterns.endsWith'),
            self::Exact => __('components/search-form.patterns.exact'),
        };
    }
}
