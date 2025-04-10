<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\ArticleBuilder;
use App\States\Articles;
use App\Contracts\States\ArticleStateContract;
use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToEditor;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelLike\Traits\Likeable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Kenepa\ResourceLock\Models\Concerns\HasLocks;

/**
 * Article represents a dictionary entry in the Vlaams Woordenboek application.
 *
 * This model manages dictionary articles through their entire lifecycle, from creation to publication.
 * It implements a state pattern to handle different article statuses (new, draft, published, etc.)
 * and includes auditing capabilities to track changes. The model supports relationships with authors,
 * editors, regions, and definitions while also providing likeability features.
 *
 * @property int            $id                 The unique identifier for the article
 * @property string         $word               The dictionary word being defined
 * @property ArticleStates  $state              The current state of the article in its lifecycle
 * @property string|null    $keywords           The keywords that are attached to the article
 * @property string         $description        The detailed explanation of the word
 * @property int            $author_id          The ID of the user who created the article
 * @property LanguageStatus $status             The current language validation status
 * @property DataOrigin      $origin             The origin of the data where the dictionary article is based on.
 * @property string|null    $example            Optional usage example of the word
 * @property string|null    $characteristics    Additional word characteristics
 * @property int|null       $editor_id          The ID of the assigned editor
 * @property int|null       $part_of_speech_id  The unique ID of the part of speech information.
 * @property string |null   $archiving_reason   The reason why the article has been archived.
 * @property \Carbon\Carbon $archived_at        Timestamp for when the article is archived at
 * @property \Carbon\Carbon $created_at         Timestamp of when the article was created
 * @property \Carbon\Carbon $updated_at         Timestamp of the last update
 *
 * @package App\Models
 */
final class Article extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    use BelongsToManyRegions;
    use BelongsToEditor;
    use Auditable;
    use Likeable;
    use HasLocks;

    /**
     * Specifies attributes that are protected from mass assignment.
     * This property ensures that the note's unique identifier remains immutable throughout its lifecycle,  maintaining referential integrity while allowing other attributes to be mass assigned for efficient creation and updates.
     * The minimal protection approach reflects a balance between security and development convenience.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Attributes excluded from the audit trail.
     * Editor ID changes are not tracked to reduce noise in the audit logs.
     *
     * @var list<string>
     */
    protected $auditExclude = ['editor_id'];

    /**
     * Default values for new article instances.
     * Every new article starts in 'New' state with unknown language status.
     *
     * @var array<string, object|int|string>
     */
    protected $attributes = [
        'origin' => DataOrigin::Suggestion,
        'state' => ArticleStates::New,
        'status' => LanguageStatus::Onbekend,
    ];

    /**
     * Returns the appropriate Article State instance based on the current article status.
     *
     * This methed uses a `match` expression to determine the current state of the dictionary article based on its state.
     * It then returns an instance of the corresponding state class, which handles specigfic behaviours and transitions fo that state.
     * Each articlpe state maps to a different state class; ensuring the current state logic is applied at any given point in the articlpe lifecycle.
     *
     * Exmaple states flow: New -> Draft -> Approval -> Published -> Archived
     *
     * @return ArticleStateContract - The correcponding state class for the current dictionary article
     */
    public function articleStatus(): ArticleStateContract
    {
        return match($this->state) {
            ArticleStates::ExternalData => new Articles\ExternalDataState($this),
            ArticleStates::New => new Articles\NewState($this),
            ArticleStates::Draft => new Articles\DraftState($this),
            ArticleStates::Approval => new Articles\ApprovalState($this),
            ArticleStates::Published => new Articles\PublishedState($this),
            ArticleStates::Archived => new Articles\ArchivedState($this),
        };
    }

    /**
     * Retrieves the associated part of speech data for the article.
     *
     * This method defines a "belongs to" relationship that retrieves detailed grammatical information,
     * such as whether the word is a noun, verb, adjective, etc. This information is critical for categorizing
     * the article correctly in the application.
     *
     * @return BelongsTo<PartOfSpeech, covariant $this>
     */
    public function partOfSpeech(): BelongsTo
    {
        return $this->belongsTo(PartOfSpeech::class);
    }

    /**
     * Defines the relationship between an article and its author.
     * Each article is created by exactly one user (author). This relationship is crucial for tracking article ownership and attribution.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Defines the many-to-many relationship between articles and labels.
     *
     * This relationship allows articles to be categorized with multiple labels and tracks when
     * each label was attached to the article. The pivot table maintains timestamps for both
     * creation and updates, providing an audit trail of label assignments.
     *
     * The relationship enables:
     * - Categorizing articles with multiple labels
     * - Tracking when labels were assigned (created_at in pivot)
     * - Maintaining updated_at timestamps for label assignments
     *
     * @return BelongsToMany<Label, covariant $this> The relationship instance for article labels
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class)
            ->withPivot('created_at')
            ->withTimestamps();
    }

    /**
     * Establishes the one-to-many relationship between dictionary articles and their associated notes.
     * This relationship allows articles to maintain multiple textual annotations, providing additional context, clarifications, or editorial comments.
     * Each note is directly linked to its parent article through a foreign key constraint, ensuring referential integrity in the database.
     *
     * The relationship enables efficient access to an article's notes through Laravel's Eloquent ORM, supporting both eager and lazy loading patterns.
     * This implementation facilitates common operations like retrieving all notes for an article, adding new notes, and managing existing annotations within the dictionary entry context.
     *
     * @return HasMany<Note, covariant $this> The relationship instance managing the article's notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Defines the relationship between an article and the user who archived it.
     *
     * This "belongs to" relationship links the article to the user who performed the archiving action.
     * It is used to track accountability and provide historical context for archived articles.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function archiever(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieves all reports associated with this article.
     *
     * The `reports` method establishes a one-to-many relationship between an article and its related report records.
     * Every time a user or system flags an article—whether for feedback, issue reporting, or another purpose a corresponding record is created in the ArticleReport model.
     * This method makes it easy to access all such reports, which can then be used for analytics, audits, or user notifications.
     *
     * @return HasMany<ArticleReport, covariant $this>
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ArticleReport::class);
    }

    /**
     * Overrides the default Eloquent builder with a custom ArticleBuilder.
     *
     * This method ensures that all queries for the Article model use the custom builder,
     * which includes additional methods for managing article states (e.g., archiving and unarchiving).
     *
     * @param \Illuminate\Database\Query\Builder $query  The base query builder instance
     * @return ArticleBuilder<self>                      The custom builder instance
     */
    public function newEloquentBuilder($query): ArticleBuilder
    {
        return new ArticleBuilder($query);
    }

    /**
     * Configures attribute casting for proper type handling.
     * Ensures that state and status fields are properly cast to their respective enum types when retrieved from the database.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'origin' => DataOrigin::class,
            'state' => ArticleStates::class,
            'status' => LanguageStatus::class,
            'sources' => 'array',
        ];
    }
}
