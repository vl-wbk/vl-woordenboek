<?php

declare(strict_types=1);

namespace App\Queries\Articles;

use App\Models\Article;
use Illuminate\Support\Facades\DB;

final readonly class SelectRandomArticle
{
    public static function fetch(): Article
    {
        $randomIdentifier = DB::table('articles')
            ->whereNotNull('published_at')
            ->whereNull('deleted_at')
            ->max('id');

        if ($randomIdentifier) {
            $randomIdentifier = rand(1, (int) $randomIdentifier);

            return Article::published()
                ->whereNull('deleted_at')
                // Find the first article whose ID is >= the generated random ID
                ->where('id', '>=', $randomIdentifier)
                ->orderBy('id', 'asc')
                ->limit(8)
                ->first();
        }

        return  Article::whereNotNull('published_at')
            ->whereNull('deleted_at')
            ->orderBy('id', 'asc') // Select the lowest ID if we couldn't find one higher than random ID
            ->limit(1)
            ->first();
    }
}

