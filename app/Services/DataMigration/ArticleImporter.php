<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

use App\Models\Article;
use Closure;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;
use RuntimeException;
use stdClass;

final class ArticleImporter
{
    private static int $consoleUpdateFrequency = 25;

    public function __construct(
        private JsonFileStreamer $jsonFileStreamer,
        private ArticleDataMapper $dataMapper,
        private ArticleProcessor $articleProcessor,
    ) {}

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

    private function logWarning(string $message, array $context = []): void
    {
        Log::warning("Article migration: {$message}", $context);
    }

    private function logError(string $message, array $context = []): void
    {
        Log::error("Article migration: {$message}", $context);
    }
}
