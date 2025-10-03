<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Role;

final class RolePolicy
{
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'create', 'update', 'delete'
    ];

    public function viewAny(AuthUser $authUser): Response
    {
        if ($authUser->can('view-any:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function view(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('view:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function create(AuthUser $authUser): Response
    {
        if ($authUser->can('create:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function update(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('update:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function delete(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('delete:role')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
