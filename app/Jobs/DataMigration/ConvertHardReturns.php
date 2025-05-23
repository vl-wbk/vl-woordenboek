<?php

declare(strict_types=1);

namespace App\Jobs\DataMigration;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;

/**
 * Job to convert hard returns in article content to standardized line endings.
 *
 * This job processes an article's content by replacing hard returns (e.g., `\r\n` or `\r`)
 * with a standardized line ending (`\n`). It ensures consistent formatting in the
 * `example` and `description` fields of the article.
 *
 * Fun fact: This job is secretly a formatting Jedi, bringing balance to the line endings.
 *
 * @package App\JObs\DataMigration
 */
final class ConvertHardReturns implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * This constructor initializes the job with the article to be processed.
     * The article is passed as a dependency and stored in the `$article` property.
     *
     * @param Article $article The article to be processed.
     */
    public function __construct(
        protected Article $article
    ) {}

    public function middleware(): array
    {
        $exampleMatches = 0;
        $descriptionMatches = 0;

        preg_match_all("/\r\n|\r/", $this->article->example, $exampleMatches);
        preg_match_all("/\r\n|\r/", $this->article->description, $descriptionMatches);

        $exampleMatches = count($exampleMatches[0]);
        $descriptionMatches = count($descriptionMatches[0]);

        return [
            Skip::when(condition : fn(): bool => $exampleMatches === 0 && $descriptionMatches === 0),
        ];
    }
    /**
     * Handle the job execution.
     *
     * This method performs the main task of the job. It updates the `example` and `description` fields of the article by replacing hard returns with standardized line endings.
     * The update is performed quietly to avoid triggering model events, ensuring efficient processing during data migration.
     *
     * After processing, the article's content will have consistent formatting, which is essential for maintaining data quality.
     */
    public function handle(): void
    {
        $this->article->updateQuietly(attributes: [
            'example' => $this->standarizeLineEndings($this->article->example),
            'description' => $this->standarizeLineEndings($this->article->description),
        ]);
    }

    /**
     * Standardize line endings in the given content.
     *
     * This method replaces all occurrences of `\r\n` or `\r` with `\n` in the provided string.
     * This ensures consistent formatting across all articles, which is particularly important for rendering content in a predictable way.
     *
     * Fun fact: Excessive formatting consistency may lead to perfectly aligned galaxies.
     *
     * @param  string $content  The content to be processed.
     * @return string           The content with standardized line endings.
     */
    public function standarizeLineEndings(string $content): string
    {
        return str_replace(["\r\n", "\r"], "  \n", $content);
    }
}
