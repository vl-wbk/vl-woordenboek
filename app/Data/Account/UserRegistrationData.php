<?php

declare(strict_types=1);

namespace App\Data\Account;

use Spatie\LaravelData\Data;

final class UserRegistrationData extends Data
{
    public function __construct(
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $email,
        public readonly string $password,
    ) {}
}
