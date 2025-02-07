<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\UserTypes;
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
        User::factory()->create(['email' => 'developer@domain.tld', 'firstname' => 'developer', 'lastname' => 'login', 'user_type' => UserTypes::Developer]);
        User::factory()->create(['email' => 'administrator@domain.tld', 'firstname' => 'Administrator', 'lastname' => 'login', 'user_type' => UserTypes::Administrators]);
        User::factory()->create(['email' => 'volunteer@domain.tld', 'firstname' => 'Volunteer', 'lastname' => 'login', 'user_type' => UserTypes::Volunteers]);
    }

    /**
     * @todo Need to check which default logins should be applied for seeding the production database
     */
    public function seedDataForProductionPurposes(): void
    {
        //
    }
}
