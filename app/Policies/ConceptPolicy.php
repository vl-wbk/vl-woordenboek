<?php

namespace App\Policies;

use App\Models\Concept;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class ConceptPolicy
 *
 * This class serves as the central authority for authorizing actions against Concept models.
 *
 * ARCHITECTURE NOTE:
 * The security model for this policy is built on strict resource ownership.
 * To prevent ID enumeration and data leakage, unauthorized requests return a '404 Not Found'
 * response via `denyAsNotFound()` instead of a '403 Forbidden'. This ensures that
 * existence of a resource is only disclosed to its authenticated author.
 *
 * @package App\Policies
 */
final readonly class ConceptPolicy
{
    /**
     * Update Authorization
     *
     * Handles permission to modify a concept.
     *
     * Future dev note: we only allow the original author to make edits.
     * If we ever introduce "Moderators" of "Admins", this is where you'll want
     * to add an additional '|| $user->hasRole('admin')' check.
     *
     *
     * @param  User    $user    The person trying to edit.
     * @param  Concept $concept The concept being targeted.
     * @return Response         Allows if they own it, otherwise throws a HTTP - 404.
     */
    public function update(User $user, Concept $concept): Response
    {
        return $concept->authoredBy($user) ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Deletion Authorization
     *
     * Prevents users from deleting each other's work.
     *
     * Keep in mind: because we use 'denyAsNotFound()', the UI should ideally not even show
     * a delete button for items the user doesn't own, as the error message won't explain
     * "why" the action failed.
     *
     * @param  User     $user    The entoty trying to delete the entity.
     * @param  Concept  $concept The concept to be removed.
     * @return Response          Allows if they own it, otherwise throws a HTTP - 404.
     */
    public function delete(User $user, Concept $concept): Response
    {
        return $concept->authoredBy($user) ? Response::allow() : Response::denyAsNotFound();
    }

    /**
     * Submission Authorization
     *
     * This us a custom action for the "Submit" workflow.
     * It ensures that only the creator can move a concept out of its concept state
     * and into the next phase of the lifecycle.
     *
     * @param  User    $user    The person submitting the work.
     * @param  Concept $concept The concept to be submitted.
     * @return Response         Allows if they own it, otherwiser throws a HTTP - 404
     */
    public function submitConcept(User $user, Concept $concept): Response
    {
        return $concept->authoredBy($user) ? Response::allow() : Response::denyAsNotFound();
    }
}
