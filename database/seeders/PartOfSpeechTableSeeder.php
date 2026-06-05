<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PartOfSpeech;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use JsonException;

/**
 * Part of Speech Table Seeder
 *
 * This seeder is responsible for populating the parts of speech table with standard Flemish grammatical categories (woordsoorten).
 * It forms the foundation for the dictionary's linguistic classification system by reading predefined categories from a JSON source file.
 * This approach ensures that all dictionary entries adhere to standard Flemish grammatical conventions.
 *
 * The source data is maintained in a JSON file within the database/data directory.
 * This file contains the official Flemish grammatical terminology used in linguistic classification.
 * By centralizing these definitions, we maintain consistency in how words are categorized throughout the dictionary system.
 */
final class PartOfSpeechTableSeeder extends Seeder
{
    /**
     * Execute the database seeding process
     *
     * This method reads and processes the standardized Flemish grammatical categories from our JSON data source.
     * Each entry in the source represents an official woordsoort as used in Flemish linguistics.
     * The seeder creates corresponding database records, establishing the foundational grammatical framework for the dictionary.
     *
     * The process preserves the authentic Flemish grammatical terminology, ensuring that
     * the dictionary maintains proper linguistic standards while cataloging words and
     * their usage.
     *
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function run(): void
    {
        $jsonDataFile = database_path('data/part_of_speech.json');

        /** @var array<int, object{name: string, value: string}> $parts */
        $parts = json_decode(File::get($jsonDataFile), false, 512, JSON_THROW_ON_ERROR);

        foreach ($parts as $partOfSpeech) {
            PartOfSpeech::create(attributes: [
                'name' => $partOfSpeech->name,
                'value' => $partOfSpeech->value,
            ]);
        }
    }
}
