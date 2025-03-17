<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PartOfSpeech;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

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
 *
 * @package Database\Seeders
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \JsonException
     *
     * @return void
     */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/part_of_speech.json'));
        $parts = json_decode($jsonDataFile);

        foreach ($parts as $region => $value) {
            PartOfSpeech::create(['name' => $value->name, 'value' => $value->value]);
        }
    }
}
