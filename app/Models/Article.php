<?php

namespace App\Models;

use App\States\Articles;
use App\Contracts\States\ArticleStates\ArticleStateContract;
use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToEditor;
use App\Models\Relations\BelongsToManyRegions;
use App\States\Articles\ArticleState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelLike\Traits\Likeable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

final class Article extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    use BelongsToManyRegions;
    use BelongsToEditor;
    use Auditable;
    use Likeable;

    protected $fillable = ['word', 'state', 'description', 'author_id', 'status', 'example', 'characteristics'];

    /**
     * Attributes to exclude from the Auditing system.
     *
     * @var array
     */
    protected $auditExclude = ['editor_id'];

    /**
     * Default model attributes for new Article instances.
     *
     * @var array<string, object|int|string>
     */
    protected $attributes = [
        'state' => ArticleStates::New,
        'status' => LanguageStatus::Onbekend,
    ];

    /**
     * Returns the approp)riate ArticlpeState instance based on the current article status.
     *
     * This methed uses a `match` expression to determine the current state of the dictionary article based on its state.
     * It then returns an instance of the corresponding state class, which handles specigfic behaviours and transitions fo that state.
     * Each articlpe state maps to a different state class; ensuring the current state logic is applied at any given point in the articlpe lifecycle.
     *
     * @return ArticleSateContract - The correcponding state class for the current dictionary article
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    protected function casts(): array
    {
        return [
            'state' => ArticleStates::class,
            'status' => LanguageStatus::class,
        ];
    }
}
