<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace App\Models;

use App\Builders\BlogBuilder;
use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Carbon\Carbon;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use App\Services\ReadTimeCalculator;
use App\States\Posts\PublicationStateContract;
use App\States\Posts;
use BeyondCode\Comments\Traits\HasComments;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Represents a blog post within the application.
 * This model handles the data and logic associated with invidual blog entries, including their content, publication status, authorship, and related features like comment and read time calculations.
 *
 * @property  string        $id                The unique ULID (Universally Unique Lexicographically Sortable Identifier) for the blog post, serving as its primary key.
 * @property  int|null      $author_id         The foreign key linking to the `User` model, representing the author of the blog post. This can be null if the author is not specified or has been removed.
 * @property  Status        $status            The current publication status of the blog post, defined by the `App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status` enum (e.g., Draft, Published).
 * @property  string        $title             The title of the blog post, a concise heading for the content.
 * @property  string        $content           The full body content of the blog post, typically stored as HTML or Markdown.
 * @property  int           $views             The number of times the blog post has been viewed, used for tracking popularity.
 * @property  bool          $comments_enabled  A boolean flag indicating whether comments are allowed on this specific blog post.
 * @property  Carbon|null   $published_at      The timestamp when the blog post was officially published and made publicly visible. This can be null for draft posts.
 * @property  Carbon|null   $created_at        The timestamp when the blog post record was initially created in the database.
 * @property  Carbon|null   $updated_at        The timestamp when the blog post record was last updated in the database.
 *
 * @property-read  User     $author            The `User` model instance representing the author of the blog post. This relation will default to the application's name if no author is explicitly set.
 * @property-read  string   $link              The generated URL to publicly view this specific blog post.
 * @property-read  string   $read_time         The estimated reading time of the post, presented in a human-readable format (e.g., "5 min read"). This is a computed attribute.
 * @property-read  int  $read_time_in_minutes  The estimated reading time of the post, presented as an integer representing the number of minutes. This is a computed attribute.
 *
 * @package App\Models
 */
final class Blog extends Model implements Feedable
{
    /** @use HasFactory<BlogFactory> */
    use HasFactory;
    use HasUlids;
    use HasComments;

    /**
     * The attributes that are not mass assignable.
     * This array specifies which attributes cannot be filled via mass assignment, ensuring that the 'id' field, being a primary key, is protected.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The model's default attribute values.
     * These values are automatically assigned to new model instances if not explicitly provided, ensuring a default status of `Draft` for new blog posts.
     *
     * @var array<string, Status>
     */
    protected $attributes = [
        'status' => Status::Draft,
    ];

    /**
     * Get the author that owns the blog post.
     *
     * This defines a `BelongsTo` relationship with the `User` model, indicating that each blog post is associated with a single author.
     * If no author is found or specified, it defaults to the application's name.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(callback: [
                'name' => config('app.name')
            ]);
    }

    /**
     * Get the publication status state object for the blog post.
     *
     * This method returns a state object implementing `PublicationStateContract` based on the current `status` of the blog post.
     * This allows for polymorphic behavior related to publication logic (e.g., publishing, drafting).
     */
    public function publicationStatus(): PublicationStateContract
    {
        return match($this->status) {
            Status::Draft => new Posts\DraftState($this),
            Status::Published => new Posts\PublishedState($this),
        };
    }

    /**
     * Get the publisher that published the blog post.
     *
     * This defines a `BelongsTo` relationship with the `User` model, which could represent the user who last changed the post's status to "Published".
     * It defaults to the application's name if no publisher is found.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(callback: ['name' => config('app.name')]);
    }

    /**
     * Get the categories that belong to the blog post.
     *
     * This defines a `BelongsToMany` relationship with the `Category` model, indicating that a blog post can belong to multiple categories, and a category can have many blog posts.
     * The relationship is managed through the `post_categories` pivot table.
     *
     * @return BelongsToMany<Category, covariant $this>
     */
    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, table: 'post_categories');
    }

    /**
     * Get the casts for the model.
     *
     * This method defines how certain attributes should be cast when they are retrieved from or saved to the database.
     * It ensures that `comments_enabled` is treated as a boolean and `status` as an instance of the `Status` enum.
     *
     * @return array<string, string> An associative array where keys are attribute names and values are the cast types.
     */
    protected function casts(): array
    {
        return [
            'comments_enabled' => 'boolean',
            'status' => Status::class,
        ];
    }

     /**
     * Get the estimated read time for the post in a human-readable format.
     *
     * This accessor dynamically calculates the reading time based on the post's content using the `ReadTimeCalculator` service and formats it for display.
     * Accessible via `$blog->read_time`.
     *
     * @return string The estimated reading time (e.g., "5 min read").
     */
    public function getReadTimeAttribute(): string
    {
        $calculator = app(ReadTimeCalculator::class);

        return $calculator->calculate($this->content);
    }

    /**
     * Get the estimated read time for the post in raw minutes.
     *
     * This accessor dynamically calculates the reading time in integer minutes based on the post's content using the `ReadTimeCalculator` service.
     * Accessible via `$blog->read_time_in_minutes`.
     *
     * @return int The estimated reading time in minutes.
     */
    public function getReadTimeInMinutesAttribute(): int
    {
        $calculator = app(ReadTimeCalculator::class);

        return $calculator->calculateInMinutes($this->content);
    }

    /**
     * Converts the blog post to a FeedItem for RSS feeds.
     *
     * This method implements the `Feedable` interface, allowing blog posts to be easily included in RSS or Atom feeds generated by Spatie's Laravel Feed package.
     * It maps the model's attributes to the required `FeedItem` properties.
     *
     * @return FeedItem An instance of `FeedItem` representing the blog post.
     */
    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->content)
            ->updated($this->updated_at)
            ->link($this->link)
            ->authorName($this->author->name);
    }

    /**
     * Get the feed items (published blog posts) for the RSS feed.
     *
     * This static method is required by the `Feedable` interface and returns a collection of blog posts that should be included in the RSS feed.
     * It specifically fetches posts that are `Published` and eager-loads their authors.
     *
     * @return Collection<int, \App\Models\Blog> A collection of `Blog` models.
     */
    public static function getFeedItems(): Collection
    {
        return Blog::with('author')->where('status', Status::Published)->get();
    }

    /**
     * Get the URL link to the blog post.
     * This accessor dynamically generates the public URL for the blog post using Laravel's route helper. Accessible via `$blog->link`.
     *
     * @return string The absolute URL to the blog post.
     */
    public function getLinkAttribute(): string
    {
        return route('news:show', $this);
    }

    /**
     * Create a new Eloquent query builder for the model.
     * This method overrides the default Eloquent builder to return a custom `BlogBuilder` instance, allowing for custom query methods specific to the Blog model.
     *
     * @param  \Illuminate\Database\Query\Builder $query The underlying query builder instance.
     * @return BlogBuilder A new instance of `BlogBuilder`.
     */
    public function newEloquentBuilder($query): BlogBuilder
    {
        return new BlogBuilder($query);
    }
}
