<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents individual notes attached to dictionary articles within the Vlaams Woordenboek application.
 *
 * This model provides the foundational structure for storing and managing user-created textual annotations.
 * It implements Laravel's Eloquent ORM patterns for seamless data layer integration. Notes are associated with both dictionary articles and their authors through Eloquent relationships.
 *
 * Data integrity is maintained through mass assignment protection, where the 'id' primary key is guarded while allowing efficient mass assignment of other attributes.
 * The model captures essential metadata including creation and modification timestamps.
 *
 * @property int                 $id          Unique identifier from the note.
 * @property int                 $author_id   Reference to the note's author unique identifier in the user's table
 * @property string              $title       Heading of the note
 * @property string              $body        Main context text of the note
 * @property Carbon|null         $created_at  The timestamp of note creation
 * @property Carbon|null         $updated_at  The timestamp of the last modification
 *
 * @package App\Models
 */
final class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;
    use BelongsToAuthor;

    /**
     * Specifies attributes that are protected from mass assignment.
     * This property ensures that the note's unique identifier remains immutable throughout its lifecycle, maintaining referential integrity while allowing other attributes to be mass assigned for efficient creation and updates.
     * The minimal protection approach reflects a balance between security and development convenience.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];
}
