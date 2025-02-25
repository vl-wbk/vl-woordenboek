<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/regions.json'));
        $regions = json_decode($jsonDataFile);

        foreach ($regions as $region => $value) {
            Region::create(['name' => $value->name]);
        }
    }
}
