<?php

namespace Database\Seeders;

use App\Models\User;
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
        $this->call(DisclaimerTableSeeder::class);
    }
}
