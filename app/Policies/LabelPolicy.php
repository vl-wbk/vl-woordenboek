<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * LabelPolicy enforces authorization rules for label management in the Vlaams Woordenboek.
 *
 * This policy class defines access control for all label-related operations, ensuring that only users with appropriate permissions can modify the dictionary's taxonomic structure.
 * The policy implements a strict permission hierarchy where administrative tasks are restricted to administrators and developers, while relationship management extends to chief editors.
 *
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php
 * @package App\Policies
 */
final readonly class LabelPolicy
{
    /**
     * @todo Document polociy method.
     */
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    /**
     * @todo Document policy
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_label');
    }

    /**
     * @todo Document policy
     */
    public function view(User $user, Label $label): bool
    {
        return $user->can('view_label');
    }

    /**
     * Determines whether a user can update existing labels.
     *
     * Updates to labels are restricted to administrators and developers to maintain consistency in the dictionary's taxonomic structure.
     * This ensures that label modifications are carefully controlled and properly managed.
     *
     * @param  User $user  The eloquent instance from the currently authenticated user.
     */
    public function update(User $user, Label $label): bool
    {
        return $user->can('update_label');
    }

    /**
     * Determines whether a user can delete labels from the system.
     *
     * Label deletion is a sensitive operation that could affect multiple articles, thus it is restricted to administrators and developers.
     * This helps prevent accidental removal of important categorization structures.
     *
     * @param  User $user  The eloquent instance from the currently authenticated user.
     */
    public function delete(User $user): bool
    {
        return $user->can('delete_label');
    }

    /**
     * Determines whether a user can create new labels.
     *
     * Creation of new labels is limited to administrators and developers to ensure the taxonomy remains organized and follows established naming conventions.
     * This centralized control helps maintain a coherent categorization system.
     *
     * @param  User $user  The eloquent instance from the currently authenticated user.
     */
    public function create(User $user): bool
    {
        return $user->can('create_label');
    }

    /**
     * Determines whether a user can attach labels to articles.
     *
     * Label attachment permissions extend to editors and chief editors in addition to administrators and developers.
     * This broader access enables content organization while maintaining appropriate oversight of the categorization process.
     *
     * @param  User $user  The eloquent instance from the currently authenticated user.
     */
    public function attach(User $user): bool
    {
        return $user->can('attach_label');
    }

    /**
     * Determines whether a user can detach labels from articles.
     *
     * Similar to attachment permissions, label detachment is available to editors, chief editors, administrators, and developers.
     * This allows for flexible content organization while ensuring proper oversight of taxonomy management.
     *
     * @param  User $user  The eloquent instance from the currently authenticated user.
     */
    public function detach(User $user, Label $label): bool
    {
        return $user->can('detach_label');
    }

    /**
     * @todo Document policy
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_label');
    }
}
