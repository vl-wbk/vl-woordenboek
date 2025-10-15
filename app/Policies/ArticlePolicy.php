<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ArticleStates;
use App\Models\User;
use App\Models\Article;
use Filament\Support\Authorization\DenyResponse;
use Illuminate\Auth\Access\Response;

/**
 * ArticlePolicy enforces authorization rules for dictionary article management.
 *
 * This policy class defines access control for all article-related operations, implementing a state-based permission system that considers both users's role and article's current state.
 * The policy ensures proper workflow progression while maintaining content quality and editorial oversight.
 *
 * @package App\Policies
 */
final class ArticlePolicy
{
    /**
     * Defines all action prefixes used for policy permission checks.
     * These combine with the resource name (e.g., ':article') to create full permission strings (e.g., 'update:article').
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'update', 'sendForApproval', 'publish', 'unpublish', 'detachEditor', 'attachDisclaimer', 'detachDisclaimer',
        'archive', 'unarchive', 'delete', 'deleteAny', 'restore', 'restoreAny', 'export', 'updatePublished'
    ];

    /**
     * Determines whether a user can update an article's content.
     *
     * Updates are permitted for articles in New, Draft, or Archived states, but restricted from normal users to maintain editorial quality.
     * This ensures that only qualified editors can modify dictionary content.
     *
     * @param  User     $user     The user attempting the update
     * @param  Article  $article  The article that is being updated
     * @return Response           True if the user has permission to update, false otherwise
     */
    public function update(User $user, Article $article): Response
    {
        $allowedStates = [ArticleStates::New, ArticleStates::ExternalData, ArticleStates::Draft, ArticleStates::Archived];

        if ($article->isPublished() && $user->can('update-published:article')) {
            return Response::allow();
        }

        if ($article->isPublished() || $article->state->is(ArticleStates::Approval)) {
            return DenyResponse::deny('Niet toegestaan');
        }

        if ($article->state->in(enums: $allowedStates) && $user->can('update:article')) {
            return Response::allow();
        }

        return DenyResponse::deny('Niet toegestaan');
    }

    /**
     * Determines whether a user can submit an article for publication review.
     *
     * Submission is allowed for New or Draft articles but restricted from normal users to ensure a proper editorial workflow.
     * This gate controls entry into the formal review process.
     *
     * @param  User     $user     The user attempting to submit the article
     * @param  Article  $article  The article that is being submitted
     * @return Response           True if the user has permission to submit, false otherwise
     */
    public function sendForApproval(User $user, Article $article): Response
    {
        if ($article->state->in(enums: [ArticleStates::Draft]) && $user->can('send-for-approval:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can publish an article.
     *
     * Publication is only allowed when:
     * - The article is in either Approval or Archived state
     * - The article has an assigned editor
     * - The publishing user isn't the assigned editor (four-eyes principle)
     *
     * This policy ensures proper oversight of content publication by requiring review from someone other than the original editor.
     * This helps maintain quality standards and prevents self-publication of content.
     *
     * @param  User     $user     The user attempting to publish the article
     * @param  Article  $article  The article to be published
     * @return Response           True if publication is allowed, false otherwise
     */
    public function publish(User $user, Article $article): Response
    {
        if ($article->state->isNot(enum: ArticleStates::Approval)) {
            return Response::deny();
        }

        if ($user->cannot('publish:article')) {
            return Response::deny();
        }

        if ($article->editor()->exists() && $article->editor()->isNot($user)) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can unpublish an article.
     *
     * Unpublishing is restricted to users with Administrator or Developer roles and only applies to articles that are currently in the Published state.
     * This ensures that only authorized personnel can remove content from public view.
     *
     * @param  User    $user     The user attempting to unpublish the article.
     * @param  Article $article  The article to be unpublished.
     * @return Response          True if the user has permission to unpublish, false otherwise.
     */
    public function unpublish(User $user, Article $article): Response
    {
        if ($article->isPublished() && $user->can('unpublish:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether the provided user has permission to detach the editor from the article.
     *
     * This method ensures that an editor can only be detached when the article is in a Draft state.
     * The reasoning is that changes to the editor assignment should only be allowed before the article is finalized.
     *
     * The method returns true if either:
     *
     * - The user attempting the detaching is the same as the article's currently assigned editor, allowing a user to remove themselves.
     * - The user belongs to a higher-privileged role (Administrators or Developers), which enables them to manage editor assignments for any article.
     *
     * If the article is not in Draft state, the detach action is disallowed.
     *
     * @param  User    $user     The user attempting to detach the editor.
     * @param  Article $article  The article from which the editor is to be detached.
     * @return Response          True if the user is authorized to perform the detaching otherwise, false.
     */
    public function detachEditor(User $user, Article $article): Response
    {
        if ($article->state->isNot(enum: ArticleStates::Draft)) {
            return Response::deny();
        }

        if ($article->editor()->is($user)) {
            return Response::allow();
        }

        if ($user->can('detach-disclaimer:article')) {
            return Response::allow();
        }


        return Response::deny();
    }

    /**
     * Determines whether a user can attach a disclaimer to an article.
     *
     * Attaching a disclaimer is permitted only if the article doesn't already have one, and the user isn't a 'Normal' user or an 'Editor'.
     * This ensures that only users with higher privileges can manage disclaimers.
     *
     * @param  User    $user     The user attempting to attach the disclaimer.
     * @param  Article $article  The article to which the disclaimer is to be attached.
     * @return Response          True if the user has permission to attach the disclaimer, false otherwise.
     */
    public function attachDisclaimer(User $user, Article $article): Response
    {
        if ($article->disclaimer()->doesntExist() && $user->can('attach-disclaimer:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can detach a disclaimer from an article.
     *
     * Detaching a disclaimer is permitted only if the article currently has one, and the user is not a 'Normal' user or an 'Editor'.
     * This ensures that only users with higher privileges can manage disclaimers.
     *
     * @param  User    $user     The user attempting to detach the disclaimer.
     * @param  Article $article  The article from which the disclaimer is to be detached.
     * @return Response          True if the user has permission to detach the disclaimer, false otherwise.
     */
    public function detachDisclaimer(User $user, Article $article): Response
    {
        if ($article->disclaimer()->exists() && $user->can('detach-disclaimer:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can archive an article.
     *
     * Archival permissions are granted to administrators and chief editors for Published or Approval-state articles.
     * This allows senior editors to manage content visibility while preserving article history.
     *
     * @param  User     $user     The user that's attempting to archive the article.
     * @param  Article  $article  The article that is being archived
     * @return Response           True if the user has permission to archive, false otherwise
     */
    public function archiveArticle(User $user, Article $article): Response
    {
        if ($article->state->in(enums: [ArticleStates::Published, ArticleStates::Approval]) && $user->can('archive:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can unarchive an article.
     *
     * Unarchiving is allowed only if the article is currently in the Archived state, and the user is not a 'Normal' user or an 'Editor'.
     * This ensures that only authorized personnel can restore archived content.
     *
     * @param  User    $user     The user attempting to unarchive the article.
     * @param  Article $article  The article to be unarchived.
     * @return Response          True if the user has permission to unarchive, false otherwise.
     */
    public function unarchive(User $user, Article $article): Response
    {
        if ($article->state->is(ArticleStates::Archived) && $user->can('unarchive:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can permanently delete an article.
     *
     * Deletion is highly restricted, limited to administrators and chief editors, and only possible for articles in New or Draft states.
     * This prevents accidental removal of published content while allowing cleanup of incomplete entries.
     *
     * @param  User     $user     The user attempting to delete the article
     * @param  Article  $article  The article being deleted by the user.
     * @return Response           True if the user has permission to delete, false otherwise
     */
    public function delete(User $user, Article $article): Response
    {
        $allowedStates = [ArticleStates::New, ArticleStates::Draft, ArticleStates::ExternalData, ArticleStates::Archived];

        if ($user->can('delete:article') && $article->state->in(enums: $allowedStates)) {
            return Response::allow();
        }

        return DenyResponse::deny('Niet toegestaan');
    }

    /**
     * Determines whether a user can restore a soft-deleted article.
     *
     * Restoration is restricted to users with 'Administrators' or 'Developer' roles.
     * This policy applies to restoring a single, specific soft-deleted article.
     *
     * @param  User $user  The user attempting to restore the article.
     * @return Response    True if the user has permission to restore, false otherwise.
     */
    public function restore(User $user): Response
    {
        if ($user->can('restore:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can restore any soft-deleted article.
     *
     * This permission is granted exclusively to users with 'Administrators' or 'Developer' roles,
     * allowing them to restore any soft-deleted article, not just a specific one.
     *
     * @param  User $user  The user attempting to restore articles.
     * @return Response    True if the user has permission to restore any article, false otherwise.
     */
    public function restoreAny(User $user): Response
    {
        if ($user->can('restore-any:article')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether a user can permanently delete multiple articles simultaneously.
     *
     * This method grants permission based solely on the presence of the 'delete-any:article' permission.
     * This permission is typically reserved for administrative roles and allows the user to perform
     * bulk deletion operations on articles, regardless of their current state.
     *
     * @param  User $user  The user attempting to delete articles.
     * @return Response    True if the user has permission to delete any article, false otherwise.
     */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete-any:article')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
