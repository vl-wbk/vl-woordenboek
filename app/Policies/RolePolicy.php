<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

/**
 * @link file://tests/Unit/Authorization/RolePolicyTest.php
 * @package App\Policies
 */
final readonly class RolePolicy
{
    public function before(User $user, string $ability): ?Response
    {
        if ($user->cannot('page_UserManagement')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_role')) {
			return Response::allow();
		};
		
		return Response::deny();
    }

    public function view(User $user, Role $role): Response
    {
        return $user->can('view_role');
    }

    public function create(User $user): bool
    {
        return $user->can('create_role');
    }

    public function update(User $user, Role $role): Response
    {
        return $user->can('update_role');
    }

    public function delete(User $user, Role $role): Response
    {
        return $user->can('delete_role');
    }

    public function deleteAny(User $user): Response
    {
        return $user->can('delete_any_role');
    }
}
