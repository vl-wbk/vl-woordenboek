<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

/**
 * Defines the authorization logic for the Role model.
 *
 * This policy governs what actions a User can perform on Role records, such as viewing, creating, updating, and deleting.
 * It integrates with the Spatie Permission package to check for specific permissions granted to the authenticated user.
 *
 * The before method acts as a gatekeeper, ensuring the user has access to the "User Management" page before any other policy checks are performed.
 *
 * @link file://tests/Unit/Authorization/RolePolicyTest.php
 * @package App\Policies
 */
final readonly class RolePolicy
{
	/**
	 * Acts as a "fail-fast" security check before any other policy method is called.
	 *
	 * This is a critical first step. We immediately deny a user's request if they don't have the basic `page_UserManagement` permission.
	 * By returning a `404 Not Found` response, we prevent unauthorized users from even knowing that a role management feature exists, which is a great security practice.
	 *
	 * @param  User 	$user 		The authenticated user instance.
	 * @param  string 	$ability 	The specific policy ability being checked (e.g., 'viewAny').
	 * @return Response|null 		Returns a `Response::denyAsNotFound()` if the user is unauthorized, otherwise `null` to let the specific policy method run.
	 */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->cannot('page_UserManagement')) {
            return Response::denyAsNotFound();
        }

        return null;
    }
	
	/**
	 * Handles the authorization for fetching a collection of roles.
	 *
	 * This method is triggered when a user wants to see a list of all available roles.
	 * It's the check that lets them access the role table or a dropdown menu for
	 * selecting a role. The user needs the `view_any_role` permission to be allowed.
	 *
	 * @param  User $user   The authenticated user instance.
	 * @return Response 	Returns a `Response::allow()` if the user can see multiple roles, otherwise a `Response::deny()`.
	 */
    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_role')) {
			return Response::allow();
		};
		
		return Response::deny();
    }
	
	/**
	 * Authorizes the action of viewing a single, specific role.
	 *
	 * This policy is invoked when a user wants to view the details of one particular role.
	 * For instance, clicking on a role's name in a list to see its profile and assigned permissions would trigger this check. The `view_role` permission is required.
	 *
	 * @param  User $user 	The authenticated user instance.
	 * @param  Role $role 	The role model instance being viewed.
	 * @return Response 	Returns a `Response::allow()` if the user can view the specific role, otherwise a `Response::deny()`.
	 */
    public function view(User $user, Role $role): Response
    {
        if ($user->can('view_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
	
	/**
	 * Determines if a user is allowed to create a new role.
	 *
	 * This is the check that a user must pass before they can access the form to create a new role.
	 * It ensures that only authorized users can add new roles to the system.
	 * The `create_role` permission is the key here.
	 *
	 * @param  User $user 	The authenticated user instance.
	 * @return Response	 	Returns a `Response::allow()` if the user can create roles, otherwise a `Response::deny()`.
	 */
    public function create(User $user): Response
    {
        if ($user->can('create_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
	
	/**
	 * Handles authorization for updating an existing role.
	 *
	 * This policy is checked when a user tries to modify an existing role's name or permissions.
	 * It's the gatekeeper for the "edit role" capability and ensures that only users with the `update_role` permission can make changes.
	 *
	 * @param  User $user 	The authenticated user instance.
	 * @param  Role $role 	The role model instance being updated.
	 * @return Response 	Returns a `Response::allow()` if the user can update the role, otherwise a `Response::deny()`.
	 */
    public function update(User $user, Role $role): Response
    {
        if ($user->can('update_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
	
	/**
	 * Authorizes the deletion of a single role.
	 *
	 * This method is called when a user attempts to delete just one role—for example, by clicking a "delete" button next to a role in a list.
	 * The `delete_role` permission is required to perform this permanent action.
	 *
	 * @param  User $user 	The authenticated user instance.
	 * @param  Role $role 	The role model instance being deleted.
	 * @return Response 	Returns a `Response::allow()` if the user can delete the role, otherwise a `Response::deny()`.
	 */
    public function delete(User $user, Role $role): Response
    {
        if ($user->can('delete_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
	
	/**
	 * Governs the ability to delete multiple or any roles.
	 *
	 * This is a special permission check for bulk actions. It's invoked when a user selects multiple roles and tries to delete them all at once.
	 * The `delete_any_role` permission provides a separate, higher level of control for these kinds of operations.
	 *
	 * @param  User $user 	The authenticated user instance.
	 * @return Response 	Returns a `Response::allow()` if the user can perform a mass deletion, otherwise a `Response::deny()`.
	 */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_role')) {
			return Response::allow();
		}
		
		return Response::deny();
    }
}
