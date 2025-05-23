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
final class ArticleDataMapper
{
    private static array $regionMappingLookup = [
        '0' => 2,  // Gans Vlaanderen -> Gans Vlaanderen (target_id: 02)
        '1' => 1,  // Standaard Nederlands -> Onbekend (target_id: 01)
        '2' => 1,  // Onbekend -> Onbekend (target_id: 01)
        '100' => 4,  // West-Vlaanderen -> Binnen-West-Vlaanderen (target_id: 04)
        '110' => 9,  // Vlaamse Kust -> Noord-West-Vlaanderen (target_id: 09)
        '120' => 16, // Westhoek -> Westelijk West-Vlaanderen (target_id: 16)
        '130' => 9,  // Brugge -> Noord-West-Vlaanderen (target_id: 09)
        '140' => 4,  // Brugs Ommeland -> Binnen-West-Vlaanderen (target_id: 04)
        '150' => 4,  // Leiestreek -> Binnen-West-Vlaanderen (target_id: 04)
        '200' => 12, // Oost-Vlaanderen -> Oost-Vlaanderen (target_id: 12)
        '210' => 18, // Meetjesland -> West-Vlaanderen>Oost-Vlaanderen (target_id: 18)
        '220' => 12, // Gent -> Oost-Vlaanderen (target_id: 12)
        '230' => 12, // Vlaamse Ardennen -> Oost-Vlaanderen (target_id: 12)
        '240' => 15, // Waasland -> Waasland (target_id: 15)
        '250' => 19, // Scheldeland -> Zuid-Brabant (target_id: 19)
        '300' => 7,  // Antwerpen -> Kempen (target_id: 07)
        '310' => 8,  // Antwerpen -> Noordwest-Brabant (target_id: 08)
        '320' => 7,  // Mechelen -> Kempen (target_id: 07)
        '330' => 7,  // Antwerpse Kempen -> Kempen (target_id: 07)
        '400' => 19, // Vlaams Brabant -> Zuid-Brabant (target_id: 19)
        '410' => 19, // Brussel -> Zuid-Brabant (target_id: 19)
        '420' => 19, // Groene Gordel -> Zuid-Brabant (target_id: 19)
        '430' => 19, // Leuven -> Zuid-Brabant (target_id: 19)
        '440' => 19, // Hageland -> Zuid-Brabant (target_id: 19)
        '500' => 5,  // Limburg -> Centraal Limburg en Maasland (target_id: 05)
        '510' => 17, // Limburgse Kempen -> West-Limburg (target_id: 17)
        '520' => 17, // Mijnstreek -> West-Limburg (target_id: 17)
        '530' => 14, // Haspengouw -> Truierland (target_id: 14)
        '540' => 5,  // Maasland -> Centraal Limburg en Maasland (target_id: 05)
        '550' => 10, // Voerstreek -> Oost-Limburg (target_id: 10)
    ];

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
            'region' => $this->convertLinguisticRegion($articleData->regio),
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

    private function convertLinguisticRegion(int $region): int
    {
        return self::$regionMappingLookup[$region] ?? 0;
    }
}
