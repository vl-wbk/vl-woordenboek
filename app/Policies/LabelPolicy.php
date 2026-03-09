<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * LabelPolicy enforces authorization rules for label management in the Vlaams Woordenboek.
 *
 * This policy class defines access control for all label-related operations, ensuring that only users with appropriate
 * permissions can modify the dictionary's taxonomic structure. It implements a hierarchy where core administrative tasks
 * (create, delete, update) are often restricted, while relationship management (attach, detach) may be granted to a broader editor base.
 *
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php Link to the corresponding unit tests.
 * @package App\Policies
 */
final class LabelPolicy
{
    /**
     * The list of **Action Names** used to construct the full Permissions required by this policy.
     *
     * These prefixes are combined with the resource identifier (':label') to form the specific permission string (e.g., 'attach:label', 'delete-any:label').
     * This array is essential for seeding or administering the required permissions.
     *
     * @var list<string> The list of canonical action names.
     */
    public static array $permissionPrefixes = [
        'deleteAny', 'detach', 'attach', 'create', 'delete', 'update', 'view', 'viewAny',
    ];

    /**
     * Determines whether the user can view the list or overview of all labels.
     *
     * Required Permission:** 'view-any:label'.
     * This grants general access to the label management screen where all taxonomic records are listed.
     *
     * @param  User $user  The authenticated user instance.
     * @return Response    Grants access if the required permission is present.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view-any:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemmin om de lijst met labels te bekijken.');
    }

    /**
     * Determines whether the user can view the details of a specific label.
     *
     * Required Permission: 'view:label'.
     * This check is performed before retrieving the details of a single Label model, ensuring the user has the general right to view this resource.
     *
     * @param  User $user    The authenticated user instance.
     * @param  Label $label  The specific label record being viewed.
     * @return Response      Grants access if the required permission is present.
     */
    public function view(User $user, Label $label): Response
    {
        if ($user->can('view:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om de details van dit label te bekijken.');
    }

    /**
     * Determines whether a user can update existing labels.
     *
     * Required Permission: update:label'.
     * Updates to labels are restricted to maintain consistency in the dictionary's taxonomic structure.
     * This ensures that label modifications are carefully controlled and properly managed.
     *
     * @param  User  $user   The authenticated user instance attempting the action.
     * @param  Label $label  The specific label record being updated.
     * @return Response      Grants access if the required permission is present.
     */
    public function update(User $user, Label $label): Response
    {
        if ($user->can('update:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om labels te wijzigen.');
    }

    /**
     * Determines whether a user can delete labels from the system (single delete).
     *
     * Required Permission: 'delete:label'.
     * Label deletion is a sensitive operation that could affect multiple articles.
     * This restriction helps prevent accidental removal of important categorization structures.
     *
     * @param  User $user   The authenticated user instance attempting the action.
     * @return Response     Grants access if the required permission is present.
     */
    public function delete(User $user): Response
    {
        if ($user->can('delete:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om dit label uit het systeem te verwijderen.');
    }

    /**
     * Determines whether a user can create new labels.
     *
     * Required Permission: 'create:label'.
     * Creation of new labels is limited to ensure the taxonomy remains organized and follows established naming conventions.
     * This centralized control helps maintain a coherent categorization system.
     *
     * @param  User $user  The authenticated user instance attempting the action.
     * @return Response     Grants access if the required permission is present.
     */
    public function create(User $user): Response
    {
        if ($user->can('create:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om labels aan te maken.');
    }

    /**
     * Determines whether a user can attach labels to articles (or other related models).
     *
     * Required Permission: 'attach:label'.
     * This permission is crucial for content organization. Unlike creation or deletion, this action is typically
     * granted to a broader group of users, like editors, to facilitate content categorization.
     *
     * @param  User $user  The authenticated user instance attempting the action.
     * @return Response    Grants access if the required permission is present.
     */
    public function attach(User $user): Response
    {
        if ($user->can('attach:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om labels aan artikelen te koppelen.');
    }

    /**
     * Determines whether a user can detach labels from articles (or other related models).
     *
     * Required Permission: 'detach:label'.
     * Similar to attachment, detachment allows for flexible content organization while ensuring proper oversight of taxonomy management.
     *
     * @param  User  $user   The authenticated user instance attempting the action.
     * @param  Label $label  The specific label record being detached. (Note: The specific label object is optional for permission check).
     * @return Response      Grants access if the required permission is present.
     */
    public function detach(User $user, Label $label): Response
    {
        if ($user->can('detach:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om dit label van het artikel los te koppelen.');
    }

    /**
     * Determines whether a user can delete multiple labels in bulk.
     *
     * Required Permission: 'delete-any:label'.
     * This provides a separate, usually higher-level, permission for actions that involve mass deletion of label records.
     *
     * @param  User $user  The authenticated user instance attempting the action.
     * @return Response    Grants access if the required permission is present.
     */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete-any:label')) {
            return Response::allow();
        }

        return Response::deny(message: 'U heeft geen toestemming om meerdere labels tegelijk te verwijderen.');
    }
}
