<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/** @todo document */
class RegionTableSeeder extends Seeder
{
    /** @todo document */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/regions.json'));
        $regions = json_decode($jsonDataFile);

        foreach ($regions as $value) {
            Region::create(['id' => $value->id, 'name' => $value->name]);
        }
    }
}
