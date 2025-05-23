<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JsonMachine\Items;
use RuntimeException;

/**
 * JsonFileStreamer provides a robust and memory-efficient way to stream and parse large JSON files.
 *
 * This class leverages the `JsonMachine` library to read JSON data iteratively from a file stream,
 * preventing memory exhaustion that can occur when attempting to load entire large JSON files into memory.
 * It integrates with Laravel's `Storage` facade to handle file existence checks and stream opening,
 * ensuring seamless operation within a Laravel application's configured disk environment.
 *
 * This is particularly useful for data migration tasks, processing large datasets, or any scenario
 * where you need to consume JSON data without loading it all at once.
 *
 * @package App\Services
 */
final readonly class JsonFileStreamer
{
    /**
     * Streams a JSON file and returns an iterable object of its items.
     *
     * This method ensures that the specified JSON file exists and is readable.
     * It opens a stream to the file and uses `JsonMachine\Items` to parse the JSON content iteratively, which is crucial for handling large files efficiently.
     *
     * @param  string  $filePath  The path to the JSON file, relative to the configured Laravel disk (e.g., 'data_migrations/users.json').
     * @return Items              An iterable object that yields JSON items one by one. You can iterate over this object in a `foreach` loop to process each JSON element.
     *
     * @throws RuntimeException If the specified file does not exist or cannot be opened for reading.
     */
    public function streamJson(string $filePath): Items
    {
        // Check if the file exists using Laravel's Storage facade.
        if (!Storage::exists($filePath)) {
            // Log an error if the file is not found, including the path for debugging.
            Log::error('JSON streamer: Source file not found.', ['path' => $filePath]);
            // Throw a RuntimeException as the operation cannot proceed without the file.
            throw new RuntimeException("Source JSON file not found at: {$filePath}");
        }

        // Attempt to open a read stream to the file.
        // Storage::readStream returns a resource on success or false on failure.
        $stream = Storage::readStream($filePath);

        if (!$stream) {
            // Log an error if the stream could not be opened.
            Log::error('JSON streamer: Failed to open stream for file.', ['path' => $filePath]);
            // Throw a RuntimeException as the file stream is essential for processing.
            throw new RuntimeException("Could not open file stream for: {$filePath}");
        }

        // Return a JsonMachine\Items instance from the opened stream.
        // This allows for efficient, memory-friendly iteration over the JSON contents.
        return Items::fromStream($stream);
    }
}
