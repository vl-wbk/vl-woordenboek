<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Defines the comprehensive authorization logic for the Category model.
 *
 * This policy acts as the security layer for all actions related to blog categories.
 * It's the gatekeeper that works with our application's permissions system (User::can()) to ensure that only authenticated and authorized users can view, create, update, or delete categories.
 *
 * Each method checks for a specific, granular permission (e.g., 'create:category').
 *
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php - The file path to the corresponding unit tests.
 * @package App\Policies
 */
final class CategoryPolicy
{
    /**
     * A list of standard prefixes used for category-related permissions.
     *
     * These prefixes map directly to the conventional policy methods and are combined with the resource name (':category') to form the full permission string.
     * This list is instrumental for tasks such as seeding permissions into the database or displaying required permissions in a user interface.
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'view', 'viewAny', 'create', 'update', 'delete', 'deleteAny',
    ];

    /**
     * Determine whether the user can view a collection of categories (Index/List).
     *
     * Authorization Logic:
     *
     * Checks for the 'view-any:category' permission.
     * This policy is triggered when a user tries to access a list of all categories.
     * For example, this is the check that allows them to see the category management page or a dropdown list of categories.
     *
     * @param  User $user  The authenticated user instance.
     * @return Response    Returns an authorization response, allowing or denying access.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('view-any:category')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can view a specific category instance (Show/Detail).
     *
     * Authorization Logic:
     *
     * Checks for the 'view:category' permission.
     * This method is used before fetching the details of a single Category model, ensuring the user has the general privilege to view category data.
     *
     * @param  User     $user      The authenticated user instance.
     * @param  Category $category  The specific category instance being viewed.
     * @return Response            Returns an authorization response, allowing or denying access.
     */
    public function view(User $user, Category $category): Response
    {
        return $user->can('view:category')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can create a new category (Store).
     *
     * Authorization Logic:
     *
     * Checks for the 'create:category' permission.
     * This check runs before processing a request to save a new category, typically triggered when validating access to the category creation route or form.
     *
     * @param  User $user  The authenticated user instance.
     * @return Response    Returns an authorization response, allowing or denying access.
     */
    public function create(User $user): Response
    {
        return $user->can('create:category')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update a specific category instance (Update/Edit).
     *
     * Authorization Logic:
     *
     * Checks for the 'update:category' permission.
     * This method is invoked when attempting to modify the details of an existing category, ensuring the user has the required privilege for making changes to this resource type.
     *
     * @param  User     $user      The authenticated user instance.
     * @param  Category $category  The specific category instance being updated.
     * @return Response            Returns an authorization response, allowing or denying access.
     */
    public function update(User $user, Category $category): Response
    {
        return $user->can('update:category')
            ? Response::allow()
            : Response::deny();
    }


    /**
     * Determine whether the user can delete a specific category instance (Destroy).
     *
     * Authorization Logic:
     *
     * Checks for the 'delete:category' permission.
     * This is a critical check that prevents unauthorized users from performing irreversible removal of an existing category record from the database.
     *
     * @param  User     $user      The authenticated user instance.
     * @param  Category $category  The specific category instance being deleted.
     * @return Response            Returns an authorization response, allowing or denying access.
     */
    public function delete(User $user, Category $category): Response
    {
        return $user->can('delete:category')
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete multiple categories in bulk (Bulk Delete).
     *
     * Authorization Logic:
     *
     * Checks for the 'delete-any:category' permission.
     * This method is used to control access to operations that affect multiple records simultaneously, offering a higher level of control than the single 'delete:category' permission.
     *
     * @param  User $user  The authenticated user instance.
     * @return Response    Returns an authorization response, allowing or denying access.
     */
    public function deleteAny(User $user): Response
    {
        return $user->can('delete-any:category')
            ? Response::allow()
            : Response::deny();
    }
}
