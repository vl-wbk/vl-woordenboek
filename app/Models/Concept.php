<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Concept extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['word', 'description', 'characteristics', 'notify_author', 'author_id', 'part_of_speech_id'];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function authoredBy(User $user): bool
    {
        return $this->author()->is($user);
    }

    /**
     * @return BelongsTo<PartOfSpeech, covariant $this>
     */
    public function partOfSpeech(): BelongsTo
    {
        return $this->belongsTo(PartOfSpeech::class);
    }

    /**
     * @return BelongsToMany<Region, covariant $this>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }
}
