<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Attributes\Todo;
use App\Models\User;
use App\UserTypes;
use Illuminate\Database\Seeder;

#[Todo(message: 'Write docblocks for this class and the methods', priority: 'low')]
final class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        collect(UserTypes::cases())->each(function (UserTypes $userType): void {
            User::factory()->create(attributes: ['email' => "{$userType->getLabel()}@domain.tld", 'user_type' => $userType->value]);
        });
    }
}
