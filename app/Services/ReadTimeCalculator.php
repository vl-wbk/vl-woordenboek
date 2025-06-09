<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;

class ReadTimeCalculator
{
    /**
     * Average words per minute for reading.
     * You can adjust this value based on your audience.
     * @var int
     */
    protected int $wordsPerMinute;

    /**
     * Seconds to add per image found in the content.
     * This is a simplified approach. Some sophisticated calculators add decreasing seconds for the first 10 images.
     * @var int
     */
    protected int $secondsPerImage;

    public function __construct()
    {
        // Get these values from config or set defaults
        $this->wordsPerMinute = config('read_time.words_per_minute', 200);
        $this->secondsPerImage = config('read_time.seconds_per_image', 5);
    }

    /**
     * Calculate the estimated read time for markdown content.
     *
     * @param string $markdownContent The raw markdown string of the post.
     * @return string A human-readable read time string (e.g., "5 min read").
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
        if ($minutes < 1) {
            return trans('Leestijd: :time min.', ['time' => 1]);
        } elseif ($minutes === 1) {
            return trans('Leestijd: :time min.', ['time' => 1]);
        } else {
            return trans('Leestijd: :time min.', ['time' => $minutes]);
        }
    }

    /**
     * Calculate the estimated read time in minutes (integer) for markdown content.
     * This is useful if you need the raw number for sorting or other logic.
     *
     * @param string $markdownContent
     * @return int The estimated read time in minutes.
     */
    public function calculateInMinutes(string $markdownContent): int
    {
        $converter = new CommonMarkConverter();
        $plainText = strip_tags($converter->convert($markdownContent)->getContent());
        $words = preg_split('/\s+/', $plainText, -1, PREG_SPLIT_NO_EMPTY);
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
     * Detects and counts common Markdown image syntaxes and HTML <img> tags.
     *
     * @param string $markdownContent
     * @return int The number of images found.
     */
    protected function countImagesInMarkdown(string $markdownContent): int
    {
        $count = 0;

        // Regex for Markdown image syntax: ![alt text](url "optional title")
        // This is a simple pattern; more complex markdown might require a more robust parser.
        preg_match_all('/!\[[^\]]*\]\([^\)]+\)/', $markdownContent, $markdownMatches);
        $count += count($markdownMatches[0]);

        // Regex for HTML <img> tags: <img src="..." alt="..." />
        preg_match_all('/<img[^>]*src\s*=\s*["\']([^"\']*)["\'][^>]*>/i', $markdownContent, $htmlMatches);
        $count += count($htmlMatches[0]);

        return $count;
    }
}
