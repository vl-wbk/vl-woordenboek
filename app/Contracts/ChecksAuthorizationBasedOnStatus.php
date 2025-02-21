<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
interface ChecksAuthorizationBasedOnStatus
{
    /**
     * Undocumented function
     *
     * @param  TModel $model
     * @return bool
     */
    public static function performActionBasedOnStatus(Model $model): bool;
}
