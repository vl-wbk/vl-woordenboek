<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * CategoryPolicy enforces authorization rules for category management in the Flemish dictionary.
 *
 * This policy class defines access control for all category-related operations, ensuring that only users with appropriate permissions can manage categories.
 * It implements a strict permission hierarchy to maintain the integrity of the system's taxonomy.
 *
 * @package App\Policies
 */
final readonly class CategoryPolicy
{
    /**
     * Executes before any other policy method to enforce global access restrictions.
     *
     * This method checks whether the user has access to the "Blog" page.
     * If the user lacks the required permission (`page_Blog`), all subsequent policy checks are bypassed, and the request is denied
     * with a localized message explaining the lack of authorization.
     *
     * @param  User $user The currently authenticated user.
     * @return Response|null A denial response if the user lacks access, or null to proceed with other checks.
     */
    public function before(User $user): ?Response
    {
        if ($user->can('page_Blog')) {
            return null;
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_before_message', replace: [
                'resource' => __('authorization.resources.categories'),
            ])
        );
    }

    /**
     * Determines whether a user can view a list of all categories.
     *
     * This method checks if the user has the `view_any_category` permission.
     * If the user has the required permission, the action is allowed. Otherwise, the request is denied with a localized message.
     *
     * @param  User $user The currently authenticated user.
     * @return Response   A response indicating whether the action is allowed or denied.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_category')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_view_any_message', replace: [
                'resource' => __('authorization.resources.categories'),
            ]),
        );
    }

    /**
     * Determines whether a user can view a specific category.
     *
     * This method checks if the user has the `view_category` permission.
     * If the user has the required permission, the action is allowed. Otherwise, the request is denied with a localized message.
     *
     * @param  User     $user     The currently authenticated user.
     * @param  Category $category The category being viewed.
     * @return Response           A response indicating whether the action is allowed or denied.
     */
    public function view(User $user, Category $category): Response
    {
        if ($user->can('view_category')) {
            Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_view_message', replace: [
                'resource' => __('authorization.resources.category'),
            ]),
        );
    }

    /**
     * Determines whether a user can create a new category.
     *
     * This method checks if the user has the `create_category` permission. If the user has the required permission, the action is allowed.
     * Otherwise, the request is denied with a localized message explaining the lack of authorization.
     *
     * @param  User $user The currently authenticated user.
     * @return Response   A response indicating whether the action is allowed or denied.
     */
    public function create(User $user): Response
    {
        if ($user->can('create_category')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_create_message', replace: [
                'resource' => __('authorization.resources.category'),
            ])
        );
    }

    /**
     * Determines whether a user can update an existing category.
     *
     * This method checks if the user has the `update_category` permission. If the user has the required permission, the action is allowed.
     * Otherwise, the request is denied with a localized message explaining the lack of authorization.
     *
     * @param  User     $user     The currently authenticated user.
     * @param  Category $category The category being updated.
     * @return Response           A response indicating whether the action is allowed or denied.
     */
    public function update(User $user, Category $category): Response
    {
        if ($user->can('update_category')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_update_message', replace: [
                'resource' => __('authorization.resources.category'),
            ]),
        );
    }

    /**
     * Determines whether a user can delete an existing category.
     *
     * This method checks if the user has the `delete_category` permission. If the user has the required permission, the action is allowed.
     * Otherwise, the request is denied with a localized message explaining the lack of authorization.
     *
     * @param  User     $user     The currently authenticated user.
     * @param  Category $category The category being deleted.
     * @return Response           A response indicating whether the action is allowed or denied.
     */
    public function delete(User $user, Category $category): Response
    {
        if($user->can('delete_category')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_message', replace: [
                'resource' => __('authorization.resources.category'),
            ]),
        );
    }

    /**
     * Determines whether a user can delete multiple categories at once.
     *
     * This method checks if the user has the `delete_any_category` permission. If the user has the required permission, the action is allowed.
     * Otherwise, the request is denied with a localized message explaining the lack of authorization.
     *
     * @param  User $user The currently authenticated user.
     * @return Response   A response indicating whether the action is allowed or denied.
 */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_category')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_any_message', replace: [
                'resource' => __('authorization.resources.categories'),
            ]),
        );
    }
}
