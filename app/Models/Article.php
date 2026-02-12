<?php

/** @noinspection PhpMissingFieldTypeInspection */

declare(strict_types=1);

namespace App\Models;

use App\Services\ViewCounterService;
use App\States\Articles\ExternalData;
use App\States\Articles\Suggestion;
use App\States\Articles\Draft;
use App\States\Articles\Approval;
use App\States\Articles\Published;
use App\States\Articles\Archived;
use App\Builders\ArticleBuilder;
use App\Models\Relations\HasNotables;
use App\Contracts\States\ArticleStateContract;
use App\Enums\ArticleStates;
use App\Enums\DataOrigin;
use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToAuthor;
use App\Models\Relations\BelongsToEditor;
use App\Models\Relations\BelongsToManyRegions;
use App\States\RejectedPublication;
use Carbon\Carbon;
use Database\Factories\ArticleFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Stringable;
use Kirschbaum\Commentions\Contracts\Commentable;
use Kirschbaum\Commentions\HasComments;
use Overtrue\LaravelLike\Traits\Likeable;
use Overtrue\LaravelVote\Traits\Votable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Override;

/**
 * Article represents a dictionary entry in the Vlaams Woordenboek application.
 *
 * This model manages dictionary articles through their entire lifecycle, from creation to publication.
 * It implements a state pattern to handle different article statuses (new, draft, published, etc.)
 * and includes auditing capabilities to track changes. The model supports relationships with authors,
 * editors, regions, and definitions while also providing likability features.
 *
 * @property int $id                 The unique identifier for the article
 * @property string $word               The dictionary word being defined
 * @property ArticleStates $state              The current state of the article in its lifecycle
 * @property string|null $keywords           The keywords that are attached to the article
 * @property string $description        The detailed explanation of the word
 * @property int $author_id          The ID of the user who created the article
 * @property bool $notify_author        The boolean flag to check if the author of the article wants a publication notification.
 * @property LanguageStatus $status             The current language validation status
 * @property DataOrigin $origin             The origin of the data where the dictionary article is based on.
 * @property string|null $example            Optional usage example of the word
 * @property string|null $characteristics    Additional word characteristics
 * @property int|null $editor_id          The ID of the assigned editor
 * @property int|null $part_of_speech_id  The unique ID of the part of speech information.
 * @property string |null $archiving_reason   The reason why the article has been archived.
 * @property ?Carbon $published_at        The timestamp indicating when the article is published. null = unpublished.
 * @property Carbon $archived_at        Timestamp for when the article is archived at
 * @property Carbon $deleted_at         Timestamp for when the article is marked for deletion.
 * @property Carbon $created_at         Timestamp of when the article was created
 * @property Carbon $updated_at         Timestamp of the last update
 *
 * @property-read User $author
 * @property-read Disclaimer $disclaimer
 *
 * @package App\Models
 * @method isPublished()
 */
final class Article extends Model implements AuditableContract, Commentable
{
    /**
     * @use HasFactory<ArticleFactory>
     */
    use HasFactory;

    use BelongsToManyRegions;
    use BelongsToEditor;
    use BelongsToAuthor;
    use Auditable;
    use Likeable;
    use SoftDeletes;
    use HasNotables;
    use HasComments;
    use Votable;

    /**
     * Specifies attributes that are protected from mass assignment.
     * This property ensures that the note's unique identifier remains immutable throughout its lifecycle, maintaining referential integrity while allowing other attributes to be mass assigned for efficient creation and updates.
     * The minimal protection approach reflects a balance between security and development convenience.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /** @todo Document this */
    protected $with = ['author'];

    /**
     * Attributes excluded from the audit trail.
     * Editor ID changes are not tracked to reduce noise in the audit logs.
     *
     * @var list<string>
     */
    protected $auditExclude = ['views', 'votes_today'];

    /**
     * Default values for new article instances.
     * Every new article starts in the 'New' state with unknown language status.
     *
     * @var array<string, object|int|string>
     */
    protected $attributes = [
        'origin' => DataOrigin::Suggestion,
        'state' => ArticleStates::New ,
        'status' => LanguageStatus::Onbekend,
    ];

    /**
     * Returns the appropriate Article State instance based on the current article status.
     *
     * This method uses a `match` expression to determine the current state of the dictionary article based on its state.
     * It then returns an instance of the corresponding state class, which handles specific behaviors and transitions of that state.
     * Each article state maps to a different state class; ensuring the current state logic is applied at any given point in the article lifecycle.
     *
     * Example states flow: New -> Draft -> Approval -> Published -> Archived
     *
     * @return ArticleStateContract - The corresponding state class for the current dictionary article
     */
    public function articleStatus(): ArticleStateContract
    {
        return match ($this->state) {
            ArticleStates::ExternalData => new ExternalData($this),
            ArticleStates::New => new Suggestion($this),
            ArticleStates::Draft => new Draft($this),
            ArticleStates::Approval => new Approval($this),
            ArticleStates::Published => new Published($this),
            ArticleStates::Archived => new Archived($this),
            ArticleStates::RejectedPublication => new RejectedPublication($this)
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
     * @return BelongsTo<User, covariant $this>
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Defines the relationship between an article and its disclaimer.
     *
     * This method establishes a "belongs to" relationship, indicating that each article can be associated with one disclaimer.
     * This is useful for providing legal or informational disclaimers related to the article's content.
     *
     * @return BelongsTo<Disclaimer, covariant $this>
     */
    public function disclaimer(): BelongsTo
    {
        return $this->belongsTo(Disclaimer::class);
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
     * Every time a user or system flags an article—whether for feedback, issue reporting, or another purpose, a corresponding record is created in the ArticleReport model.
     * This method makes it easy to access all such reports, which can then be used for analytics, audits, or user notifications.
     *
     * @return HasMany<ArticleReport, covariant $this>
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ArticleReport::class);
    }

    /**
     * Defines a one-to-many relationship with the `Etymology` model.
     *
     * This method indicates that the current model (e.g., an `Article`) can have multiple associated `Etymology` records.
     * When called, it returns a `HasMany` relationship builder instance, allowing you to query or eager load all etymology entries linked to this specific model instance.
     *
     * @return HasMany<Etymology, covariant $this>
     */
    public function etymologies(): HasMany
    {
        return $this->hasMany(Etymology::class, 'article_id');
    }

    /**
     * @todo document this function
     * @return HasMany<Reaction, covariant $this>
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * Retrieves all users who have bookmarked this article.
     *
     * The `bookmarkers` method sets up a many-to-many relationship between articles and users through the `article_bookmarks` pivot table.
     * This allows you to quickly find all users who have saved this article to their bookmarks, which can be useful for engagement analytics, notifications, or personalized content features.
     *
     * @return BelongsToMany<User, covariant $this> A collection of users who have bookmarked this article.
     */
    public function bookmarkers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, table: 'article_bookmarks');
    }

    /**
     * @todo Document this method
     * @return HasMany<ArticleReferenceWork, covariant $this>
     */
    public function sources(): HasMany
    {
        return $this->hasMany(ArticleReferenceWork::class);
    }

    /**
     * Defines the many-to-many relationship with related articles.
     *
     * This relationship links the current article instance to other articles via a pivot table.
     * It is typically a self-referencing many-to-many where articles can be related in both directions.
     *
     * NOTE:
     *
     * The default foreign keys are usually 'article_id' and 'related_article_id' based on the models and pivot table name.
     * The current code explicitly sets the pivot table and the foreign pivot key.
     * The local key (which should be the current model's ID in the pivot table) is omitted and thus relies on Laravel's default naming convention (e.g., 'article_id').
     *
     *  Due to the parameter sequence used:
     *  - 'related_articles' is the pivot table name.
     *  - 'related_article_id' is the foreign pivot key (the key of the current model).
     *  - The related pivot key (the key of the Article::class model) is inferred.
     *
     * @return BelongsToMany<Article, covariant $this>
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'related_articles', 'related_article_id');
    }

    /**
     * Overrides the default Eloquent builder with a custom ArticleBuilder.
     *
     * This method ensures that all queries for the Article model use the custom builder,
     * which includes additional methods for managing article states (e.g., archiving and unarchiving).
     *
     * @param \Illuminate\Database\Query\Builder $query The base query builder instance
     * @return ArticleBuilder<self>                      The custom builder instance
     */
    #[Override]
    public function newEloquentBuilder($query): ArticleBuilder
    {
        return new ArticleBuilder($query);
    }

    /**
     * @param  EloquentBuilder<covariant $this> $builder
     * @param  string $date
     * @return void
     */
    #[Scope]
    protected function publishedAfter(EloquentBuilder $builder, string $date): void
    {
        $builder->where('published_at', '>', now()->parse($date));
    }

    public function recordView()
    {
        app(ViewCounterService::class)->incrementAndSync($this);
    }

    /**
     * @param  EloquentBuilder<covariant $this> $builder
     * @param  string $date
     * @return void
     */
    #[Scope]
    protected function createdAfter(EloquentBuilder $builder, string $date): void
    {
        $builder->where('created_at', '>', now()->parse($date));
    }

    /**
     * @return HasMany<WordOfTheDay, covariant $this>
     */
    public function wordOfTheDays(): HasMany
    {
        return $this->hasMany(WordOfTheDay::class);
    }

    public function isCurrentWordOfTheDay(): bool
    {
        return $this->wordOfTheDays()
            ->whereDate('scheduled_for', today())
            ->exists();
    }

    /**
     * @return Attribute<string, never-return>
     */
    protected function seoDescription(): Attribute
    {
        return Attribute::get(function (): string {
            return (string) str($this->description)
                ->markdown()     // Maak er HTML van (lost Markdown syntax op)
                ->stripTags()    // Strip alle resulterende HTML tags
                ->squish()
                ->limit(300);
        });
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
            'notify_author' => 'boolean',
            'wtod' => 'boolean',
            'feedback' => 'array',
            'origin' => DataOrigin::class,
            'state' => ArticleStates::class,
            'status' => LanguageStatus::class,
            'published_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }
}
