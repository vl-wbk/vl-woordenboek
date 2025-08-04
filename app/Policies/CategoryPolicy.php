<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @todo Document this method
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

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_category');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->can('view_category');
    }

    public function create(User $user): bool
    {
        return $user->can('create_category');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->can('update_category');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can('delete_category');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_category');
    }
}
