<?php

declare(strict_types=1);

namespace App\States\Posts;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

/**
 * The PublicationState class implements the PublicationStateContract and defines the logic for transitioning a blog post between different publication states, specifically 'Published' and 'Draft'.
 * This class encapsulates the state-specific behavior for a `Blog` model, ensuring that state changes are handled consistently and atomically within database transactions.
 *
 * This class is part of a state pattern implementation, where different states (e.g., PublicationState) define how a `Blog` object behaves when certain actions (e.g., publishing, drafting) are requested.
 * It ensures that updates to the blog's status are performed safely within a database transaction.
 *
 * @see Blog                        - The Blog model whose publication state is managed by this class.
 * @see Status                      - The enum defining the possible publication statuses for a blog.
 * @see PublicationStateContract    - The interface that this class implements, defining the contract for publication state transitions.
 *
 * @package App\States\Posts
 */
class PublicationState implements PublicationStateContract
{
    /**
     * Create a new PublicationState instance.
     *
     * The constructor initializes the state object with the specific `Blog` model instance whose publication state will be managed.
     * This ensures that all state transition methods operate on the correct blog post.
     * The `$blog` property is declared as `public readonly` to make it accessible but immutable after instantiation.
     *
     * @param \App\Models\Blog $blog The Blog model instance whose publication state is being managed.
     */
    public function __construct(
        public readonly Blog $blog,
    ) {}

    /**
     * Transitions the associated blog post to the 'Published' status.
     *
     * This method updates the `status` attribute of the `Blog` model to `Status::Published`.
     * The entire update operation is wrapped within a database transaction using `DB::transaction`.
     * This ensures that the status change is atomic; either the update is fully committed to the database, or it is entirely rolled back if any error occurs, thereby maintaining data consistency and integrity.
     *
     * @return bool     Returns `true` if the update operation was successful within the transaction, `false` otherwise (e.g., if the transaction failed).
     */
    public function transitionToPublished(): bool
    {
        return DB::transaction(callback: fn(): bool => $this->blog->update(attributes: [
            'status' => Status::Published,
        ]));
    }

    /**
     * Transitions the associated blog post to the 'Draft' status.
     *
     * This method updates the `status` attribute of the `Blog` model to `Status::Draft`. Similar to the `transitionToPublished` method, this operation is also enclosed within a database transaction using `DB::transaction`.
     * This guarantees that the status change is performed atomically, ensuring that the database remains in a consistent state even if an error occurs during the update process.
     *
     * @return bool  Returns `true` if the update operation was successful within the transaction,`false` otherwise (e.g., if the transaction failed).
     */
    public function transitionToDraft(): bool
    {
        return DB::transaction(callback: fn(): bool => $this->blog->update(attributes: [
            'status' => Status::Draft,
        ]));
    }
}
