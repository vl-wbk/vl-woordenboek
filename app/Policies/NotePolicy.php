<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use App\UserTypes;

/**
 * Implements authorization policies for managing notes within the Vlaams Woordenboek application.
 * This policy class defines the access control rules for note-related operations, ensuring that only authorized users can interact with notes in appropriate ways.
 *
 * The policy implements a hierarchical authorization system where administrators and developers
 * receive full access privileges automatically, while regular users must pass specific permission
 * checks based on note ownership. This structure maintains security while providing flexibility
 * for administrative needs.
 *
 * Through method-specific authorization rules, the policy ensures data integrity by restricting
 * note modifications and deletions to the original authors, except for administrative users who
 * have unrestricted access. This ownership-based access control system protects user content
 * while enabling necessary administrative oversight.
 *
 * @package App\Policies
 */
final readonly class NotePolicy
{
    /**
     * Performs pre-authorization checks for all note-related operations.
     * This method serves as a gateway for administrative access, automatically granting permission to users with administrator or developer roles.
     * For all other users, the method allows the regular authorization flow to proceed by returning null.
     *
     * @param  User   $user     The user attempting the operation
     * @param  string $ability  The authorization action being attempted
     * @return bool|null        True for administrators, null for other users
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])) {
            return true;
        }

        return null;
    }

    /**
     * Controls authorization for note modification operations. This method enforces content ownership by verifying that the user attempting to update a note is its original author.
     * This restriction ensures that users can only modify their own content, maintaining data integrity and user trust in the system.
     *
     * @param  User $user   The user attempting to update the note
     * @param  Note $note   The note being updated
     * @return bool         True if the user is the note's author
     */
    public function update(User $user, Note $note): bool
    {
        return $note->author()->is($user);
    }

    /**
     * Manages authorization for note deletion operations.
     * This method mirrors the update authorization logic by restricting deletion rights to the note's original author.
     * This consistent approach to content ownership ensures that users maintain full control over their contributions while preventing unauthorized content removal.
     *
     * @param  User $user  The user attempting to delete the note
     * @param  Note $note  The note being deleted
     * @return bool        True if the user is the note's author
     */
    public function delete(User $user, Note $note): bool
    {
        return $note->author()->is($user);
    }
}
