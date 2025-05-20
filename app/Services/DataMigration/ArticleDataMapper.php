<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
use RuntimeException;
use stdClass;

/**
 * Maps raw article data to the application's internal data structure.
 *
 * The `ArticleDataMapper` class is responsible for transforming raw article data
 * (typically from an external source) into an array format that matches the
 * application's database schema. It also validates that all required fields
 * are present in the raw data before mapping.
 *
 * @package App\Services
 */
final readonly class ArticleDataMapper
{
    /**
     * Maps raw article data to an array format suitable for database insertion.
     *
     * This method takes a `stdClass` object containing raw article data, validates
     * that all required fields are present, and maps the data to an associative
     * array that matches the application's database schema.
     *
     * @param stdClass $articleData  The raw article data to be mapped.
     * @return array                 The mapped article data, ready for database insertion.
     *
     * @throws RuntimeException If any required fields are missing from the raw data.
     */
    public function map(stdClass $articleData): array
    {
        $this->validateRequiredFields($articleData);

        return [
            'origin' => DataOrigin::External, // Indicates the data source is external.
            'state' => ArticleStates::ExternalData, // Sets the article state to external data.
            'word' => $articleData->word, // The word or term for the article.
            'views' => $articleData->rating ?? 0, // The number of views or rating, defaults to 0.
            'description' => $articleData->description, // The main description of the article.
            'example' => $articleData->example, // Example usage or context for the article.
            'characteristics' => $articleData->properties, // Additional properties or metadata.
            'disclaimer_id' => 1, // Default disclaimer ID for all articles.
            'published_at' => $articleData->updated_at, // Sets the published date to the updated date.
            'created_at' => $articleData->updated_at, // Sets the creation date to the updated date.
            'updated_at' => $articleData->updated_at, // Sets the last updated date.
        ];
    }

    /**
     * Validates that all required fields are present in the raw article data.
     *
     * This method checks the raw data for the presence of specific fields that are required for the article to be processed.
     * If any required fields are missing, a `RuntimeException` is thrown with a detailed error message.
     *
     * @param  stdClass $articleData The raw article data to validate.
     *
     * @throws RuntimeException If any required fields are missing.
     */
    private function validateRequiredFields(stdClass $articleData): void
    {
        $requiredFields = ['word', 'description', 'example', 'properties', 'updated_at'];
        foreach ($requiredFields as $field) {
            if (!isset($articleData->$field)) {
                throw new RuntimeException("Missing required field '{$field}' for article. Raw data: " . json_encode($articleData));
            }
        }
    }
}
