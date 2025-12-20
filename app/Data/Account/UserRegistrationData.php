<?php

declare(strict_types=1);

namespace App\Data\Account;

use Spatie\LaravelData\Data;

/**
 * @todo Write docblock for this DTI
 */
final class UserRegistrationData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $firstname = null,
        public readonly ?string $lastname = null,
    ) {}
}
