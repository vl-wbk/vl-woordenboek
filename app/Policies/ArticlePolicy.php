<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Enums\ArticleStates;
use App\Models\{Article, User};
use App\UserTypes;
use App\States\Articles\ArticleState;
use Filament\Support\Authorization\DenyResponse;

/**
 * ArticlePolicy enforces authorization rules for dictionary article management.
 *
 * This policy class defines access control for all article-related operations, implementing a state-based permission system that considers both user's role and article's current state.
 * The policy ensures proper workflow progression while maintaining content quality and editorial oversight.
 *
 * @package App\Policies
 */
final class ArticlePolicy
{
    const string DisplayArticle = "display";

    /**
     * Defines all action prefixes used for policy permission checks.
     * These combine with the resource name (e.g., ':article') to create full permission strings (e.g., 'update:article').
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        "update",
        "sendForApproval",
        "publish",
        "unpublish",
        "detachEditor",
        "attachDisclaimer",
        "detachDisclaimer",
        "archive",
        "unarchive",
        "delete",
        "verwijderVanuitPublicatie",
        "deleteAny",
        "restore",
        "restoreAny",
        "export",
        "updatePublished",
        "geforceerdVerwijderen",
        "meerdereGeforceerdVerwijderen",
    ];

    /**
     * Determines whether the user can view in the frontend of the application.
     * This policy method differs from the 'view' method because of the usage location of the policy.
     *
     * While the view policy is only used in the filament management method while the display method
     * is used in the frontend for the guests and is modified to allow preview articles from the backend.
     *
     * @param  User|null $user      The entity of the authenticated user
     * @param  Article   $article   The entity of the article that user tries to view.
     * @return Response
     */
    public function display(?User $user, Article $article): Response
    {
        $allowedStates = $article->state->in([ArticleStates::Draft, ArticleStates::Approval]);

        if (
            $article->isPublished() ||
            $article->isArchived() ||
            ($user?->canAny(["update-published:article", "update:article"]) && $allowedStates)
        ) {
            return Response::allow();
        }

        //! No custom authorization message defined because we simply need to return a HTTP 404 code.
        return Response::denyAsNotFound();
    }

    public function viewSuggestion(User $user, Article $article): Response
    {
        if ($article->author->is($user)) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

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
        if ($article->trashed()) {
            return Response::deny("Kan geen verwijderd artikel bewerken");
        }

        if ($article->isPublished()) {
            return $user->can("update-published:article")
                ? Response::allow()
                : Response::deny("Geen toestemming voor gepubliceerde artikelen.");
        }

        if ($article->isEditable() && $user->can("update:article")) {
            return Response::allow();
        }

        return Response::deny("Bewerken is momenteel niet toegestaan.");
    }

    /**
     * Determines whether a user can submit an article for publication review.
     *
     * Submission is allowed for New or Draft articles but restricted from normal users to ensure a proper editorial workflow.
     * This gate controls entry into the formal review process.
     *
     * @param User      $user     The user attempting to submit the article
     * @param Article   $article  The article that is being submitted
     * @return Response           True if the user has permission to submit, false otherwise
     */
    public function sendForApproval(User $user, Article $article): Response
    {
        if ($article->state->isNot(ArticleStates::Draft)) {
            return Response::deny(message: "Alleen klad artikelen kunnen ingezonden worden voor nazicht en publicatie");
        }

        return $user->can("send-for-approval:article") || $article->editor()->is($user)
            ? Response::allow()
            : Response::deny(message: "Je hebt geen permissie om dit artikel in te zenden voor nazicht en publicatie");
    }

    /**
     * Determines whether the user can create a copy of an existing article.
     *
     * Duplication is permitted for articles in 'Kladversie', 'Publicatie', or 'Archief' states.
     *
     * This allows editors to:
     * 1. Create safety backups of complex drafts.
     * 2. Prepare new revisions of currently published entries without affecting the live site.
     * 3. Repurpose archived data as a starting point for new lemmas.
     *
     * @param  User    $user     The user attempting to duplicate the article.
     * @param  Article $article  The source article to be copied.
     * @return Response
     */
    public function duplicate(User $user, Article $article): Response
    {
        $cloneableStates = [ArticleStates::Published, ArticleStates::Draft, ArticleStates::Archived];

        if ($article->trashed()) {
            return Response::deny(message: __('Je kan geen verwijderd artikel dupliceren.'));
        }

        if ($article->state->notIn(enums: $cloneableStates)) {
            return Response::deny(message: __('Artikelen in de status :state kunnen niet worden gedepliceerd.', [
                'state' => $article->state->getLabel()
            ]));
        }


        return $user->can('create:article')
            ? Response::allow()
            : Response::deny('Je hebt geen rechten om nieuwe artikelen aan te maken.');
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

        if ($user->cannot("publish:article")) {
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
        if ($article->isPublished() && $user->can("unpublish:article")) {
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
            return Response::deny(
                message: "Het is niet mogelijk oim de redacteur los te koppelen van het artikelen buiten de klad versie status.",
            );
        }

        if ($article->editor()->is($user)) {
            return Response::allow();
        }

        if ($user->can("detach-editor:article")) {
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
        if ($user->can("attach-disclaimer:article") && $article->disclaimer()->doesntExist()) {
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
        if ($user->can("detach-disclaimer:article") && $article->disclaimer()->exists()) {
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
        if ($article->trashed()) {
            return Response::deny(message: "U kunt geen verwijderde artikelen archiveren in het systeem.");
        }

        if ($article->state->in(enums: [ArticleStates::Published, ArticleStates::Approval]) && $user->can("archive:article")) {
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
        if ($article->state->is(ArticleStates::Archived) && $user->can("unarchive:article")) {
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
     * @param  User     $user     The user attempting to delete the article.
     * @param  Article  $article  The article being deleted by the user.
     * @return Response           True if the user has permission to delete, false otherwise.
     */
    public function delete(User $user, Article $article): Response
    {
        if ($article->state->is(ArticleStates::Published)) {
            return $user->can("verwijder-vanuit-publicatie:article")
                ? Response::allow()
                : DenyResponse::deny("Je hebt geen permissie om gepubliceerde artikelen te verwijderen.");
        }

        if (!$user->can("delete:article")) {
            return DenyResponse::deny("Je hebt geen permissie om artikelen te verwijderen.");
        }

        $deletableStates = match (true) {
            $user->user_type->is(UserTypes::Editor) => [ArticleStates::ExternalData, ArticleStates::New],
            $user->user_type->in([UserTypes::Administrators, UserTypes::Developer]) => [
                ArticleStates::ExternalData,
                ArticleStates::New,
                ArticleStates::Archived,
            ],
            default => [],
        };

        return $article->state->in($deletableStates)
            ? Response::allow()
            : DenyResponse::deny("Het artikel kan in deze staat niet verwijderd worden.");
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
        if ($user->can("restore:article")) {
            return Response::allow();
        }

        return Response::deny(message: "Je hebt geen permissie om verwijderde artikelen te herstellen.");
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
        if ($user->can("restore-any:article")) {
            return Response::allow();
        }

        return Response::deny(message: "Je hebt geen permissie om verwijderde artikelen te herstellen.");
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
        if ($user->can("delete-any:article")) {
            return Response::allow();
        }

        return Response::deny(message: "Je hebt geen permissie om meerdere artikelen te verwijderen.");
    }

    /**
     * Determines whether a user can permanently (force) delete a specific article.
     *
     * Force deletion bypasses soft-delete and permanently removes the article from the database.
     * This action is irreversible and therefore restricted to users with explicit force-delete permissions.
     *
     * @param  User     $user  The user attempting to permanently delete the article.
     * @return Response        Allow if the user has the 'geforceerd-verwijderen:article' permission, deny otherwise.
     */
    public function forceDelete(User $user): Response
    {
        return $user->can("geforceerd-verwijderen:article")
            ? Response::allow()
            : Response::deny(message: "Je hebt geen permissies om merdere artikelen te verwijderen.");
    }

    /**
     * Determines whether a user can permanently (force) delete multiple articles simultaneously.
     *
     * This method controls bulk force-deletion, which permanently removes all targeted articles from the database without the possibility of restoration.
     * Due to the destructive and irreversible nature of this operation, it is exclusively reserved for privileged roles.
     *
     * @param  User     $user  The user attempting to permanently delete multiple articles.
     * @return Response        Allow if the user has the 'meerdere-geforceerd-verwijderen:article' permission, deny otherwise.
     */
    public function forceDeleteAny(User $user): Response
    {
        return $user->can("meerdere-geforceerd-verwijderen:article")
            ? Response::allow()
            : Response::deny(message: "Je hebt geen permissie om artikelen permanent te verwijderen.");
    }
}
