<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Casts the 'source_citation' attrbiutes as the complete, enhanced source citation, including HTML tags.
 *
 * This class serves as a Value Object accessor, dynamically constructing the citation string by retrieving required
 * fields from the primary Model (e.g., page reference) and its eagerly loaded or lazily loaded 'referenceWork'
 * relationship (e.g., name, publisher). The logic is highly sensitive to the presence of data, ensuring accurate
 * punctuation and adhering to a consistent format.
 *
 * @package App\Casts
 */
final class SourceCitation implements CastsAttributes
{
    /**
     * Casts the stored model value to the desired display value (the citation string).
     *
     * As this is a virtual attribute, the value is fully calculated based on relationships and specific model
     * properties, effectively providing a dynamic computed property.
     *
     * @param  Model  $model       The model utilizing the cast (e.g., Source).
     * @param  string $key         The name of the attribute being cast (e.g., 'source_citation').
     * @param  mixed  $value       The raw value from the database (ignored, but typically null).
     * @param  array  $attributes  All model attributes.
     * @return string              The resulting HTML formatted source citation, Returns also a fallback message.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $this->buildCitationString($model);
    }

    /**
     * Prepares the cast valie for storage.
     *
     * Since this is a virtual/computed read-only attribute, the incoming value is
     * returned immediately without modification, as no storage operation is required.
     *
     * @param  Model  $model       The model being persisted.
     * @param  string $key         The attribute name.
     * @param  mixed  $value       The value to be set.
     * @param  array  $attributes  All attributes.
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Core function to construct the source citation string.
     *
     * This method orchestrates the retrieval of components, applies styling (<em>),
     * and enforces consistent punctuation (periods and commas) to meet the desired format.
     *
     * Format (example):
     * <em>Title of the Work</em> (ABBR). Publisher, 2024. Chapter X, p. 123.
     *
     * @param  Model $model The primary model containing sectional data (e.g., page number).
     * @return string       The complete and HTML-formatted source citation.
     */
    protected function buildCitationString(Model $model): string
    {
        $work = $this->getReferencedWork($model);

        if (!$work) {
            return 'Broninformatie niet beschikbaar.';
        }

        // 1. Gather and Format Components
        $name = $work->name;
        $abbreviation = $work->abbreviation;
        $publisher = $work->publisher;

        // Format the name in italics (<em>) as required for titles
        $formattedName = "<em>{$name}</em>";

        // Determine the year, defaulting to 'n.d.' (no date) if missing
        $year = $work->published_at ? $work->published_at->format('Y') : 'z.j.';

        // Retrieve and sanitize sectional data from the primary model
        $section = trim((string) $model->container_section);
        $page = trim((string) $model->page_reference);

        // 2. Build the citation in sequential steps with optimized punctuation.
        // Start with the italicized name
        $citation = $formattedName;

        if ($abbreviation) { // Add abbreviation, if present, in parentheses, appended to the name
            $citation .= " ({$abbreviation})";
        }

        // Add the publication details block (Publisher, Year), separated by a period
        $citation .= ". {$publisher}, {$year}";

        if ($section) { // Add section, if present (separated by a period)
            $citation .= ". {$section}";
        }

        // Add Page/Location, separated by a comma from the previous block
        if ($page) {
            // Use comma as separator if any publishing detail (section or publisher) precedes it.
            if ($section || $publisher) {
                $citation .= ", {$page}";
            } else { // Fallback for an unlikely scenario where only the page reference exixts
                $citation .= ". {$page}";
            }
        }

        $citation = trim($citation);

        // 3. Finalize
        // Ensure the string ends with a single period and is properly trimmed.
        if (!str_ends_with($citation, '.')) {
            $citation .= '.';
        }

        return $citation;
    }

    /**
     * Retrieves the related ReferenceWork model instance.
     *
     * This method implements a defensive loading strategy:
     * 1. It checks if the 'referenceWork' relationship is already loaded (eager-loaded).
     * 2. If not loaded, it lazily loads the first related record from the database.
     *
     * @param  Model $model  The model that contains the 'referenceWork' relationship.
     * @return Model|null    The related ReferenceWork model instance or null if the relationship is empty.
     */
    private function getReferencedWork(Model $model): ?Model
    {
        return $model->relationLoaded('referenceWork')
            ? $model->referenceWork
            : $model->referenceWork()->first();
    }
}
