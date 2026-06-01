<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;

final readonly class ReadTimeCalculator
{
    /**
     * The average number of words a person can read per minute.
     *
     * This value is used in calculating the estimated reading time of an article.
     * You can adjust this based on your target audience's typical reading speed.
     * For example, a common average reading speed for adults is around 200-250 words per minute.
     */
    protected int $wordsPerMinute;

    /**
     * The number of seconds to add to the reading time for each image found in the content.
     *
     * Images can interrupt the flow of reading, and this variable accounts for the time a user might spend looking at or processing each image.
     * This implementation uses a simplified approach where each image adds a fixed amount of time.
     * More advanced calculators might vary this, for instance, by adding more time for the first few images and less for subsequent ones.
     */
    protected int $secondsPerImage;

    public function __construct()
    {
        // Get these values from config or set defaults
        $this->wordsPerMinute = config()->integer('read_time.words_per_minute', 200);
        $this->secondsPerImage = config()->integer('read_time.seconds_per_image', 5);
    }

    /**
     * Calculate the estimated read time for markdown content.
     *
     * @param  string $markdownContent The raw markdown string of the post.
     * @return string                  A human-readable read time string (e.g., "5 min read").
     *
     * @throws CommonMarkException
     */
    public function calculate(string $markdownContent): string
    {
        // 1. Convert markdown to plain text
        $converter = new CommonMarkConverter();
        // Convert markdown to HTML, then strip HTML tags to get pure text
        $plainText = strip_tags($converter->convert($markdownContent)->getContent());

        // 2. Count words
        // Use a robust way to count words: split by whitespace and filter out empty strings
        $words = preg_split('/\s+/', $plainText, -1, PREG_SPLIT_NO_EMPTY);
        /** @phpstan-ignore-next-line */
        $wordCount = count($words);

        // 3. Detect and count images in markdown
        $imageCount = $this->countImagesInMarkdown($markdownContent);

        // 4. Calculate initial reading time for words in minutes
        $minutes = (int) ceil($wordCount / $this->wordsPerMinute);

        // 5. Add time for images (convert seconds to minutes and add)
        if ($imageCount > 0) {
            $imageReadingTimeSeconds = $imageCount * $this->secondsPerImage;
            $minutes += (int) ceil($imageReadingTimeSeconds / 60);
        }

        // 6. Format the output string
        return match (true) {
            $minutes < 1, $minutes === 1 => trans('Leestijd: :time min.', ['time' => 1]),
            default => trans('Leestijd: :time min.', ['time' => $minutes]),
        };
    }

    /**
     * Calculates the estimated reading time for Markdown content in whole minutes.
     *
     * This method converts the provided Markdown content into plain text, counts the words, and adds a time estimation for any images present.
     * It's designed to return a raw integer value representing minutes, which is useful for sorting, filtering, or
     * other programmatic logic where an exact numerical duration is required.
     *
     * The calculation factors in:
     * - The number of words in the content based on a predefined `wordsPerMinute` rate.
     * - Additional time for each image, using a `secondsPerImage` value.
     *
     * @param  string $markdownContent The Markdown formatted string content of an article or post.
     * @return int                     The estimated reading time, rounded up to the nearest whole minute.
     *
     * @throws CommonMarkException
     */
    public function calculateInMinutes(string $markdownContent): int
    {
        $converter = new CommonMarkConverter();
        $plainText = strip_tags($converter->convert($markdownContent)->getContent());
        $words = preg_split('/\s+/', $plainText, -1, PREG_SPLIT_NO_EMPTY);

        /** @phpstan-ignore-next-line */
        $wordCount = count($words);
        $imageCount = $this->countImagesInMarkdown($markdownContent);

        $minutes = (int) ceil($wordCount / $this->wordsPerMinute);

        if ($imageCount > 0) {
            $imageReadingTimeSeconds = $imageCount * $this->secondsPerImage;
            $minutes += (int) ceil($imageReadingTimeSeconds / 60);
        }

        return $minutes;
    }

    /**
     * Detects and counts common Markdown image syntaxes and HTML <img> tags within a given string.
     *
     * This function uses regular expressions to find occurrences of both standard Markdown image syntax (`![alt text](url "optional title")`) and HTML `<img>` tags.
     * It provides a basic count and may not be exhaustive for all possible Markdown variations or highly complex embedded HTML structures.
     *
     * @param  string $markdownContent  The string content, potentially containing Markdown and/or HTML.
     * @return int                      The total number of detected images (Markdown images + HTML `<img>` tags).
     */
    private function countImagesInMarkdown(string $markdownContent): int
    {
        $count = 0;

        // Regex for Markdown image syntax: ![alt text](url "optional title")
        // This is a simple pattern; more complex markdown might require a more robust parser.
        preg_match_all('/!\[[^\]]*\]\([^\)]+\)/', $markdownContent, $markdownMatches);
        $count += count($markdownMatches[0]);

        // Regex for HTML <img> tags: <img src="..." alt="..." />
        preg_match_all('/<img[^>]*src\s*=\s*["\']([^"\']*)["\'][^>]*>/i', $markdownContent, $htmlMatches);
        return $count + count($htmlMatches[0]);
    }
}
