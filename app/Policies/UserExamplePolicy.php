<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{User, UserExample};
use App\UserTypes;
use Illuminate\Auth\Access\Response;

/**
 * Class UserExamplePolicy
 *
 * This class serves as the central authority for authorizing actions against UserExample models.
 *
 * ARCHITECTURE NOTE:
 * Authorization in this policy is strictly Role-Based (RBAC). Access is restricted to
 * high-level administrative tiers: Developers, Administrators, and Editors-in-Chief.
 * If the organization structure changes, this logic should be updated to check for
 * specific permissions rather than hardcoded UserTypes.
 *
 * @package App\Policies
 */
final readonly class UserExamplePolicy
{
    /**
     * Action constants for granular ability referencing.
     * Use these in Gate::allows() or $user->can() to avoid magic string.
     */
    public const string changeState = "change-state";
    public const string changeStateAny = "change-state-any";

    public function viewAny(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om de lijst met gebruikersvoorbeelden te raadplegen.");
    }

    /**
     * Index Access Control
     *
     * This method validates whether a user has the administrative standing required to view the global list of
     * user examples. It is typically invoked at the controller level before executing a collection query.
     *
     * @param  User $user The entity of the authenticated user instance.
     * @return Response   Returns an allow response for elevated roles; otherwise, return a Dutch denial message.
     */
    public function deleteAny(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om gebruikersvoorbeelden te verwijderen.");
    }

    /**
     * Bulk Deletion Control
     *
     * This gate protects the destructive action of removing multiple user examples at once.
     * Given the high impact of this action, it is strictly limited to top-tier management roles.
     *
     * @param  User $user The entity of the authenticated user instance.
     * @return Response   Returns an allow response for elevated roles; otherwise, return a Dutch denial message.
     */
    public function update(User $user, UserExample $userExample): Response
    {
        return $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om dit gebruikersvoorbeeld te bewerken.");
    }

    /**
     * Instance Modification Control
     *
     * Authorizes the ability to update data for a specific user example instance.
     * Note that there is currently no "owner" check; any authorized administrator can edit any example.
     *
     * @param  User        $user        The entity of the authenticated user instance,
     * @param  UserExample $userExample The specific record being modified.
     * @return Response                 Returns an allow response for elevated roles; otherwise, return a Dutch denial message.
     */
    public function changeState(User $user, UserExample $userExample): Response
    {
        return $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om de status van dit gebruikersvoorbeeld te wijzigen.");
    }

    /**
     * Global State Transition Control
     *
     * A broader version of the state check, used when determing if a user has the general right to perform state
     * transitions across the entire model type.
     *
     * @param  User $user The entity of the authenticated user.
     * @return Response   Returns an allow response for elevated roles; otherwise, return a Dutch denial message.
     */
    public function changeStateAny(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])
            ? Response::allow()
            : Response::deny(message: __("U heeft geen toestemming om de status van gebruikersvoorbeelden te wijzigen."));
    }
}
