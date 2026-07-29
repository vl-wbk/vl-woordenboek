<?php

declare(strict_types=1);

namespace App\Models;

use App\States\ExampleSentence\SentenceState;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\HasStates;

/**
 * Class UserExample
 *
 * This model manages the lifecycle and attribution of user-contributed example sentences.
 *
 * The "status" of this model is not a simple string; it is a state machine  managed by
 * the `spatie/laravel-model-states` package. This allows for complex transitions (e.g., from 'Pending' to 'Published')
 * with built-in validation logic.
 *
 * @property int     $id                The unique identifier from the record in the database.
 * @property string  $status            Managed state object (see casts).
 * @property int     $article_id        Foreign key for the parent article.
 * @property ?int    $user_id           The unique ID of the registered author (if applicable).
 * @property ?string $contributor_name  Manual name entry for guest contributions.
 * @property string  $example           The actual content of the example sentence.
 * @property ?Carbon $created_at        The timestamp from when the record has been updated.
 * @property ?Carbon $updated_at        The timestamp from when the record has last been updated.
 */
#[Guarded(columns: ['id'])]
final class UserExample extends Model
{
    use HasStates;
    use SoftDeletes;

    /**
     * @return MorphTo<Model, covariant $this>
     */
    public function exampleable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * This method identifies the user who submitted the example.
     *
     * To prevent "Property of non-object" errors in Blade templates, we use a fallback (withDefault).
     * If a User record is missing or the contribution is anonymous, it attempts to use the 'contributor_name'
     * database column, eventually falling back to the system's default application name.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withDefault(function (User $user, UserExample $example) {
                $user->name = $example->contributor_name ?? config()->string('app.name', 'Laravel');
            });
    }

    /**
     * Model attribute casting
     *
     * Defines how attributes are mutated when retrieved from or saved to the database.
     * The 'status' attribute is cast specifically to the SentenceState class to enable state-pattern functionality.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SentenceState::class,
        ];
    }
}
