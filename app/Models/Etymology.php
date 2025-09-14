<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Articles\EtymologySources;
use App\Enums\Articles\EtymologyStatus;
use App\Models\Relations\BelongsToAuthor;
use App\Observers\EtymologyObserver;
use App\States\Etymology\EtymologyStateContract;
use App\States\Etymology as EtymologyState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The Etymology model represents an etymological entry associated with an article within the application.
 * It stores detailed information about the origin, form, source, and historical period of a word's etymology, along with its current status in a workflow (e.g., Draft, Under Review, Published).
 *
 * This model leverages Laravel's Eloquent features for database interaction, including factory support for testing and seeding, and an observer for automatic attribute population during creation.
 * It also implements a state pattern to manage its lifecycle, allowing for structured transitions between different statuses.
 *
 * @property int                                $id                 The unique identifier for the etymology entry.
 * @property EtymologyStatus                    $status             The current status of the etymology entry (e.g., Draft, UnderReview, Published, Rejected, Archived).
 * @property int|null                           $article_id         The foreign key linking this etymology to its parent article.
 * @property string                             $origin_language    The original language from which the word is derived.
 * @property string                             $origin_form        The original form of the word in its origin language.
 * @property ?string                            $source             The primary source of the etymological information.
 * @property string                             $source_url         The URL to the primary source of the etymological information.
 * @property string|null                        $note               Any additional notes or comments regarding the etymology.
 * @property string                             $etymology          The detailed etymological explanation.
 * @property \Illuminate\Support\Carbon|null    $period_start       The start date of the historical period relevant to the etymology.
 * @property \Illuminate\Support\Carbon|null    $period_end         The end date of the historical period relevant to the etymology.
 * @property \Illuminate\Support\Carbon|null    $created_at         The timestamp when the etymology entry was created.
 * @property \Illuminate\Support\Carbon|null    $updated_at         The timestamp when the etymology entry was last updated.
 * @property \Illuminate\Support\Carbon|null    $published_at       The timestamp when the etymology entry was published.
 * @property \Illuminate\Support\Carbon|null    $rejected_at        The timestamp when the etymology entry was rejected.
 * @property \Illuminate\Support\Carbon|null    $archived_at        The timestamp when the etymology entry was archived.
 * @property int|null                           $published_by       The ID of the user who published the etymology.
 * @property int|null                           $rejected_by        The ID of the user who rejected the etymology.
 * @property int|null                           $archived_by        The ID of the user who archived the etymology.
 * @property string|null                        $rejection_reason   The reason provided if the etymology was rejected.
 * @property string|null                        $archiving_reason   The reason provided if the etymology was archived.
 * @property User                               $author             The author (user) who created this etymology entry.
 *
 * @see EtymologyObserver       - For automatic population of timestamps and user IDs during creation.
 * @see EtymologyStateContract  - For the state pattern implementation.
 * @see BelongsToAuthor         - For the author relationship trait.
 *
 * @package App\Models
 */
#[ObservedBy(EtymologyObserver::class)]
final class Etymology extends Model
{
    /** @use HasFactory<\Database\Factories\EtymologyFactory> */
    use HasFactory;
    use BelongsToAuthor;

    /**
     * The attributes that are not mass assignable.
     *
     * This property defines the columns that cannot be filled using mass assignment, protecting sensitive fields from unintended updates.
     * The 'id' column is typically guarded as it's an auto-incrementing primary key.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The model's default attribute values.
     *
     * This property sets the default values for certain attributes when a new Etymology model instance is created.
     * By default, the `status` of a new etymology entry is set to EtymologyStatus::UnderReview, indicating that it requires review before further processing.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => EtymologyStatus::UnderReview,
    ];

    /**
     * Returns the current state object for the etymology.
     *
     * This method implements the state pattern, dynamically returning a concrete state object based on the current status of the etymology model.
     * Each state object (UnderReview, Draft, Rejected, Published, Archived) encapsulates the specific behaviors and allowed transitions from that state.
     * This design promotes clean separation of concerns and makes it easier to manage complex state-dependent logic.
     *
     * @return EtymologyStateContract   An instance of the concrete state class corresponding to the etymology's current status.
     */
    public function state(): EtymologyStateContract
    {
        return match ($this->status) {
            EtymologyStatus::UnderReview => new EtymologyState\UnderReview($this),
            EtymologyStatus::Draft => new EtymologyState\Draft($this),
            EtymologyStatus::Rejected => new EtymologyState\Rejected($this),
            EtymologyStatus::Published => new EtymologyState\Published($this),
            EtymologyStatus::Archived => new EtymologyState\Archived($this),
        };
    }

    /**
     * Defines a BelongsTo relationship with the User model for the archiver.
     *
     * This method establishes a relationship where an etymology entry belongs to a user who archived it.
     * The foreign key used for this relationship is archived_by.
     * If the associated archiver user does not exist (e.g., if the user was deleted),
     * it defaults to a placeholder user with the name 'Onbekende of verwijderde gebruiker' (Unknown or deleted user) to prevent errors in the view.
     *
     * @return BelongsTo<User, covariant $this>  The Eloquent BelongsTo relationship instance.
     */
    public function archiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by')
            ->withDefault(['name' => 'Onbekende of verwijderde gebruiker']);
    }

    /** @phpstan-ignore-next-line */
    #[Scope] protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }

    /**
     * Defines a BelongsTo relationship with the User model for the rejecter.
     *
     * This method establishes a relationship where an etymology entry belongs to a user who rejected it.
     * The foreign key used for this relationship is rejected_by.
     * If the associated rejecter user does not exist, it defaults to a placeholder user with the name 'Onbekende of verwijderde gebruiker' (Unknown or deleted user).
     *
     * @return BelongsTo<User, covariant $this> The Eloquent BelongsTo relationship instance.
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by')
            ->withDefault(['name' => 'Onbekende of verwijderde gebruiker']);
    }

    /**
     * Defines a BelongsTo relationship with the Article model.
     *
     * This method establishes a relationship where an etymology entry belongs to a single Article.
     * This is a standard one-to-many (inverse) relationship, indicating that an etymology is a component of a larger article.
     *
     * @return BelongsTo<Article, covariant $this>  The Eloquent BelongsTo relationship instance.
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * This method defines the data types for specific model attributes, ensuring they are automatically converted to and from PHP types when interacting with the database.
     *
     * - period_end and period_start are cast to date objects, allowing them to be manipulated as Carbon instances.
     * - status is cast to the EtymologyStatus enum, providing type-safe access to the etymology's status.
     * - type is cast to the EtymologyTypes enum, providing type-safe access to the etymology's type.
     *
     * @return array<string, string> An associative array defining the casting configuration.
     */
    protected function casts(): array
    {
        return [
            'status' => EtymologyStatus::class,
            'source_name' => EtymologySources::class,
        ];
    }
}
