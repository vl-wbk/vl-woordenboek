<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ArticleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $jsonDataFile = File::get(database_path('data/categories.json'));
        $regions = json_decode($jsonDataFile);

        foreach ($regions as $value) {
            Category::create(['description' => $value->description, 'name' => $value->name]);
        }
    }
}
