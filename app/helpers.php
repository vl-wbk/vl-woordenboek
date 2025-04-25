<?php

use App\Models\Article;

if (! function_exists('formatUserContent')) {
    function formatUserContent(?string $text = null): string {
        if (empty($text)) {
            return '';
        }

        return preg_replace_callback('/\[(.*?)\]/', function ($matches): string  {
            $term = $matches[1];

            if ($record = Article::where('word', $term)->first()) {
                $url = route('word-information.show', $record); // Assuming you have a route named 'term_definitions_path'
                return '<a href="' . $url . '">' . $matches[1] . '</a>'; // Escape again for display in the link text
            } else {
                return "[{$matches[1]}]";
            }
        }, $text);
    }
}
