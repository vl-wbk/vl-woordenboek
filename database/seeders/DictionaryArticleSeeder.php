<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use stdClass;

final class DictionaryArticleSeeder extends Seeder
{
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/test-artikelen.json'));

        /** var stdClass[] $articles */
        $articles = json_decode($jsonDataFile);

        /** @phpstan-ignore-next-line */
        collect($articles)->each(function (stdClass $article): void {
            Article::create(attributes: [
                'word' => $article->word,
                'description' => $article->description,
                'example' => $article->example,
                'characteristics' => $article->properties,
                'created_at' => $article->updated_at,
                'updated_at' => $article->updated_at,
            ]);
        });
    }
}
