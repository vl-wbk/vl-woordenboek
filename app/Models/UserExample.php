<?php

namespace App\Models;

use App\States\ExampleSentence\SentenceState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

final class UserExample extends Model
{
    use HasStates;

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * @return BelongsTo<Article, covariant $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(function ($user, $example) {
                $user->name = $example->contributor_name ?? config('app.name', 'Laravel');
            });
    }

    protected function casts(): array
    {
        return [
            'status' => SentenceState::class,
        ];
    }
}
