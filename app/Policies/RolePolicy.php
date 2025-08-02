<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function delete(User $user, Role $role): bool
    {
        return false;
    }
}
