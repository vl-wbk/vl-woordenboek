<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\UserTypes;
use Illuminate\Database\Seeder;

/** @todo document */
final class UserTableSeeder extends Seeder
{
    /** @todo document */
    public function run(): void
    {
        collect(UserTypes::cases())->each(function (UserTypes $userType): void {
            User::factory()->create(attributes: ['email' => "{$userType->getLabel()}@domain.tld", 'user_type' => $userType->value]);
        });
    }
}
