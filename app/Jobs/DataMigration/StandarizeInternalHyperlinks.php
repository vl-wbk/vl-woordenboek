<?php

declare(strict_types=1);

namespace App\Jobs\DataMigration;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class StandarizeInternalHyperlinks implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Article $article
    ) {}

    public function handle(): void
    {
        $uniqueTerms = collect();

        if ($this->article->example) {
            preg_match_all('/\[(.*?)\](?!\()/', $this->article->example, $matches);
            if (!empty($matches[1])) {
                $uniqueTerms = $uniqueTerms->merge($matches[1]);
            }
        }
        if ($this->article->description) {
            preg_match_all('/\[(.*?)\](?!\()/', $this->article->description, $matches);

            if (!empty($matches[1])) {
                $uniqueTerms = $uniqueTerms->merge($matches[1]);
            }
        }

        $uniqueTerms = $uniqueTerms->unique()->filter()->values();

        if ($uniqueTerms->isEmpty()) {
            return;
        }

        $lookupArticles = Article::query()
            ->select(['id', 'word', 'published_at'])
            ->whereIn('word', $uniqueTerms)
            ->whereNotNull('published_at')
            ->get();

        $lookupData = $lookupArticles->groupBy('word')->map(function ($items) {
            $publishedItems = $items->filter(fn($item) => $item->published_at !== null);

            return [
                'count' => $publishedItems->count(),
                'first_id' => $publishedItems->count() > 0 ? $publishedItems->first()->id : null,
            ];
        })->toArray();

        $updatedExample = $this->formatMarkdownLinks($this->article->example, $lookupData);
        $updatedDescription = $this->formatMarkdownLinks($this->article->description, $lookupData);

        if ($this->article->example !== $updatedExample || $this->article->description !== $updatedDescription) {
            $this->article->updateQuietly(attributes: [
                'example' => $updatedExample,
                'description' => $updatedDescription,
            ]);
        }
    }

    private function formatMarkdownLinks(?string $text = null, array $lookupData): string
    {
        if (is_null($text)) {
            return '';
        }

        return preg_replace_callback('/\[(.*?)\](?!\()/', function ($matches) use ($lookupData): string {
            $term = $matches[1];

            $lookup = $lookupData[$term] ?? null;

            if ($lookup && $lookup['count'] > 0) {
                $url = ($lookup['count'] === 1)
                    ? route('word-information.show', $lookup['first_id'])
                    : route('search.results', ['zoekterm' => $term]);

                return "[{$term}]({$url})";
            }

            return $matches[0];
        }, $text);
    }
}
