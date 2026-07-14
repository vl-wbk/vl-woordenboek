<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ConceptObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, MorphMany};


#[ObservedBy(ConceptObserver::class)]
final class Concept extends Model
{
    use HasFactory;

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
     * @return MorphMany<UserExample, covariant $this>
     */
    public function userExamples(): MorphMany
    {
        return $this->morphMany(UserExample::class, 'exampleable');
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
