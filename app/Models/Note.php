<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents individual notes attached to dictionary articles within the Vlaams Woordenboek application.
 *
 * This model provides the foundational structure for storing and managing user-created textual annotations.
 * It implements Laravel's Eloquent ORM patterns for seamless data layer integration. Notes are associated with both dictionary articles and their authors through Eloquent relationships.
 *
 * Data integrity is maintained through mass assignment protection, where the 'id' primary key is guarded while allowing efficient mass assignment of other attributes.
 * The model captures essential metadata including creation and modification timestamps.
 *
 * @property int $id Unique identifier from the note.
 * @property int $author_id Reference to the note's author unique identifier in the users table
 * @property string $title Heading of the note
 * @property string $body Main context text of the note
 * @property \Carbon\Carbon|null $created_at The timestamp of note creation
 * @property \Carbon\Carbon|null $updated_at The timestamp of last modification
 *
 * @method author()  Relationship method to access the note's author
 */
final class Note extends Model
{
    /**
     * Specifies attributes that are protected from mass assignment.
     * This property ensures that the note's unique identifier remains immutable throughout its lifecycle,  maintaining referential integrity while allowing other attributes to be mass assigned for efficient creation and updates.
     * The minimal protection approach reflects a balance between security and development convenience.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Establishes the relationship between a note and its author.
     * This method defines a belongs-to relationship with the User model, enabling seamless access to author information through Eloquent's elegant relationship syntax.
     * The implementation automatically handles foreign key conventions and eager loading capabilities, facilitating efficient data access patterns when retrieving author details for display or processing.
     *
     * @return BelongsTo<User, covariant $this> The Eloquent relationship instance linking to the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
