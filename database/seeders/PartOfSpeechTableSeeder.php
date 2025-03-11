<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PartOfSpeech;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

final class PartOfSpeechTableSeeder extends Seeder
{
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/part_of_speech.json'));
        $parts = json_decode($jsonDataFile);

        foreach ($parts as $region => $value) {
            PartOfSpeech::create(['name' => $value->name, 'value' => $value->value]);
        }
    }
}
