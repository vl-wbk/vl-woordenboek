<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php
 */
final readonly class CategoryPolicy
{
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Blog')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $user->can('view_any_category')
			? Response::allow()
			: Response::deny();
    }

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
