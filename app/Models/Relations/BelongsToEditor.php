<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Enables a model to associate users as an editor or a publisher.
 *
 * This trait provides methods to assign users as the editor or publisher of a model, facilitating easy tracking of who has edited or managed the content within the model.
 * Ensure the model's table has appropriate foreign keys (`editor_id`, `publisher_id`) that correspond to the user's ID.
 *
 * @package App\Models\Relations
 */
trait BelongsToEditor
{
    /**
     * Establishes a belongs-to association with the User model, designated as the publisher.
     * This relationship can help identify which user took responsibility for publishing the model, which often intersects with roles concerned with oversight and content delivery.
     *
     * @return BelongsTo<User, covariant $this> The relationship instance pointing to the User model.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Establishes a belongs-to association with the User model, designated as the publisher.
     * This relationship can help identify which user took responsibility for publishing the model, which often intersects with roles concerned with oversight and content delivery.
     *
     * @return BelongsTo<User, covariant $this> The relationship instance pointing to the User model.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the currently authenticated user as the publisher of the model.
     *
     * This method fetches the currently authenticated user via the `auth()` helper, associates this user with the model's publisher relationship, and persists the change to the database.
     * This simplifies managing and tracking the publishing process directly from the model instance.
     *
     * @return self Returns the current instance of the model to allow method chaining.
     */
    public function setCurrentUserAsPublisher(): self
    {
        $this->publisher()->associate(auth()->user())->save();

        return $this;
    }

    /**
     * Sets the currently authenticated user as the editor of the model.
     *
     * Similar to 'setCurrentUserAsEditor', this function assigns the authenticated user as the editor.
     * It estabilishes the user's association within the model's database and saves the record, which is crucial for tracking revisions or entries by particular users.
     *
     * @return self  Returns the current instance of the model to allow method chaining.
     */
    public function setCurrentUserAsEditor(): self
    {
        $this->editor()->associate(auth()->user())->save();

        return $this;
    }
}
