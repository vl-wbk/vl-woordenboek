<?php

declare(strict_types=1);

namespace App\Jobs\DataMigration;

use App\Enums\Articles\SearchPatterns;
use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;

/**
 * Standarizes internal hyperlins within an article's example and description fields.
 *
 * This job processes the example and description fields of an Article model, identifying terms enclosed in square bracketd (e?g?, [term]) that are not already part of a Markdown link.
 * It then attempts to convert those into proper Markdown links based on existing Article entries.
 *
 * If a bracketed term matches a single published article, it creates a direct link to that article's information page. If it matches multiple published articles, it creates a link to the search results page for that term.
 * Terms that do not match any published articles are left unchanged.
 *
 * @property App\Jobs\DataMigration
 */
final class StandarizeInternalHyperlinks implements ShouldQueue
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
     * @param Article $article The article to be processed.
     */
    public function __construct(
        private Article $article
    ) {
    }

    /**
     * Get the middelware the job should pass through.
     *
     * The mîddelwaee checks if there are any unformatted internal hyperlinks terms in square brackets not followed by parentheses) in the article's example or description fields.
     * If no such patterns are found, the job is skipped.
     *
     * @return array<int, Skip>
     */
    public function middleware(): array
    {
        $exampleMatches = 0;
        $descriptionMatches = 0;

        // Count unformatted links in the example field
        preg_match_all("/\[(.*?)\](?!\()/", (string) $this->article->example, $exampleMatches);

        // Count unformatted links in the description field
        preg_match_all("/\[(.*?)\](?!\()/", $this->article->description, $descriptionMatches);

        $exampleMatches = count($exampleMatches[0]);
        $descriptionMatches = count($descriptionMatches[0]);

        return [
            // Skip the job if no unformatted links are found in either field
            Skip::when(condition : fn(): bool => $exampleMatches === 0 && $descriptionMatches === 0),
        ];
    }

    /**
     * Execute the job.
     *
     * This method extracts all unique bracketed terms from the article's example and description.
     * It then queries the database for published articles matching these terms. $
     * Finally, it formats the identified terms into Markdown links based on the lookup results and updates the article if changes were made.
     */
    public function handle(): void
    {
        $uniqueTerms = collect();

        if ($this->article->example) { // Extract bracketed terms from the example field
            preg_match_all('/\[(.*?)\](?!\()/', (string) $this->article->example, $matches);

            /** @phpstan-ignore-next-line */
            if (isset($matches[1]) && $matches[1] !== []) {
                $uniqueTerms = $uniqueTerms->merge($matches[1]);
            }
        }
        if ($this->article->description) { // Extract bracketed terms from the description field.
            preg_match_all('/\[(.*?)\](?!\()/', $this->article->description, $matches);

            /** @phpstan-ignore-next-line */
            if (isset($matches[1]) && $matches[1] !== []) {
                $uniqueTerms = $uniqueTerms->merge($matches[1]);
            }
        }

        // Get Unique, non empty terms and re-index the collection
        $uniqueTerms = $uniqueTerms->unique()->filter()->values();

        if ($uniqueTerms->isEmpty()) { // If no unique terms are found, there's nothing to do.
            return;
        }

        // Query for published articles that match the extracted terms
        $lookupArticles = Article::query()
            ->select(['id', 'word', 'published_at'])
            ->whereIn('word', $uniqueTerms)
            ->whereNotNull('published_at')
            ->get();

        // Organize lookup data by word, including count and the ID of the first published article
        $lookupData = $lookupArticles->groupBy('word')->map(function ($items): array {
            $publishedItems = $items->filter(fn($item): bool => $item->published_at !== null);

            return [
                'count' => $publishedItems->count(),
                'first_id' => $publishedItems->count() > 0 ? $publishedItems->first()->id : null,
            ];
        })->toArray();

        // Format the example and description fields with Markdown links
        $updatedExample = $this->formatMarkdownLinks($lookupData, $this->article->example);
        $updatedDescription = $this->formatMarkdownLinks($lookupData, $this->article->description);

        // If either field has been updated, save the changes to the article
        if ($this->article->example !== $updatedExample || $this->article->description !== $updatedDescription) {
            $this->article->updateQuietly(attributes: [
                'example' => $updatedExample,
                'description' => $updatedDescription,
            ]);
        }
    }

    /**
     * Formats bracketed terms in a given text into Markdown links.
     * Iterates through the text, finds terms enclosed in square brackets that are not already part of a Markdown link, and replaces them with a Markdown link if a corresponding published article is found in the `$lookupData`.
     *
     * @param  array<string, array{count: int, first_id: int|null}> $lookupData  An associative array where keys are terms and values contain the count of published articles and the ID of the first one.
     * @param  string|null                                          $text        The text to format.
     * @return string                                                            The formatted text with internal hyperlinks.
     */
    private function formatMarkdownLinks(array $lookupData, ?string $text = null): string
    {
        if (is_null($text)) {
            return '';
        }

        // Use preg_replace_callback to find and replace bracketed terms
        return preg_replace_callback('/\[(.*?)\](?!\()/', function (array $matches) use ($lookupData): string {
            $term = $matches[1];

            $lookup = $lookupData[$term] ?? null;

            // If the term has a corresponding published article(s)
            if ($lookup && $lookup['count'] > 0) {
                // Determine the URL based on the number of matching articles
                $url = ($lookup['count'] === 1)
                    ? route('word-information.show', $lookup['first_id'])
                    : route('search.results', parameters: [
                        'zoekpatroon' => SearchPatterns::Exact,
                        'zoekterm' => str_replace(' ', '+', $term)
                    ]);

                // Return the formatted Markdown link
                return "[{$term}]({$url})";
            }

            // If no published article is found, return the original match (unaltered)
            return $matches[0];
        }, $text);
    }
}
