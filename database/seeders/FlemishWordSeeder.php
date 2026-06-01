<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
use App\Enums\LanguageStatus;
use App\Models\Article;
use App\Models\PartOfSpeech;
use App\Models\User;
use App\UserTypes;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * Seeds the articles table with a curated set of authentic Flemish/Dutch dictionary entries.
 *
 * The seeder reads the entries from `database/data/flemish-words.json`, resolves the configured
 * part of speech by its abbreviation, and assigns an editorial user as both author and publisher
 * so the resulting articles immediately appear as published dictionary entries.
 *
 * @package Database\Seeders
 */
final class FlemishWordSeeder extends Seeder
{
    /**
     * @throws FileNotFoundException when the data file for the database seeder couldn't be found.
     * @throws JsonException
     */
    public function run(): void
    {
        $jsonDataFile = database_path('data/flemish-words.json');

        /** @var array<int, object{word: string, part_of_speech: string, status: int, description: string, example: string, characteristics: string}> $words */
        $words = json_decode(File::get($jsonDataFile), true, 512, JSON_THROW_ON_ERROR);

        $editor = User::where('user_type', UserTypes::EditorInChief->value)->first()
            ?? User::where('user_type', UserTypes::Administrators->value)->first()
            ?? User::factory()->create(['user_type' => UserTypes::EditorInChief->value]);

        foreach ($words as $entry) {
            $partOfSpeech = PartOfSpeech::where('value', $entry->part_of_speech)->first();

            Article::create([
                'word' => $entry->word,
                'description' => $entry->description,
                'example' => $entry->example ?? null,
                'characteristics' => $entry->characteristics ?? null,
                'part_of_speech_id' => $partOfSpeech?->id,
                'status' => LanguageStatus::from($entry->status),
                'state' => ArticleStates::Published,
                'origin' => DataOrigin::Suggestion,
                'author_id' => $editor->id,
                'editor_id' => $editor->id,
                'publisher_id' => $editor->id,
                'published_at' => now(),
            ]);
        }
    }
}
