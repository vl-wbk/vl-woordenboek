<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

final class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        match ($this->determineSeedingEnvironment()) {
            true => $this->seedDataForLocalPurposes(),
            false => $this->seedDataForProductionPurposes(),
        };
    }

    private function determineSeedingEnvironment(): bool
    {
        return Config::boolean('app.debug', false) || app()->environment(['local', 'testing']);
    }

    private function seedDataForLocalPurposes(): void
    {
        User::factory()->create(['email' => 'developer@domain.tld', 'name' => 'Developer login']);
        User::factory()->create(['email' => 'administrator@domain.tld', 'name' => 'Administrator login']);
    }

    /**
     * @todo Need to check which default logins should be applied for seeding the production database
     */
    public function seedDataForProductionPurposes(): void
    {
        //
    }
}
