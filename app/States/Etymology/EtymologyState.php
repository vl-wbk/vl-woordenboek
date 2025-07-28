<?php

declare(strict_types=1);

namespace App\States\Etymology;

use App\Data\Etymology\StatusData;
use App\Enums\Articles\EtymologyStatus;
use App\Models\Etymology;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * The EtymologyState class serves as an abstract base class (or concrete base for specific implementations) for managing the state transitions of an `Etymology` model.
 * It implements the `EtymologyStateContract`, defining a common interface for various state-changing operations such as transitioning to draft, under review, rejected, published, or archived.
 *
 * This class encapsulates the core logic for updating an etymology's status and related metadata within a database transaction, ensuring atomicity and data integrity.
 * Concrete state classes (like `Published` or `Draft`) would typically extend this class and override or implement specific transition behaviors, potentially preventing certain transitions based on the current state.
 *
 * @see Etymology               - The Eloquent model whose state is managed by this class.
 * @see EtymologyStatus         - The enum defining the possible statuses for an etymology.
 * @see StatusData              - The Data Transfer Object used for preparing status update attributes.
 * @see EtymologyStateContract  - The interface that this class implements, defining the contract for etymology state transitions.
 *
 * @package App\States\Etymology
 */
readonly class EtymologyState implements EtymologyStateContract
{
    /**
     * Create a new EtymologyState instance.
     *
     * The constructor initializes the state object with the specific `Etymology` model instance whose status and related attributes will be managed.
     * This ensures that all state transition methods operate on the correct etymology entry.
     * The `$etymology` property is declared as `public readonly` to make it accessible but immutable after instantiation, promoting data consistency.
     *
     * @param Etymology $etymology  The Etymology model instance whose state is being managed.
     */
    public function __construct(
        public Etymology $etymology,
    ) {}

    /**
     * Transitions the associated etymology entry to the 'Draft' status.
     *
     * This method updates the `status` attribute of the `Etymology` model to `EtymologyStatus::Draft`.
     * The entire update operation is wrapped within a database transaction using `DB::transaction`.
     * This ensures that the status change is atomic; either the update is fully committed to the database, or it is entirely rolled back if any error occurs, thereby maintaining data consistency and integrity.
     * The attributes for the update are prepared using `StatusData::from()->toArray()`.
     *
     * @return bool|int  Returns `true` if the update operation was successful (for Eloquent's `update` method, or `0` if no update occurred. In a transaction, it returns the result of the callback.
     */
    public function transitionToDraft(): bool|int
    {
        return DB::transaction(
            callback: fn(): bool|int => $this->etymology->update(
                attributes: StatusData::from(['status' => EtymologyStatus::Draft])->toArray(),
            ),
        );
    }

    /**
     * Transitions the associated etymology entry to the 'Under Review' status.
     *
     * This method updates the `status` attribute of the `Etymology` model to `EtymologyStatus::UnderReview`.
     * The entire update operation is wrapped within a database transaction using `DB::transaction`.
     * This guarantees that the status change is performed atomically, ensuring that the database remains in a consistent state even if an error occurs during the update process.
     * The attributes for the update are prepared using `StatusData::from()->toArray()`.
     *
     * @return bool|int  Returns `true` if the update operation was successful, or `0` if no update occurred.
     */
    public function transitionToUnderReview(): bool|int
    {
        return DB::transaction(
            callback: fn(): bool|int => $this->etymology->update(
                attributes: StatusData::from(['status' => EtymologyStatus::UnderReview])->toArray(),
            ),
        );
    }

    /**
     * Transitions the associated etymology entry to the 'Rejected' status.
     *
     * This method updates the `status` attribute of the `Etymology` model to `EtymologyStatus::Rejected`.
     * In addition to the status, it also records the `rejected_by` user (using the authenticated user's ID), the `rejected_at` timestamp (current time), and an optional `rejection_reason`.
     * This entire operation is performed within a database transaction to ensure atomicity.
     * The attributes are prepared using `StatusData::from()->toArray()`.
     *
     * @param  string|null $reason  An optional string providing the reason for rejection.
     * @return bool|int             Returns `true` if the update operation was successful, or `0` if no update occurred.
     */
    public function transitionToRejected(?string $reason = null): bool|int
    {
        return DB::transaction(
            callback: fn(): bool|int => $this->etymology->update(
                attributes: StatusData::from([
                    'status' => EtymologyStatus::Rejected,
                    'rejected_by' => Auth::user()->getAuthIdentifier(),
                    'rejected_at' => now(),
                    'rejection_reason' => $reason,
                ])->toArray(),
            ),
        );
    }

    /**
     * Transitions the associated etymology entry to the 'Published' status.
     *
     * This method updates the `status` attribute of the `Etymology` model to `EtymologyStatus::Published`.
     * It also records the `published_at` timestamp (current time) and the `published_by` user (using the authenticated user's ID).
     * The entire operation is executed within a database transaction to guarantee atomicity and data consistency. The attributes are prepared using `StatusData::from()->toArray()`.
     *
     * @return bool|int Returns `true` if the update operation was successful, or `0` if no update occurred.
     */
    public function transitionToPublished(): bool|int
    {
        return DB::transaction(
            callback: fn(): bool|int => $this->etymology->update(
                attributes: StatusData::from([
                    'status' => EtymologyStatus::Published,
                    'published_at' => now(),
                    'published_by' => Auth::user()->getAuthIdentifier(),
                ])->toArray(),
            ),
        );
    }

    /**
     * Transitions the associated etymology entry to the 'Archived' status.
     *
     * This method updates the `status` attribute of the `Etymology` model to `EtymologyStatus::Archived`.
     * Additionally, it records the `archived_by` user (using the authenticated user's ID), the `archived_at` timestamp (current time), and an optional `archiving_reason`.
     * This entire operation is performed within a database transaction to ensure atomicity.
     * The attributes are prepared using `StatusData::from()->toArray()`.
     *
     * @param  string|null $reason  An optional string providing the reason for archiving.
     * @return bool|int             Returns `true` if the update operation was successful, or `0` if no update occurred.
     */
    public function transitionToArchived(?string $reason = null): bool|int
    {
        return DB::transaction(
            callback: fn(): bool | int => $this->etymology->update(
                attributes: StatusData::from([
                    'status' => EtymologyStatus::Archived,
                    'archived_by' => Auth::user()->getAuthIdentifier(),
                    'archived_at' => now(),
                    'archiving_reason' => $reason,
                ])->toArray(),
            ),
        );
    }
}
