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
        if ($user->can('view_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }

    public function create(User $user): Response
    {
        if ($user->can('create_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }

    public function update(User $user, Role $role): Response
    {
        if ($user->can('update_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }

    public function delete(User $user, Role $role): Response
    {
        if ($user->can('delete_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }

    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
}
