<?php

namespace App\Features;

use App\Models\User;
use Illuminate\Support\Lottery;
use Laravel\Pennant\Contracts\Feature;

class EtymologySupport
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user): mixed
    {
        if ($user->isTester()) {
            return true;
        }

        return false;
    }
}
