<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Etymology;
use Illuminate\Support\Facades\Auth;

/**
 * The EtymologyObserver class acts as an Eloquent observer for the Etymology model.
 * Its primary responsibility is to automatically populate specific timestamp and user ID fields on an Etymology record before it is first created in the database, based on its initial status.
 * This ensures that records are properly attributed and timestamped from their inception, reflecting the user who initiated the creation and the immediate state of the etymology.
 *
 * Observers provide a clean way to group listeners for a model, keeping related logic organized instead of scattering it across multiple event listeners or within the model itself.
 *
 * @package App\Observers
 */
final readonly class EtymologyObserver
{
    /**
     * Handle the `creating` event for the Etymology model.
     *
     * This method is invoked automatically by Laravel's Eloquent ORM just before a new Etymology record is saved to the database for the very first time.
     * Its purpose is to pre-fill certain attributes based on the etymology's initial status.
     *
     * The method first retrieves the ID of the currently authenticated user.
     * It then uses a `match` expression to conditionally fill attributes:
     *
     * - If the etymology's status is `Archived`, it sets `archived_at` to the current timestamp, and `author_id` and `archived_by` to the authenticated user's ID.
     * - If the etymology's status is `Rejected`, it sets `rejected_at` to the current timestamp, and `author_id` and `rejected_by` to the authenticated user's ID.
     * - If the etymology's status is `Published`, it sets `published_at` to the current timestamp, and `author_id` and `published_by` to the authenticated user's ID.
     * - For any other status (the `default` case), no specific fields are pre-filled, allowing other mechanisms or default database values to apply.
     *
     * This ensures that new etymology records have consistent timestamp and user attribution based on their initial lifecycle state.
     *
     * @param  Etymology $etymology  The Etymology model instance being created.
     * @return void                  This method does not return a value; it modifies the `$etymology` object by reference.
     */
    public function creating(Etymology $etymology): void
    {
        match (true) {
            $etymology->status->isArchived() => $etymology->fill(['archived_at' => now(), 'archived_by' => $userId]),
            $etymology->status->isRejected() => $etymology->fill(['rejected_at' => now(), 'rejected_by' => $userId]),
            $etymology->status->isPublished() => $etymology->fill(['published_at' => now(), 'published_by' => $userId]),
            default => null,
        };

        $etymology->author()->associate($user ?? null)->save();
    }
}
