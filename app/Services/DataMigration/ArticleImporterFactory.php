<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

/**
 * Factory for creating instances of ArticleImporter.
 *
 * This class is responsible for constructing and returning a fully configured instance of the `ArticleImporter` class.
 * It ensures that all required dependencies are properly instantiated and injected into the importer.
 *
 * @package App\Services
 */
final readonly class ArticleImporterFactory
{
    /**
     * Creates a new instance of ArticleImporter.
     *
     * This method initializes the `ArticleImporter` with its required dependencies:
     *
     * - `JsonFileStreamer` for streaming JSON data from the source file.
     * - `ArticleDataMapper` for mapping raw JSON data to the application's data structure.
     * - `ArticleProcessor` for handling the creation and processing of articles.
     *
     * @return ArticleImporter A fully configured instance of the ArticleImporter.
     */
    public function createImporter(): ArticleImporter
    {
        return new ArticleImporter(
            new JsonFileStreamer(),
            new ArticleDataMapper(),
            new ArticleProcessor(),
        );
    }
}
