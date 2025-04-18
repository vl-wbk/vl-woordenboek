<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Concerns\HandlesRelationManipulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait BelongsToAuthor
 *
 * Provides a standardized way for Eloquent models to establish a "belongs to" relationship with a User model, representing the author or creator of the model instance.
 * This trait encapsulates common author-related logic, promoting code reusability and consistency across different models within the application.
 *
 * This trait is particularly useful for models that require tracking ownership or authorship, such as articles, comments, or any content created by users.
 *
 * Key Features:
 * - Simplifies the definition of the `author()` relationship.
 * - Offers a convenient method to assign the currently authenticated user as the author.
 * - Provides a method to explicitly set any User instance as the author.
 * - Ensures the author relationship is persisted immediately upon assignment.
 *
 * Integration:
 * To utilize this trait, simply include it in your Eloquent model: `use BelongsToAuthor;`
 *
 * @todo Refactor in order to support the HandlesRelationManipulation trait
 *
 * @package App\Models\Relations
 */
trait BelongsToAuthor
{
    /**
     * Defines the "belongs to" relationship with the User model, representing the author of this model.
     *
     * This method establishes the inverse one-to-many relationship, indicating that this model belongs to a single User instance, which is designated as the author.
     * This relationship allows for easy retrieval of the author's information associated with the model.
     *
     * @return BelongsTo<User, covariant $this> A BelongsTo relationship instance, linking this model to the User model as the author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Automatically sets the currently authenticated user as the author of this model.
     *
     * This method fetches the currently logged-in user's instance from the authentication context and assigns them as the author of this model using the `setAuthor` method.
     * This is commonly used during the creation or modification of a model when the user performing the action is considered the author.
     *
     * @return void
     */
    public function setCurrentUserAsAuthor(): void
    {
        $this->setAuthor(auth()->user());
    }

    /**
     * Associates a specific User model as the author of this model and immediately persists the relationship.
     *
     * This method accepts a User model instance and establishes the "belongs to" relationship between this model and the provided user.
     * It then immediately saves the current model to the database, ensuring that the author relationship is stored.
     * This method should be used when explicitly assigning a specific user as the author, rather than relying on the currently authenticated user.
     *
     * @param  User $user  The User model instance to be associated as the author.
     * @return void
     */
    public function setAuthor(User $user): void
    {
        $this->author()->associate($user)->save();
    }
}
