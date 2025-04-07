<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ArticleReport;
use App\Models\User;
use App\States\Reporting\Status;
use App\UserTypes;

/**
 * The ArticleReportPolicy class defines the authorization rules for managing article reports.
 *
 * This policy ensures that only authorized users can perform specific actions on `ArticleReport` instances, such as viewing, updating, or deleting them.
 * It integrates with Laravel's authorization system to enforce these rules consistently across the application.
 *
 * The `before` method provides global authorization logic, allowing certain rules to override specific policy methods.
 * For example, users with the "Editor" role are explicitly denied access to all actions on article reports.
 * If no global rule applies, the specific policy methods handle the authorization logic for each action.
 *
 * This policy plays a critical role in maintaining accountability and ensuring that only authorized users
 * can manage article reports, which may include sensitive or important information about dictionary content.
 *
 * @package App\Policies
 */
final readonly class ArticleReportPolicy
{
    /**
     * Provides global authorization logic for article reports.
     *
     * This method denies access to all actions for users with the "Editor" role.
     * If no global rule applies, it defers to the specific policy methods for further authorization checks.
     *
     * @param  User $user  The user attempting to perform an action.
     * @return bool|null   Returns `false` to deny access, or `null` to defer to specific methods.
     */
    public function before(User $user): bool|null
    {
        if ($user->user_type->is(enum: UserTypes::Editor)) {
            return false;
        }

        return null;
    }

    /**
     * Determines whether the user can view the list of article reports.
     *
     * This method allows all authenticated users to access the list of reports.
     * It is a permissive rule that grants access to the `viewAny` action for all users.
     *
     * @param  User $user  The user attempting to view the list of article reports.
     * @return bool        Always returns `true`, allowing access.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determines whether the user can mark an article report as "In Progress."
     *
     * This action is allowed only if the article report does not already have an assignee and is in the "Open" state.
     * These conditions ensure that only unassigned and open reports can be marked as "In Progress."
     *
     * @param  User $user                    The user attempting to mark the report as "In Progress."
     * @param  ArticleReport $articleReport  The article report being updated.
     * @return bool                          Returns `true` if the report can be marked as "In Progress," otherwise `false`.
     */
    public function markInProgress(User $user, ArticleReport $articleReport): bool
    {
        return $articleReport->assignee()->doesntExist()
            && $articleReport->state->is(enum: Status::Open);
    }

    /**
     * Determines whether the user can mark an article report as "Closed."
     *
     * This action is permitted only if the article report has an assignee and the assignee is the currently authenticated user.
     * This ensures that only the assigned user can close the report, maintaining accountability.
     *
     * @param  User $user                    The user attempting to mark the report as "Closed."
     * @param  ArticleReport $articleReport  The article report being updated.
     * @return bool                          Returns `true` if the report can be marked as "Closed," otherwise `false`.
     */
    public function markAsClosed(User $user, ArticleReport $articleReport): bool
    {
        return $articleReport->assignee()->exists()
            && $articleReport->assignee()->is($user)
            && $articleReport->state->is(enum: Status::InProgress);
    }

    /**
     * Determines whether the user can delete an article report.
     *
     * This method allows all authenticated users to delete article reports.
     * It is a permissive rule that grants access to the `delete` action for all users.
     *
     * @param  User $user                    The user attempting to delete the article report.
     * @param  ArticleReport $articleReport  The article report being deleted.
     * @return bool                          Always returns `true`, allowing access.
     */
    public function delete(User $user, ArticleReport $articleReport): bool
    {
        return true;
    }
}
