<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ChecksAuthorizationBasedOnStatus
{
    public static function performActionBasedOnStatus(Model $model): bool;
}
