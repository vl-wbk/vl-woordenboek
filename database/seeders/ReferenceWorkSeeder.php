<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ReferenceWork;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use JsonException;

final class ReferenceWorkSeeder extends Seeder
{
    /**
     * @throws FileNotFoundException w<hen the data file for the seeder couldn't be found
     * @throws JsonException
     */
    public function run(): void
    {
        $jsonDataFile = database_path('data/reference-works.json');

        /** @var array<int, object{abbreviation: string, name: string}> $referenceWorks */
        $referenceWorks = json_decode(File::get($jsonDataFile), false, 512, JSON_THROW_ON_ERROR);

        foreach ($referenceWorks as $referenceWork) {
            ReferenceWork::create(attributes: [
                'abbreviation' => $referenceWork->abbreviation,
                'name' => $referenceWork->name,
            ]);
        }
    }
}
