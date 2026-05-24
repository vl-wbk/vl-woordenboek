<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ReferenceWork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

final class ReferenceWorkSeeder extends Seeder
{
    /**
     * @throws FileNotFoundException w<hen the data file for the seeder couldn't be found
     */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/reference-works.json'));
        $referenceWorks = json_decode($jsonDataFile);

        foreach ($referenceWorks as $referenceWork) {
            ReferenceWork::create(['abbreviation' => $referenceWork->abbreviation, 'name' => $referenceWork->name]);
        }
    }
}
