<?php

declare(strict_types=1);

namespace App\Features;

use App\UserTypes;
use Illuminate\Support\Lottery;

final readonly class GuestEditors
{
    public function resolve(mixed $scope): mixed
    {
        return auth()->user()->user_type->in(enums: [UserTypes::Developer]);
    }
}
