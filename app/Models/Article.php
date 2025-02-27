<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\ExampleArrtibute;
use App\States\Articles;
use App\Contracts\States\ArticleStateContract;
use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToEditor;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Deprecated;
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
 * @property int            $id               The unique identifier for the article
 * @property string         $word             The dictionary word being defined
 * @property ArticleStates  $state            The current state of the article in its lifecycle
 * @property string         $description      The detailed explanation of the word
 * @property int            $author_id        The ID of the user who created the article
 * @property LanguageStatus $status           The current language validation status
 * @property string|null    $example          Optional usage example of the word
 * @property string|null    $characteristics  Additional word characteristics
 * @property int|null       $editor_id        The ID of the assigned editor
 * @property \Carbon\Carbon $created_at       Timestamp of when the article was created
 * @property \Carbon\Carbon $updated_at       Timestamp of the last update
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
     * The attributes that can be mass assigned when creating or updating an article.
     * Security measure to prevent unintended attribute modifications.
     *
     * @var list<string>
     */
    protected $fillable = ['word', 'state', 'description', 'author_id', 'status', 'example', 'characteristics'];

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
            ArticleStates::New => new Articles\NewState($this),
            ArticleStates::Draft => new Articles\DraftState($this),
            ArticleStates::Approval => new Articles\ApprovalState($this),
            ArticleStates::Published => new Articles\PublishedState($this),
            ArticleStates::Archived => new Articles\ArchivedState($this),
        };
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
     * Configures attribute casting for proper type handling.
     * Ensures that state and status fields are properly cast to their respective enum types when retrieved from the database.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'state' => ArticleStates::class,
            'status' => LanguageStatus::class,
            'example' => ExampleArrtibute::class,
        ];
    }
}
