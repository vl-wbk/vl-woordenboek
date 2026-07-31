<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ConceptFactory;
use App\Observers\ConceptObserver;
use Illuminate\Database\Eloquent\Attributes\{Fillable, ObservedBy};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, MorphMany};

/**
 * Represents a linguistic concept, serving as one of the central domain entities in the application.
 *
 * If you are a new contributor or stepping in to maintain this codebase, this model sits at the
 * heart of the application logic. It ties together user contributions, grammatical classifications,
 * regional variations, and contextual examples. Changes here will ripple across search, curation,
 * and user profile statistics.
 *
 * @property int         $id                The unique identifier from the record in the concepts table.
 * @property string|null $author_id         The unique identifier from the author in the flemish dictionary.
 * @property int|null    $part_of_speech_id The unique identifier from the part of speech in the flemish dictionary.
 * @property string|null $word              The word (title) of the concept.
 * @property string|null $characteristics   The characteristics of the word in the flemish dictionary.
 * @property string|null $description       The description of the dictionary article.
 * @property bool        $notify_author     The boolean flag that indicates that the user wants to get notified when the suggestion is published.
 * @property Carbon|null $created_at        The timestamp that indicates when the article was created in the application.
 * @property Carbon|null $updated_at        The timestamp that indicated when the article was modified for the last time in the application.
 *
 * @package App\Models
 */
#[ObservedBy(classes: ConceptObserver::class)]
#[Fillable('word', 'description', 'characteristics', 'notify_author', 'author_id', 'part_of_speech_id')]
final class Concept extends Model
{
    /** @use HasFactory<ConceptFactory> */
    use HasFactory;

    /**
     * Establishes a standard belongs-to relationship pointing to the user account that orginally creatd this concept.
     *
     * This is primarily used for attribution, permission checking and displaying
     * author information across the application interface.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determines whether a given user account is the original author of this concept.
     *
     * This method leverages Eloquent's internal object identity comparison (the 'is' method)
     * to check ownership efficiently, avoiding redundant database queries if the author
     * relation is already eager-loaded.
     *
     * @param  User $user  The user accountr instance tro evaluate against the concept author.
     * @return bool        Return true if the provided user authored this concept, and false otherwise.
     */
    public function authoredBy(User $user): bool
    {
        return $this->author()->is($user);
    }

    /**
     * Establishes a polymorphic many relationship connecting this concept to user-submitted examples.
     *
     * Through the 'exampleable' morph map, various parts of the application can attach
     * real-world usage examples, sentences, or contextual notes directly to a concept.
     * without being strictly bound to a single table structure.
     *
     * @return MorphMany<UserExample, covariant $this>
     */
    public function userExamples(): MorphMany
    {
        return $this->morphMany(UserExample::class, 'exampleable');
    }

    /**
     * Connects the concept to its grammatical part of speech classification.
     *
     * This defines whether the concept functions as a noun, verb, adjective, or another
     * linguistic category, helping structure how terms are displayed and filtered.
     *
     * @return BelongsTo<PartOfSpeech, covariant $this>
     */
    public function partOfSpeech(): BelongsTo
    {
        return $this->belongsTo(PartOfSpeech::class);
    }

    /**
     * Links the concept to multiple geographical regions through a many-to-many relationship.
     *
     * This allows the platform to track where specific terms, spellings, or conceptual
     * variations are actively used or recognized geographically.
     *
     * @return BelongsToMany<Region, covariant $this>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }
}
