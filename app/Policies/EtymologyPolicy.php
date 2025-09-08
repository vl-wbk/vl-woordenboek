<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * The EtymologyPolicy class defines the authorization rules for actions on Etymology records.
 *
 * Only users with the roles EditorInChief, Administrators, or Developer are permitted to perform actions on etymologies.
 * Each method in this policy checks the user's role and the current status of the etymology to determine if the action is allowed.
 *
 * For example, only etymologies in draft status can be updated, and only administrators or developers can delete etymologies.
 * The policy ensures that actions such as archiving, rejecting, publishing, moving to draft, and setting under review
 * are only available under the correct conditions, helping to maintain the integrity of the editorial workflow.
 *
 * @link file://tests/Unit/Authorization/EtymologyPolicyTest.php
 * @package App\Policies;
 */
final class EtymologyPolicy
{
    public static array $defaultPermissions = ['view', 'view_any', 'update', 'delete', 'delete_any', 'archive', 'reject', 'publish', 'draft', 'under_review'];

    /**
     * Undocumented function
     *
     * @param  User     $user       The user attempting the action.
     * @param  string   $ability    The name of the policy being checked.
     * @return Response|null        Returns a deny response or null to continu.
     */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    /**
     * @todo Undocumented function
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_etymology');
    }

    /**
     * @todo Undocumented function
     */
    public function view(User $user, Etymology $etymology): bool
    {
        return $user->can('view_etymology');
    }

    /**
     * Determines whether the given user can update the specified etymology.
     *
     * Only etymologies that are currently in the "Draft" status can be updated.
     * This ensures that published or archived etymologies cannot be modified,
     * preserving the integrity of finalized records.
     *
     * @param  User      $user       The user attempting to perform the update action.
     * @param  Etymology $etymology  The etymology instance being considered for update.
     * @return bool                  Returns true if the etymology is in draft status and can be updated; false otherwise.
     */
    public function update(User $user, Etymology $etymology): bool
    {
        return $etymology->status->is(enum: EtymologyStatus::Draft)
            && $user->can('update_etymology');
    }

    /**
     * Determines whether the given user can delete the specified etymology.
     *
     * Only users with the administrators or developer roles are permited to delete etymologies.
     * This restriction helps prevent accidental or unauthorized removal of important records.
     *
     * @param  User       $user         The user attempting the delete action.
     * @param  Etymology  $etymology    The etymology instance being considered for deletion.
     * @return bool                     Returns true if the user is authorized to delete, false otherwise.
     */
    public function delete(User $user, Etymology $etymology): bool
    {
        return $user->can('delete_etymology');
    }

    /**
     * @todo Document policy
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_etymology');
    }

    /**
     * Determines whether the given user can archive the specified etymology.
     *
     * Archiving is only allowed if the etymology is not already archived.
     * This prevents redundant archiving actions and maintains clear status transitions.
     *
     * @param  User       $user         The user attempting to perform the archive action.
     * @param  Etymology  $etymology    The etymology instance being considered for archiving.
     * @return bool                     Returns true if the etymology is not archived; false otherwise.
     */
    public function archive(User $user, Etymology $etymology): bool
    {
        return $etymology->status->isNot(enum: EtymologyStatus::Archived)
            && $user->can('archive_etymology');
    }

    /**
     * Determines whether the given user can reject the specified etymology.
     *
     * Rejection is only allowed if the etymology is currently under review.
     * This ensures that only etymologies in the appropriate workflow stage can be rejected.
     *
     * @param  User         $user       The user attempting to perform the reject action.
     * @param  Etymology    $etymology  The etymology instance being considered for rejection.
     * @return bool                     Returns true if the etymology is under review; false otherwise.
     */
    public function reject(User $user, Etymology $etymology): bool
    {
        return $etymology->status->in(enums: [EtymologyStatus::UnderReview])
            && $user->can('reject_etymology');
    }

    /**
     * Determines whether the given user can publish the specified etymology.
     *
     * Publishing is allowed if the etymology is either under review or archived.
     * This enables the transition of etymologies to a published state from these statuses.
     *
     * @param  User         $user       The user attempting to perform the publishing action.
     * @param  Etymology    $etymology  The etymology instance being considered for publishing.
     * @return bool                     Returns true if the etymology is under review or archived; false otherwise.
     */
    public function publish(User $user, Etymology $etymology): bool
    {
        return $etymology->status->in(enums: [EtymologyStatus::UnderReview, EtymologyStatus::Archived])
            && $user->can('publish_etymology');
    }

    /**
     * Determines whether the given user can move the specified etymology to draft status.
     *
     * This action is allowed if the etymology is currently under review, rejected, or archived.
     * It supports reverting etymologies to draft for further editing or reconsideration.
     *
     * @param  User       $user         The user attempting to perform the draft action.
     * @param  Etymology  $etymology    The etymology instance being considered for draft status.
     * @return bool                     Returns true if the etymology is under review, rejected, or archived; false otherwise.
     */
    public function draft(User $user, Etymology $etymology): bool
    {
        return $etymology->status->in(enums: [EtymologyStatus::UnderReview, EtymologyStatus::Rejected, EtymologyStatus::Archived])
            && $user->can('update_etymology');
    }

    /**
     * Determines whether the given user can move the specified etymology to under review status.
     *
     * Only etymologies in draft status can be moved to under review.
     * This supports the editorial workflow for reviewing new or revised etymologies.
     *
     * @param  User $user            The user attempting to perform the under review action.
     * @param  Etymology $etymology  The etymology instance being considered for under review status.
     * @return bool                  Returns true if the etymology is in draft status; false otherwise.
     */
    public function underReview(User $user, Etymology $etymology): bool
    {
        return $etymology->status->is(enum: EtymologyStatus::Draft)
            && $user->can('under_review_etymology');
    }
}
