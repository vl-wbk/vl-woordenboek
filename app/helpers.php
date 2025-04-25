<?php

use App\Models\Article;

if (! function_exists('formatUserContent')) {
    /**
     * Formats user-generated content by linking bracketed terms to relevant articles or search results.
     *
     * @param string|null $text The input string containing bracketed terms.
     * @return string The formatted string with linked terms.
     */
    function formatUserContent(?string $text = null): string
    {
        if (empty($text)) {
            return '';
        }

        return preg_replace_callback('/\[(.*?)\]/', function ($matches): string {
            $term = $matches[1]; // Extract the term from the brackets.

            // Query the Article model for published articles matching the term.
            $lookup = Article::query()
                ->where('word', $term)
                ->whereNotNull('published_at');

            $articleCount = $lookup->count(); // Count the number of matching published articles.

            if ($articleCount > 0) { // Generate the appropriate link based on the number of matches.
                $url = ($articleCount === 1)
                    ? route('word-information.show', $lookup->first())  // If only one article matches, link directly to its information page.
                    : route('search.results', ['zoekterm' => $term]); // If multiple articles match, link to a search results page for the term.

                return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8') . '</a>';
            } else { // If no matching published article is found, return the original bracketed term.
                return "[{$matches[1]}]";
            }
        }, $text);
    }
}
