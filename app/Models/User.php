<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\UserBuilder;
use App\Mail\AccountPrunedMailable;
use App\Models\Relations\Contactable;
use App\Notifications\AccountDeletedNotification;
use App\Notifications\RegistrationWelcomeNotification;
use App\Observers\UserObserver;
use App\UserTypes;
use Carbon\Carbon;
use Cmgmyr\Messenger\Traits\Messagable;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Kirschbaum\Commentions\Contracts\Commenter;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Overtrue\LaravelLike\Traits\Liker;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Pennant\Concerns\HasFeatures;
use Laravel\Sanctum\HasApiTokens;
use Override;
use Spatie\Permission\Traits\HasRoles;

/**
 * User represents an authenticated account in the 'Vlaams woordenboek app'.
 *
 * This model handles user authentication, authorization, and profile management.
 * It supports role-based access control through user types, welcome notifications for new users,
 * and interaction tracking through the "likes" system.
 *
 * @property int $id                 Unique identifier for the user
 * @property string $name               The unique username from the account
 * @property string $firstname          User's first name
 * @property string $lastname           User's last name
 * @property string $email              User's email address for authentication
 * @property ?string $bio                  A short description of the user in the platform
 * @property ?string $twitter              The URL to the twitter account of the user.
 * @property ?string $bluesky              The URL to the bluesky account of the user.
 * @property ?string $website              The URL to the website of the user
 * @property UserTypes $user_type          The assigned role group
 * @property string $password           Hashed password for authentication
 * @property Carbon|null $last_seen_at       Timestamp of last activity
 * @property Carbon|null $email_verified_at  Timestamp of email verification
 * @property string|null $remember_token     Token for the "remember me" feature
 * @property Carbon|null $banned_at          Timestamp from when the user account has been banned.
 * @property bool $is_beta_tester     Indicates that the user is a beta tester of not.
 * @property Carbon $created_at         Timestamp of account creation
 * @property Carbon $updated_at         Timestamp of the last update
 *
 * @method bans()
 * @method static UserBuilder|static query()
 * @method UserBuilder newQuery()
 * @method searchContributions(string $string, ?string $string1, string $etymology)
 *
 * @package App\Models
 */
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, BannableInterface, MustVerifyEmail, Commenter
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use ReceivesWelcomeNotification;
    use Notifiable;
    use Liker;
    use Bannable;
    use HasApiTokens;
    use HasFeatures;
    use HasRoles;
    use Messagable;
    use Contactable;
    use TwoFactorAuthenticatable;
    use Prunable;

    /**
     * Specifies which attributes can be mass assigned when creating or updating user records.
     * This provides a security layer against mass-assignment vulnerabilities by explicitly listing allowed fields.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'bluesky', 'twitter', 'website', 'firstname', 'lastname', 'inactivity_warning_sent_at', 'is_beta_tester', 'email', 'user_type', 'password', 'last_seen_at', 'email_verified_at', 'google_id', 'google_token', 'google_refresh_token'];

    /**
     * Defines default values for new user instances.
     * Every new user starts with normal privileges until explicitly upgraded by an administrator.
     *
     * @var array<string, UserTypes>
     */
    protected $attributes = ['user_type' => UserTypes::Normal];

    /**
     * Specifies attributes that should be hidden when the model is serialized.
     * This prevents sensitive data like passwords from being exposed in API responses of JSON serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Determines whether a user can access the admin panel interface.
     * Access is granted based on the 'access-backend' permission, which is typically assigned to editorial and administrative roles.
     *
     *
     * @codeCoverageIgnore This method is typically auto-generated by Filament, and its behavior is implicitly tested through end-to-end tests of the admin panel functionality. Explicit unit tests are therefore redundant.
     * @see /tests/Feature/Filament/BackendAccessTest.php - Tests the backend access functionality.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('access-backend');
    }

    /**
     * Returns all article suggestions submitted by this user.
     *
     * Each suggested article is linked back to the user via the 'author_id' field.
     * Use this relationship to fetch or query the suggestions made by the user.
     *
     * @return HasMany<Article, covariant $this> A collection of Article instances representing the user's suggestions.
     */
    public function suggestions(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Returns all article reports submitted by this user.
     *
     * Each report is associated with the user who submitted it using the 'author_id' field.
     * Use this relationship to access any reports related to articles made by the user.
     *
     * @return HasMany<ArticleReport, covariant $this> A collection of ArticleReport instances representing the user's reports.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ArticleReport::class, 'author_id');
    }

    /**
     * Finds and collects all the articles that this user has written.
     *
     * This function connects a single author (this user) to multiple articles.
     * Think of it as opening a file cabinet labeled with this user's name: everything inside is the articles they have contributed.
     *
     * @return HasMany<Blog, covariant $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Finds and collects all the etymology records that this user has worked on.
     *
     * This function connects this author to many etymology records. It is the system's way of efficiently tracking authorship and managing the editorial workflow.
     * When you need to see a list of everything this author has touched—from drafts to final versions—this is the method the system uses.
     *
     * @return HasMany<Etymology, covariant $this>
     */
    public function etymologies(): HasMany
    {
        return $this->hasMany(Etymology::class, 'author_id');
    }

    /**
     * Defines the relationship between a user and their bookmarked articles.
     *
     * This method establishes a many-to-many relationship between the User model and the Article model, using the 'article_bookmarks' pivot table.
     * This allows a user to bookmark multiple articles, and an article to be bookmarked by multiple users.
     *
     * @return BelongsToMany<Article, covariant $this> A collection of Article instances that the user has bookmarked.
     */
    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, table: 'article_bookmarks');
    }

    /**
     * Sends the initial welcome notification to newly created users.
     * The notification includes a time-limited link for setting up their password and activating their account.
     *
     * @param Carbon $validUntil Expiration timestamp for the welcome link.
     */
    public function sendWelcomeNotification(Carbon $validUntil): void
    {
        $this->notify(new RegistrationWelcomeNotification($validUntil));
    }

    /**
     * Overrides the default Eloquent builder with a custom UserBuilder.
     *
     * This method ensures that all queries for the User model use the custom builder,
     * which includes additional methods for managing user types and such.
     *
     * @param Builder $query The base query builder instance
     * @return UserBuilder     The custom builder instance
     */
    #[Override]
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function isTester(): bool
    {
        return $this->is_beta_tester;
    }

    public function prunable(): EloquentBuilder
    {
        return static::where('last_seen_at', '<', now()->subMonths(6))
            ->whereNotNull('inactivity_warning_sent_at');
    }

    protected function pruning(): void
    {
        Mail::to($this->email)->queue(new AccountPrunedMailable());
    }

    /**
     * Configures attribute casting for proper type handling.
     * This ensures that dates are properly handled as Carbon instances and that the user type cast to its own enum representation.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'google_id' => 'encrypted',
            'google_token' => 'encrypted',
            'google_refresh_token' => 'encrypted',
            'is_beta_tester' => 'boolean',
            'user_type' => UserTypes::class,
            'last_seen_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
