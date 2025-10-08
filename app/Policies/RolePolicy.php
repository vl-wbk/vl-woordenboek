<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Role;

/**
 * Policy for handling authorization rules related to the Spatie 'Role' model.
 *
 * This class serves as the primary access control mechanism for all interactions with the Role resource within the application.
 * It strictly adheres to the standard Laravel policy structure, making its methods automatically discoverable and resolvable by Laravel's built-in Gate functionality and Controller authorization checks.
 *
 * Core responsibilities:
 *
 * 1. Gate integration: Provides specific methods (e.g., `viewAny`, `create`) that map directly to authorization actions checked by the `Gate` facade or `authorize()` controller helper.
 * 2. Permission check: Delegates all authorization decisions to the underlying Spatie `can()` method on the authenticated user, checking for granular permissions like 'update:role' or 'delete:role'.
 * 3. Resource Protection: Ensures robust, granular access control, preventing unauthorized users from viewing, modifying, or deleting role data.
 *
 * This policy typically acts as a high-level security guard, granting or denying access based solely on the presence of required permissions, without implementing complex business logic inside the policy itself.
 */
final class RolePolicy
{
    /**
     * A list of standard prefixes used for role-related permissions.
     * These prefixes map directly to the conventional names for resource controller methods (e.g., index, show, store, update, destroy).
     *
     * They are combined with ':role' to form the full permission name (e.g., 'viewAny:role', 'create:role').
     * This list is essential for centralized management and potentially automated generation or listing of all available permissions in an administration interface.
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'create', 'update', 'delete',
    ];

    /**
     * Determine whether the given user can view a list of roles (View Any).
     *
     * Authorization Logic:
     *
     * Checks for the 'view-any:role' permission.
     * This method is executed when using `Gate::allows('viewAny', Role::class)`.
     * It is typically used before fetching a collection of all available roles to ensure the user has the general privilege to access the resource list.
     *
     * @param  AuthUser $authUser  The authenticated user attempting the action. This object is an instance of the class defined by AuthUser.
     * @return Response            The authorization response, allowing or denying access. Uses Response::allow() on success, and Response::deny() with a reason on failure.
     */
    public function viewAny(AuthUser $authUser): Response
    {
        if ($authUser->can('view-any:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the given user can view a specific role (View).
     *
     * Authorization Logic:
     *
     * Checks for the 'view:role' permission.
     * This method is executed when using `Gate::allows('view', $role)`.
     * It is used when attempting to fetch the details of a single **Role model instance**.
     * Since the permission is generic ('view:role'), it grants access to view *any* role, not just a specific one.
     *
     * @param  AuthUser $authUser  The authenticated user attempting the action.
     * @param  Role     $role      The specific Role model instance to be viewed.
     * @return Response            The authorization response, allowing or denying access.
     */
    public function view(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('view:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the given user can create a new role (Create).
     *
     * Authorization Logic:
     *
     * Checks for the 'create:role' permission.
     * This method is executed when using `Gate::allows('create', Role::class)`.
     * It typically checks the user's privilege before showing a role creation form or processing the form data to persist a new role to the database.
     *
     * @param  AuthUser $authUser  The authenticated user attempting the action.
     * @return Response            The authorization response, allowing or denying access.
     */
    public function create(AuthUser $authUser): Response
    {
        if ($authUser->can('create:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the given user can update a specific role (Update).
     *
     * Authorization Logic:
     *
     * Checks for the 'update:role' permission.
     * This method is executed when using `Gate::allows('update', $role)`.
     * It is used when attempting to modify the properties of an **existing Role model**.
     * This permission ensures the user can perform changes across the entire resource type.
     *
     * @param  AuthUser $authUser  The authenticated user attempting the action.
     * @param  Role     $role      The specific Role model instance to be updated.
     * @return Response            The authorization response, allowing or denying access.
     */
    public function update(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('update:role')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the given user can delete a specific role (Delete).
     *
     * Authorization Logic:
     *
     * Checks for the 'delete:role' permission.
     * This method is executed when using `Gate::allows('delete', $role)`.
     * It is used when attempting to remove an **existing Role model** from the database.
     * This is a critical check, as deletion is often an irreversible action.
     *
     * @param  AuthUser $authUser  The authenticated user attempting the action.
     * @param  Role     $role      The specific Role model instance to be deleted.
     * @return Response            The authorization response, allowing or denying access.
     */
    public function delete(AuthUser $authUser, Role $role): Response
    {
        if ($authUser->can('delete:role')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
