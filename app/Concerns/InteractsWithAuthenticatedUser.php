<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\User;

trait InteractsWithAuthenticatedUser
{
    public function authenticatedUser(): User
    {
        return auth()->user();
    }
}
