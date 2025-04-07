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

    /**
     * @todo Need to check which default logins should be applied for seeding the production database
     */
    public function seedDataForProductionPurposes(): void
    {
        collect(UserTypes::cases())->each(function (UserTypes $userType) {
            User::factory()->create(attributes: ['email' => "{$userType->getLabel()}@domain.tld", 'user_type' => $userType->value]);
        });
    }

    private function determineSeedingEnvironment(): bool
    {
        return Config::boolean('app.debug', false) || app()->environment(['local', 'testing']);
    }

    private function seedDataForLocalPurposes(): void
    {
        collect(UserTypes::cases())->each(function (UserTypes $userType) {
            User::factory()->create(attributes: ['email' => "{$userType->getLabel()}@domain.tld", 'user_type' => $userType->value]);
        });
    }
}
