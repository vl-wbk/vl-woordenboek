<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Notes\Visibility;
use Database\Factories\NoteFactory;
use App\Models\Relations\BelongsToAuthor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Guarded;
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
 * @property-read User $author  The variable that holds all the information about the user who authored the note
 *
 * @package App\Models
 */
#[Guarded(columns: ['id'])]
final class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory;
    use BelongsToAuthor;

    protected $attributes = [
        'visibility' => Visibility::Public,
    ];

    protected function casts(): array
    {
        return [
            'visibility' => Visibility::class,
        ];
    }
}
