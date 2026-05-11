<?php

declare(strict_types=1);

namespace App\Data\Account;

use App\Attributes\Todo;
use Spatie\LaravelData\Data;


#[Todo(message: 'Provide docblocks for this class and their methods', priority: 'low')]
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
