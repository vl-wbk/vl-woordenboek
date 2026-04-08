<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\UserTypes;

trait ManagesUserGroups
{
    public function isDeveloper(): bool
    {
        return $this->user_type->is(UserTypes::Developer);
    }
}
