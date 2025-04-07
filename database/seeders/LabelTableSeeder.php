<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use stdClass;

/**
 * LabelTableSeeder populates the labels table with initial taxonomic data for the Vlaams Woordenboek.
 *
 * This seeder reads label definitions from a JSON data file and creates corresponding database records.
 * The JSON structure provides a maintainable way to define the dictionary's initial categorization system, keeping taxonomy data separate from the seeding logic.
 * Each label entry in the JSON file includes a name and optional description which are used to create Label model instances.
 */
final class LabelTableSeeder extends Seeder
{
    /**
     * Seeds the labels table with predefined taxonomic categories.
     *
     * This method reads label definitions from a JSON file located in the database/data directory.
     * It processes each entry to create a new Label record, establishing the initial set of categories available for article classification.
     * The seeder uses Laravel's collection methods for efficient data processing and database insertion.
     */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/labels.json'));

        /** @var stdClass[] $labels */
        $labels = json_decode($jsonDataFile);

        collect($labels)->each(function (stdClass $label): void {
            Label::create(attributes: ['name' => $label->name, 'description' => $label->description]);
        });
    }
}
