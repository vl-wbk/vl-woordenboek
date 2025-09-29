<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Defines the comprehensive authorization logic for the category model.
 *
 * This policy acts as the security layer for all actions related to blog categories.
 * It's the gatekeeper that works with our permissions system to ensure that only authenticated and authorized users can view, create, update, or delete categories.
 *
 * The before method is our first line of defense, acting as a quick gatekeeper for the entire "blog" section of the app.
 *
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php
 * @packlage App\Policies
 */
final readonly class CategoryPolicy
{
	/**
	 * Acts as a "fail-fast" gatekeeper for all category-related actions.
	 *
	 * This method is the first check that runs for any category action.
	 * It immediately denies access and returns a '404 Not Found' response if the user doesn't have the foundational 'page_Blog' permission.
	 * This is a smart security practice because it prevents unauthorized users from even knowing that a blog management section exists.
	 *
	 * @param  User $user		The authenticated user instance.
	 * @return Response|null	Returns a `Response::denyAsNotFound()` if the user is not authorized to access the blog section, otherwise `null` to let the specific policy method run.
	 */
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Blog')) {
            return Response::denyAsNotFound();
        }

        return null;
    }
	
	/**
	 * Determines whether the user can view a collection of categories.
	 *
	 * This policy is triggered when a user tries to access a list of all categories.
	 * For example, this is the check that allows them to see the category management page or a dropdown list of categories.
	 * The user needs the 'view_any_category' permission to be allowed.
	 *
	 * @param  User $user	The authenticated user instance.
	 * @return Response		Returns a `Response::denyAsNotFound()` if the user is not authorized to access the blog section, otherwise `null` to let the specific policy method run.
	 */
    public function viewAny(User $user): Response
    {
        return $user->can('view_any_category')
			? Response::allow()
			: Response::deny();
    }
	
	/**
	 *
	 * @param User $user
	 * @param Category $category
	 * @return Response
	 */
    public function view(User $user, Category $category): Response
    {
        return $user->can('view_category')
			? Response::allow()
			: Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->can('create_category')
			? Response::allow()
			: Response::deny();
    }

    public function update(User $user, Category $category): Response
    {
        return $user->can('update_category')
			? Response::allow()
			: Response::deny();
    }

    public function delete(User $user, Category $category): Response
    {
        return $user->can('delete_category')
			? Response::allow()
			: Response::deny();
    }

    public function deleteAny(User $user): Response
    {
        return $user->can('delete_any_category')
			? Response::allow()
			: Response::deny();
    }
}
