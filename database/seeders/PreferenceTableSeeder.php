<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Preferences;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Preferences::create([
            'section' => 'management-console',
            'preference' => 'uitgeschakelde grafieken',
            'description' => 'Ik wens geen grafieken te zien in de beheersconsole.',
        ]);
    }
}
