<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ArticleStates;
use App\Models\User;
use App\Models\Article;
use App\States\Articles\ArticleState;
use App\UserTypes;

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
        $isPublishedOrAwaitinApproval = ($article->isPublished() || $article->state->is(ArticleStates::Approval)) ;

        if ($isPublishedOrAwaitinApproval && $user->user_type->in([UserTypes::EditorInChief, UserTypes::Administrators, UserTypes::Developer])) {
            return true;
        }

        return $article->state->in(enums: [ArticleStates::New, ArticleStates::ExternalData, ArticleStates::Draft, ArticleStates::Archived])
            && $user->user_type->notIn(enums: [UserTypes::Normal]);
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
            && $user->user_type->notIn(enums: [UserTypes::Normal]);
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

        if ($user->user_type->notIn(enums: [UserTypes::EditorInChief, UserTypes::Developer, UserTypes::Administrators])) {
            return false;
        }

        return $article->editor()->exists() && $article->editor()->isNot($user);
    }

    /** @todo document */
    public function unpublish(User $user, Article $article): bool
    {
        return $article->isPublished() && $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators]);
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

        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    /** @todo document */
    public function attachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->doesntExist() && $user->user_type->notIn([UserTypes::Normal, UserTypes::Editor]);
    }

    /** @todo document */
    public function detachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->exists() && $user->user_type->notIn([UserTypes::Normal, UserTypes::Editor]);
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
            && $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer, UserTypes::EditorInChief]);
    }

    /** @todo document */
    public function unarchive(User $user, Article $article): bool
    {
        return $article->state->is(ArticleStates::Archived) && $user->user_type->notIn([UserTypes::Normal, UserTypes::Editor]);
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
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])
            && $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft, ArticleStates::ExternalData, ArticleStates::Archived]);
    }

    /** @todo document */
    public function restore(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    /** @todo document */
    public function restoreAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
