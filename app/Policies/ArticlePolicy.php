<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ArticleStates;
use App\Models\User;
use App\Models\Article;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

/**
 * ArticlePolicy enforces authorization rules for dictionary article management.
 *
 * This policy class defines access control for all article-related operations, implementing a state-based permission system that considers both users's role and article's current state.
 * The policy ensures proper workflow progression while maintaining content quality and editorial oversight.
 *
 * @package App\Policies
 */
final readonly class ArticlePolicy
{
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    public function viewInformation(?User $user, Article $article): Response
    {
        if ($article->isHidden() && $article->state->in([ArticleStates::New, ArticleStates::Published, ArticleStates::Archived]) && $article->author()->is($user)) {
            return Response::allow();
        }

        if ($article->isHidden()) {
            return Response::denyAsNotFound();
        }

        return Response::allow();
    }

    /**
     * Determines whether a user can update an article's content.
     *
     * Updates are permitted for articles in New, Draft, or Archived states, but restricted from normal users to maintain editorial quality.
     * This ensures that only qualified editors can modify dictionary content.
     *
     * @param  User     $user     The user attempting the update
     * @param  Article  $article  The article that is being updated
     * @return bool               True if the user has permission to update, false otherwise
     */
    public function update(User $user, Article $article): bool
    {
        $isPublishedOrAwaitinApproval = ($article->isPublished() || $article->state->is(ArticleStates::Approval));

        if ($isPublishedOrAwaitinApproval && $user->can('update_article')) {
            return false;
        }

        return $article->state->in(enums: [ArticleStates::New, ArticleStates::ExternalData, ArticleStates::Draft, ArticleStates::Archived])
            && $user->can('update_article');
    }

    /**
     * Determines whether a user can submit an article for publication review.
     *
     * Submisseion is allowed for New or Draft articles, but retricted form normal users to ensure proper editorial workflow.
     * This gate controls entry into the formal review process.
     *
     * @param  User     $user     The user attempting to submit the article
     * @param  Article  $article  The article that is being submitted
     * @return bool               True if the user has permission to submit, false otherwise
     */
    public function sendForApproval(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Draft])
            && $user->can('send_for_approval_article');
    }

    /**
     * Determines whether a user can publish an article.
     *
     * Publication is only allowed when:
     * - The article is in either Approval or Archived state
     * - The article has an assigned editor
     * - The publishing user is not the assigned editor (four-eyes principle)
     *
     * This policy ensures proper oversight of content publication by requiring review from someone other than the original editor.
     * This helps maintain quality standards and prevents self-publication of content.
     *
     * @param  User     $user     The user attempting to publish the article
     * @param  Article  $article  The article to be published
     * @return bool               True if publication is allowed, false otherwise
     */
    public function publish(User $user, Article $article): bool
    {
        if ($article->state->isNot(enum: ArticleStates::Approval)) {
            return false;
        }

        if ($user->cannot('publish_article')) {
            return false;
        }

        return $article->editor()->exists() && $article->editor()->isNot($user);
    }

    /**
     * Determines whether a user can unpublish an article.
     *
     * Unpublishing is restricted to users with Administrator or Developer roles, and only applies to articles that are currently in the Published state.
     * This ensures that only authorized personnel can remove content from public view.
     *
     * @param  User    $user     The user attempting to unpublish the article.
     * @param  Article $article  The article to be unpublished.
     * @return bool              True if the user has permission to unpublish, false otherwise.
     */
    public function unpublish(User $user, Article $article): bool
    {
        return $article->isPublished() && $user->can('unpublish_article');
    }

    /**
     * Determines whether the provided user has permission to detach the editor from the article.
     *
     * This method ensures that an editor can only be detached when the article is in a Draft state.
     * The reasoning is that changes to the editor assignment should only be allowed before the article is finalized.
     *
     * The method returns true if either:
     *
     * - The user attempting the detach is the same as the article's currently assigned editor, allowing a user to remove themselves.
     * - The user belongs to a higher-privileged role (Administrators or Developers), which enables them to manage editor assignments for any article.
     *
     * If the article is not in Draft state, the detach action is disallowed.
     *
     * @param  User    $user     The user attempting to detach the editor.
     * @param  Article $article  The article from which the editor is to be detached.
     * @return bool              True if the user is authorized to perform the detach; otherwise, false.
     */
    public function detachEditor(User $user, Article $article): bool
    {
        if ($article->state->isNot(enum: ArticleStates::Draft)) {
            return false;
        }

        if ($article->editor()->is($user)) {
            return true;
        }

        return $user->can('detach_disclaimer_article');
    }

    /**
     * Determines whether a user can attach a disclaimer to an article.
     *
     * Attaching a disclaimer is permitted only if the article does not already have one, and the user is not a 'Normal' user or an 'Editor'.
     * This ensures that only users with higher privileges can manage disclaimers.
     *
     * @param  User    $user     The user attempting to attach the disclaimer.
     * @param  Article $article  The article to which the disclaimer is to be attached.
     * @return bool              True if the user has permission to attach the disclaimer, false otherwise.
     */
    public function attachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->doesntExist() && $user->can('attach_disclaimer_article');
    }

    /**
     * Determines whether a user can detach a disclaimer from an article.
     *
     * Detaching a disclaimer is permitted only if the article currently has one, and the user is not a 'Normal' user or an 'Editor'.
     * This ensures that only users with higher privileges can manage disclaimers.
     *
     * @param  User    $user     The user attempting to detach the disclaimer.
     * @param  Article $article  The article from which the disclaimer is to be detached.
     * @return bool              True if the user has permission to detach the disclaimer, false otherwise.
     */
    public function detachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->exists() && $user->can('detach_disclaimer_article');
    }

    /**
     * Determines whether a user can archive an article.
     *
     * Archival permissions are granted to administrators and chief editors for Published or Approval-state articles.
     * This allows senior editors to manage content visibility while preserving article history.
     *
     * @param  User     $user     The user that iàs attempting to archive the article.
     * @param  Article  $article  The article that is being archived
     * @return bool               True if the user has permission to archive, false otherwise
     */
    public function archiveArticle(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Published, ArticleStates::Approval])
            && $user->can('archive_article');
    }

    /**
     * Determines whether a user can unarchive an article.
     *
     * Unarchiving is allowed only if the article is currently in the Archived state, and the user is not a 'Normal' user or an 'Editor'.
     * This ensures that only authorized personnel can restore archived content.
     *
     * @param  User    $user     The user attempting to unarchive the article.
     * @param  Article $article  The article to be unarchived.
     * @return bool              True if the user has permission to unarchive, false otherwise.
     */
    public function unarchive(User $user, Article $article): bool
    {
        return $article->state->is(ArticleStates::Archived) && $user->can('unarchive_article');
    }

    /**
     * Determines whether a user can permanently delete an article.
     *
     * Deletion is highly restricted, limited to administrators and chief editors, and only possible for articles in New or Draft states.
     * This prevents accidental removal of published content while allowing cleanup of incomplete entries.
     *
     * @param  User     $user     The user attempting to delete the article
     * @param  Article  $article  The article being deleted by the user.
     * @return bool               True if the user has permission to delete, false otherwise
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->can('delete_article')
            && $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft, ArticleStates::ExternalData, ArticleStates::Archived]);
    }

    /**
     * Determines whether a user can restore a soft-deleted article.
     *
     * Restoration is restricted to users with 'Administrators' or 'Developer' roles.
     * This policy applies to restoring a single, specific soft-deleted article.
     *
     * @param  User $user  The user attempting to restore the article.
     * @return bool        True if the user has permission to restore, false otherwise.
     */
    public function restore(User $user): bool
    {
        return $user->can('restore_article');
    }

    /**
     * Determines whether a user can restore any soft-deleted article.
     *
     * This permission is granted exclusively to users with 'Administrators' or 'Developer' roles,
     * allowing them to restore any soft-deleted article, not just a specific one.
     *
     * @param  User $user  The user attempting to restore articles.
     * @return bool        True if the user has permission to restore any article, false otherwise.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_article');
    }

    /**
     * @todo document policy
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_article');
    }
}
