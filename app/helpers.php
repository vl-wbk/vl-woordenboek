<?php

use App\Models\Article;

if (! function_exists('formatUserContent')) {
    function formatUserContent(?string $text = null): string {
        if (empty($text)) {
            return '';
        }

        return preg_replace_callback('/\[(.*?)\]/', function ($matches): string  {
            $term = $matches[1];
            $lookup = Article::where('word', $term)->whereNotNull('published_at');

            if ($lookup->count() > 0) {
                if ($lookup->count() === 1) {
                    $url = route('word-information.show', $lookup->first());
                } else {
                    $url = route('search.results', ['zoekterm' => $term]);
                }

                return '<a href="' . $url . '">' . $matches[1] . '</a>'; // Escape again for display in the link text
            } else {
                return "[{$matches[1]}]";
            }
        }, $text);
    }
}
