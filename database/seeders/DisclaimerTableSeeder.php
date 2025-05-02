<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Disclaimer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

final class DisclaimerTableSeeder extends Seeder
{
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/disclaimers.json'));
        $regions = json_decode($jsonDataFile);

        foreach ($regions as $region => $value) {
            Disclaimer::create(['id' => $value->id, 'name' => $value->name, 'type' => $value->type, 'message' => $value->message, 'usage' => $value->usage, 'description' => $value->description]);
        }
    }
}
