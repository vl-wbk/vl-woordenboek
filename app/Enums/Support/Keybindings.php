<?php

declare(strict_types=1);

namespace App\Enums\Support;

/**
 * Keybindings enum
 *
 * This enumeration serves a the single source of truth for keyboard shortcuts across the application.
 * It maps physical key combinations (Mousetrap.js compatible) to their respective authorization logic
 * and frontend DOM requirements.
 */
enum KeyBindings: string
{
    case EditArticle = 'meta+option+e';             // Trigger the edit mode for the current article.
    case UndoPublication = 'meta+option+u';         // Revert a published article back to a draft state.
    case ArchivePublication = 'meta+option+a';      // Move an article to the archive storage.
    case AcceptPublication = 'meta+option+p';       // Approve and publish a pending article.
    case RejectPublication = 'meta+option+r';       // Decline a pending publication request.
    case DeletePublication = 'meta+option+d';       // Retrieve the corresponding policy method name.

    /**
     * Retrieve the corresponding Policy method name.
     * This method maps the keyboard shortcut to the specific authorization check defined within the ArticlePolicy.
     *
     * @return string The name of the method to be checked via Gate or @can.
     */
    public function policyMethod(): string
    {
        return match ($this) {
            self::EditArticle => 'update',
            self::UndoPublication => 'unpublish',
            self::ArchivePublication => 'archiveArticle',
            self::DeletePublication => 'delete',
            self::AcceptPublication, self::RejectPublication => 'publish',

        };
    }

    /**
     * Retrieve the unique HTML ID for the target DOM element.
     *
     * This ID is used by the JavaScript Mousetrap binding to locate and
     * programmatically click the relevant UI component (button or link).
     *
     * @return string The exact 'id' attribute expected in the Blade template.
     */
    public function domId(): string
    {
        return match ($this) {
            self::EditArticle => 'editArticle',
            self::UndoPublication => 'undoPublication',
            self::ArchivePublication => 'archivePublication',
            self::AcceptPublication => 'acceptPublication',
            self::RejectPublication => 'rejectPublication',
            self::DeletePublication => 'deleteArticle',
        };
    }
}
