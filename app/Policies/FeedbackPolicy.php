<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\FeedbackStatus;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy for Feedback Management Access Rules.
 *
 * This class defines the security rules for all user feedback submitted through the application.
 * It acts as the gatekeeper, deciding which administrators are allowed to perform tasks like viewing feedback lists, changing status, or deleting entries, ensuring the system remains secure.
 *
 * All methods here check if the user has been explicitly granted a specific "Permission" (e.g., 'delete-any:feedback') which must be configured in the database.
 * This structure leverages Laravel's automatic policy resolution via the Gate and the authorization checks within Controllers.
 *
 * @link file://tests/Unit/Authorization/BlogPolicyTest.php
 * @package App\Policies
 */
final class FeedbackPolicy
{
    /**
     * The list of **Action Names** used to build the required Permissions.
     *
     * These prefixes are essential for setting up the authorization system.
     * They represent the types of actions an administrator can perform on the feedback records, combined with the resource identifier ':feedback' to form the full permission string.
     * This array is typically used when seeding permissions into the database.
     *
     * @var list<string> The list of canonical action names.
     */
    public static array $permissionPrefixes = ['viewAny', 'view', 'delete', 'deleteAny', 'changeStatus'];

    /**
     * Allows the user to view the main list or overview of all submitted feedback.
     *
     * This requires the 'view-any:feedback' Permission.
     * This grants general access to the Feedback Management screen where all records are displayed (the index page).
     * This check is usually performed when accessing the route that lists the resources.
     *
     * @param  User $user  The authenticated User model instance attempting the action.
     * @return Response    The result: Allowed (Response::allow()) or Denied (Response::deny()) with a localized message.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view-any:feedback')) {
            return Response::allow();
        }

        return Response::deny(message: 'U hebt geen machtiging om het feedback overzicht te bekijken.');
    }

    /**
     * Allows the user to view the details of a single feedback message.
     *
     * This requires the 'view:feedback' Permission.
     * This allows an administrator to open and read a specific feedback entry in detail (the show page).
     * Since no specific logic about the feedback object itself is implemented here, the permission grants access to view *any* feedback item.
     *
     * @param  User     $user      The authenticated User model instance attempting the action.
     * @param  Feedback $feedback  The specific Feedback model instance being accessed.
     * @return Response            The result: Allowed or Denied.
     */
    public function view(User $user, Feedback $feedback): Response
    {
        if ($user->can('view:feedback')) {
            return Response::allow();
        }

        return Response::deny(message: 'U hebt geen machtiging om dit feedback bericht te bekijken.');
    }

    /**
     * Allows the user to delete a single, specific feedback message.
     *
     * This requires the 'delete:feedback' Permission.
     * This is the privilege required to remove one individual feedback entry permanently from the system (the destroy action).
     * Note that the `$feedback` model instance is required by the standard policy signature, but the logic here checks a generic permission, meaning the user can delete *any* feedback.
     *
     * @param  User $user   The authenticated User model instance attempting the action.
     * @return Response     The result: Allowed or Denied.
     */
    public function delete(User $user): Response
    {
        if ($user->can('delete:feedback')) {
            return Response::allow();
        }

        return Response::deny(message: 'U hebt geen machtiging om dit feedback bericht te verwijderen.');
    }

    public function markAsClosed(User $user, Feedback $feedback): Response 
    {
        return ($user->can('change-status:feedback') && $feedback->status->is(enum: FeedbackStatus::Unprocessed))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen machtiging om een feedback ticket te sluiten');
    }

    public function markAsOpen(User $user, Feedback $feedback): Response 
    {
        return ($user->can('change-status:feedback') && $feedback->status->is(enum: FeedbackStatus::Processed)) 
            ? Response::allow()
            : Response::deny(message: 'U hebt geen machtiging om een feedback ticket te heropenen');
    }

    /**
     * Allows the user to delete multiple feedback messages at once** (Bulk Deletion).
     *
     * This requires the **'delete-any:feedback'** Permission.
     * This is a separate, higher-level permission typically used for mass-cleanup operations,
     * distinguishing it from the ability to delete a single item (`delete:feedback`).
     *
     * @param  User $user  The authenticated User model instance attempting the action.
     * @return Response    The result: Allowed or Denied.
     */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete-any:feedback')) {
            return Response::allow();
        }

        return Response::deny(message: 'U hebt geen machtiging om feedback te verwijderen.');
    }
}
