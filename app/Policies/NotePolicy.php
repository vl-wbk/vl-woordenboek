<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Note, User};
use Illuminate\Auth\Access\Response;

/**
 * Implements authorization policies for managing notes within the Vlaams Woordenboek app.
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
 * while enabling the necessary administrative oversight.
 *
 * @link file://tests/Unit/Authorization/NotePolicyTest.php
 * @package App\Policies
 */
final readonly class NotePolicy
{
    /**
     * Controls authorization for note modification operations. This method enforces content ownership by verifying that the user attempting to update a note is its original author.
     * This restriction ensures that users can only modify their own content, maintaining data integrity and user trust in the system.
     *
     * @param  User $user   The user attempting to update the note
     * @param  Note $note   The note being updated
     * @return Response     True if the user is the note's author
     */
    public function update(User $user, Note $note): Response
    {
        return $note->authoredBy($user)
            ? Response::allow()
            : Response::deny(message: "U hebt geen toestemming om de notitie te wijzigen.");
    }

    /**
     * Manages authorization for note deletion operations.
     * This method mirrors the update authorization logic by restricting deletion rights to the note's original author.
     * This consistent approach to content ownership ensures that users maintain full control over their contributions while preventing unauthorized content removal.
     *
     * @param  User $user  The user attempting to delete the note
     * @param  Note $note  The note being deleted
     * @return Response    True if the user is the note's author
     */
    public function delete(User $user, Note $note): Response
    {
        return $note->authoredBy($user)
            ? Response::allow()
            : Response::deny(message: "U hebt geen toestemming om de notitie te verwijderen.");
    }
}
