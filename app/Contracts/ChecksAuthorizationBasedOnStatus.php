<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ChecksAuthorizationBasedOnStatus
{
    public static function performActionBasedOnStatus(Model $model): bool;
}
