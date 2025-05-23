<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

use App\Models\Article;
use Closure;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;
use RuntimeException;
use stdClass;

/**
 * The ArticleImporter serves as the main orchestrator for importing articles from JSON data into the application.
 * It coordinates the entire process by streaming data from source files, transforming raw data into application structures, and managing the creation of articles in the database.
 *
 * Memory efficiency is a key consideration in this implementation.
 * Rather than loading the entire JSON file into memory, the importer streams the data and processes articles one at a time.
 * This approach allows handling of large data sets without excessive memory consumption.
 *
 * @package App\Services\DataMigration
 */
final class ArticleImporter
{
    /**
     * Defines how frequently progress updates are sent to the console during the import process.
     * An update will be triggered after processing this many articles.
     */
    private static int $consoleUpdateFrequency = 25;

    /**
     * Creates a new importer instance with its required dependencies.
     * The JsonFileStreamer handles reading from the source file, the ArticleDataMapper transforms raw data to our format, and the ArticleProcessor handles the database operations.
     *
     * @param JsonFileStreamer   $jsonFileStreamer  Handles reading from the source file
     * @param ArticleDataMapper  $dataMapper        Transforms raw data to application format
     * @param ArticleProcessor   $articleProcessor  Handles database operations
     */
    public function __construct(
        private JsonFileStreamer $jsonFileStreamer,
        private ArticleDataMapper $dataMapper,
        private ArticleProcessor $articleProcessor,
    ) {}

    /**
     * Processes the import of articles from a JSON file source.
     *
     * This method orchestrates the entire import workflow. It streams data from the source file, validates each item, maps the data to our format, and processes each article.
     * Progress updates are provided through an optional callback, and warnings can be handled through a separate callback.
     *
     * The method carefully manages resources by ensuring proper cleanup of file handles, even in case of errors.
     * It tracks the number of successfully processed articles and provides detailed error logging for any failures.
     *
     * @param  string        $filePath          Path to the JSON file containing article data
     * @param  Closure|null  $progressCallback  Called to report progress (params: int $count, Article $lastArticle)
     * @param  Closure|null  $warningCallback   Called when warnings occur (param: string $message)
     * @return int                              Number of successfully processed articles
     *
     * @throws RuntimeException If the file cannot be read or processed
     */
    public function importArticles(string $filePath, ?Closure $progressCallback = null, ?Closure $warningCallback = null): int
    {
        $streamItems = $this->jsonFileStreamer->streamJson($filePath);
        $streamResource = $this->extractStreamResource($streamItems);
        $processedCount = 0;

        try {
            /** @var stdClass $item */
            foreach ($streamItems as $item) {
                if (!$item instanceof stdClass) {
                    $message = "Skipping malformed item (not an object).";
                    $this->logWarning($message, ['item_data' => $item]);
                    if ($warningCallback) {
                        $warningCallback($message);
                    }
                    continue;
                }

                try {
                    $mappedData = $this->dataMapper->map($item);
                    $article = $this->articleProcessor->process($mappedData);

                    $processedCount++;

                    if ($progressCallback && ($processedCount % self::$consoleUpdateFrequency === 0)) {
                        $progressCallback($processedCount, $article);
                    }
                } catch (RuntimeException $e) {
                    $word = $item->word ?? 'N/A';
                    $message = "Failed to process article '{$word}': {$e->getMessage()}";
                    $this->logError($message, ['error' => $e->getMessage(), 'item' => $item]);
                }
            }

            if ($progressCallback && $processedCount > 0) {
                $progressCallback($processedCount, $article ?? new Article());
            }

        } finally {
            if (is_resource($streamResource)) {
                fclose($streamResource);
            }
        }

        return $processedCount;
    }

    /**
     * Retrieves the underlying stream resource from a JsonMachine Items instance.
     *
     * This helper method uses reflection to access the protected stream resource, enabling proper cleanup after processing completes.
     * If the extraction fails, it logs a warning but allows processing to continue.
     *
     * @param  Items $items   The JsonMachine Items instance to extract from
     * @return resource|null  The stream resource or null if extraction fails
     */
    private function extractStreamResource(Items $items)
    {
        try {
            $reflection = new \ReflectionClass($items);
            $property = $reflection->getProperty('f');
            $property->setAccessible(true);

            return $property->getValue($items);
        } catch (\ReflectionException $e) {
            Log::warning("Could not extract stream resource from JsonMachine\Items for explicit closing: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Records a warning message in the application logs.
     * Warning messages indicate non-critical issues that don't prevent the overall import process from continuing, such as malformed data or missing optional fields.
     *
     * @param  string                $message  The warning message to log
     * @param  array<string, mixed>  $context  Additional context data for the log
     * @return void
     */
    private function logWarning(string $message, array $context = []): void
    {
        Log::warning("Article migration: {$message}", $context);
    }

    /**
     * Records an error message in the application logs.
     * Error messages indicate more serious issues that prevented an individual article from being processed, though the overall import continues with the next item.
     *
     * @param  string $message                The error message to log
     * @param  array<string, mixed> $context  Additional context data for the log
     * @return void
     */
    private function logError(string $message, array $context = []): void
    {
        Log::error("Article migration: {$message}", $context);
    }
}
