<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserTableSeeder::class);
        $this->call(RegionTableSeeder::class);
        $this->call(LabelTableSeeder::class);
        $this->call(PartOfSpeechTableSeeder::class);
    }
}
