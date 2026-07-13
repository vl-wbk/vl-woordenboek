<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Concept;

/**
 * Handle model lifecycle events for the Concept model.
 *
 * This observer manages cascade operations and structural modifications for Concept records.
 * It automates cleanup of dependent relational entities whenever a concept is modified or dropped from the ecosystem.
 *
 * Be aware that the deletion routine uses 'forceDelete()' on the related 'userExamples'.
 * Even if the parent Concept or child entities utilizes Soft Deletes, deleting a Concept
 * will permanently purge its associated examples from the database.
 *
 * @package App\Observers
 */
final readonly class ConceptObserver
{
    /**
     * Handle the Concept "deleting" event.
     *
     * Intercepts the deletion lifecycle phase to perform a cascading purge of all linked 'userExamples' records.
     * This explicitly bypasses soft deletion machanics on the relation and permanently drops the child records
     * to maintain storage and relation integrity.
     *
     * @param  Concept $concept The specific concept instance currently.
     * @return void
     */
    public function deleting(Concept $concept): void
    {
        $concept->userExamples()->forceDelete();
    }
}
