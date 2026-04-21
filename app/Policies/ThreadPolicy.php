<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Auth\Access\Response;

/**
 * Defines the comprehensive authorization rules for all interactions within a private messaging thread.
 * This policy enforces strict privacy rules and delegates management responsibilities based on the user's relationship to the thread (Participant vs. Creator).
 *
 * @package App\Policies
 */
final readonly class ThreadPolicy
{
	/**
     * Determines whether the user can view the message thread.
     *
     * Authorization Rule:
     * - The user **MUST** be a current participant in the thread.
     *
     * Rationale:
     * This is a fundamental privacy mechanism. If a user is not listed as a member, they cannot see the thread's content or confirm its existence.
	 * Unauthorized attempts result in a "404 Not Found" response (`denyAsNotFound`) to prevent malicious users from confirming that a thread ID is valid but inaccessible.
     *
     * @param  User   $user    The user attempting the action.
     * @param  Thread $thread  The thread being viewed.
     * @return Response
     */
    public function view(User $user, Thread $thread): Response
	{
	    return $thread->hasParticipant($user->id)
            ? Response::allow()
            : Response::denyAsNotFound();
	}

	/**
     * Determines whether the user can send a new message (reply) to the thread.
     *
     * Authorization Rule:
     * - The user **MUST** be a current participant in the thread.
     *
     * Rationale:
     * Only active members of the discussion are permitted to contribute new messages.
     * Similar to viewing, attempts to reply by unauthorized users are treated as a "404 Not Found" to maintain privacy.
     *
     * @param  User  $user 	   The user attempting the action.
     * @param  Thread $thread  The thread receiving the message.
     * @return Response
     */
	public function reply(User $user, Thread $thread): Response
	{
		return $thread->hasParticipant($user->id)
		    ? Response::allow()
			: Response::denyAsNotFound();
	}

	/**
	 * Determines whether the user dan remove a specific participant from the thread.
	 *
	 * Authorization rule:
	 * - The user MUST be the thread creator.
	 * - The participant being removed MUST NOT be the creator themselves.
	 *
	 * Rationale:
	 * Managemant of thread membership is centralized with the original creator.
	 * This policy prevents the creator from removing themselves, ensuring they maintain responsibility for the thread's structure until it is potentially deleted entirely.
	 *
	 * @param  User 	   $user		The user attempting the removal (must be creator).
	 * @param  Thread 	   $thread		The thread being modified
	 * @param  Participant $participant The participant being removed.
	 * @return Response
	 */
	public function removeParticipants(User $user, Thread $thread, Participant $participant): Response
	{
		if ($thread->creator()->isNot($user)) {
		    return Response::deny(message: __('Alleen de beheerder van dit gesprek kan deelnemers verwijderen.'));
		}

		if ($participant->user()->is($thread->creator())) {
		    return Response::deny(message: __('De beheerder van het gesprek kan niet worden verwijderd.'));
		}

		return Response::allow();
	}

	/**
	 * Determines whether the user can add new participants to the thread.
	 *
	 * Authorization rule:
	 * - The user MUST be the thread creator.
	 * - The total number of participants in the thread MUST be less than 5 (i.e, the thread size limit).
	 *
	 * Rationale:
	 * This centralizes control for expansion with the originator and enforces a strict, system-wide maximum thread size of 5 memebers,
	 * preventing large, unmanageable group chats.
	 *
	 * @param  User   $user		The user attempting the action (must be the creator).
	 * @param  Thread $thread	The thread being modified.
	 * @return Response
	 */
	public function addParticipants(User $user, Thread $thread): Response
	{
		if ($thread->creator()->isNot($user)) {
		    return Response::deny(message: __('Alleen de beheerder van dit gesprek kan nieuwe deelnemers toevoegen.'));
		}

		if ($thread->participants->count() >= 5) {
		    return Response::deny(message: __('De maximale limit van 5 deelnemers voor dit gesprek is bereikt.'));
		}

		return Response::allow();
	}

	/**
	 * Determines whether the user can leave the thread.
	 *
	 * Authorization rule:
	 * - The user MUST be a current participant in the thread.
	 * - The user MUST NOT be the thread creator.
	 *
	 * Rationale:
	 * This allows any regular participant to opt out of a conversation at any time.
	 * The creator is permantly anchored to the thread, consistent with their exclusive management responsibilities.
	 *
	 * @param  User   $user		The user attempting to leave.
	 * @param  Thread $thread	The thread the user is attempting to leave.
	 * @return Response
	 */
	public function leave(User $user, Thread $thread): Response
	{
		if (! $thread->hasParticipant($user->id)) {
		    return Response::denyAsNotFound();
		}

		if ($thread->creator()->is($user)) {
            return Response::deny('Als beheerder kun je dit gesprek niet verlaten.');
        }

        return Response::allow();
	}
}
