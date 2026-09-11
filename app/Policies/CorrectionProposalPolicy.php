<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{CorrectionProposal, User};
use App\States\Articles\Corrections\{ApprovedState, PendingState, RejectedState};
use App\UserTypes;
use Illuminate\Auth\Access\Response;

/**
 * Policy class to authorize operations on Correction Proposals.
 *
 * This policy handles permissions for reviewing, creating, updating, and transitioning the state of community-submitted article correction proposals.
 * It ensures that standard users can only propose modifications, while internal maintainers retain control over moderation.
 *
 * This policy leverages custom user type enums (UserTypes enum) and a state machine pattern for the proposal lifecycle
 * (ApprovedState, PendingState, RejectedState). Changes to the moderation workflow should maps directly to these states and user permissions.
 *
 * @package App\Policies
 */
final readonly class CorrectionProposalPolicy
{
    /** @var string Ability string to approve a correction proposal. */
    public const string Approve = 'approve';

    /** @var string Ability to reject a correction proposal. */
    public const string Reject = 'reject';

    /**
     * Determine if a user can create a new correction proposal.
     *
     * Allows any authenticated user to submit a correction proposal for review.
     * This forms the entry point of the community contribution pipeline, defaulting new proposals to a 'Pending' status
     * automatically via the database schema or model.
     *
     * @param  User $user The authenticated user attempting to submit a correction.
     * @return Response   Always allowed for authenticated users.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine if a user can see the index dashboard of correction proposals.
     *
     * Restricts the complete overview list strictly to project staff (Administrators, Editors, Editors-in-Chief, and Developers).
     * This prevents non-privileged contributors from scraping or viewing pending data edits submitted by other users.
     *
     * @param  User $user The authenticated user trying to view the listing
     * @return Response   Allowed if user is an internal maintainer, otherwiser return a translated 403 error.
     */
    public function viewAny(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer])
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om een overzicht van correctie te bekijken'));
    }

    /**
     * Determine if a user can inspect invidual correction details.
     *
     * Permitted only for project maintainers, provided that the proposal has already been resolved
     * (meaning it is currently in an ApprovedState or RejectedState). To inspect proposals that are
     * still awaiting review, refer to the update capability instead.
     *
     * @param  User                 $user               The authenticated user trying to view the record.
     * @param  CorrectionProposal   $correctionProposal The specific proposal instance being evaluated.
     * @return Response                                 Allowed if the user is maintainer and the proposal state is finalized.
     */
    public function view(User $user, CorrectionProposal $correctionProposal): Response
    {
        $hasCorrectUserType = $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer]);
        $isEditable = in_array($correctionProposal->state, [ApprovedState::class, RejectedState::class]);

        return ($hasCorrectUserType && $isEditable)
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de gegevens van een correctie te bekijken'));
    }

    /**
     * Determine if a user can modify or update an existing proposal.
     *
     * Maintainers can only update details while the proposal is still awaiting action.
     * Once it moves past a PendingState (i.e., it gets approved or rejected), further updates are frozen to maintain historical audit integrity.
     *
     * @param  User                 $user               The authenticated user attempting the mutation
     * @param  CorrectionProposal   $correctionProposal The specific model instance being evaluated
     * @return Response                                 Allowed if the user is a maintainer and the proposal status is pending
     */
    public function update(User $user, CorrectionProposal $correctionProposal): Response
    {
        $hasCorrectUserType = $user->user_type->in(enums: [
            UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer
        ]);

        $isEditable = in_array($correctionProposal->state, [PendingState::class]);

        return ($hasCorrectUserType && $isEditable)
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de gegevens van een correctie te bekijken'));
    }

    /**
     * Determine if a user has ore privileges to accept and merge proposals.
     *
     * Restricts final approval execution to high-level management and technical roles (Editor-in-Chief, Developer and Administrators).
     * Standard editors can view and triage, but lack authorization to execute the state transition into production data.
     *
     * @param  User $user The authenticated user attempting to approve the change.
     * @return bool       True if authorized to accept and merge modifications. False otherwise.
     */
    public function approve(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::EditorInChief, UserTypes::Developer, UserTypes::Administrators])
            ? Response::allow()
            : Response::deny(message: 'U hebt geen machtiging om correctie voorstellen goed te keuren');
    }

    /**
     * Determine if a user has core privileges to deny and close approsals.
     *
     * Restricts final rejection execution to high-level management and technical roles (Editor-in-Chief, Developer and Administrators).
     * Standard editors can view and triage, but lack authorization to finalize rejection status.
     *
     * @param  User $user The authenticated user attempting to reject the change.
     * @return bool       True if authorized to deny and close modifications, false otherwise.
     */
    public function reject(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::EditorInChief, UserTypes::Developer, UserTypes::Administrators])
            ? Response::allow()
            : Response::deny(message: 'U hebt geen machtiging om correctie voorstellen af te wijzen');
    }
}
