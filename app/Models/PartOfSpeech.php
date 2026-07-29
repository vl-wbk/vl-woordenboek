<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\PartOfSpeechFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Part of the speech model
 *
 * This model represents grammatical categories in the Flemish dictionary application.
 * Each instance defines a specific part of speech that helps classify words in the dictionary based on their grammatical function and behavior sentences.
 *
 * The model serves as a fundamental building block for linguistic categorization, enabling proper classification of dictionary entries.
 * It Maintains a clear separation between Dutch terminology and English equivalents.
 *
 * @property int             $id           The unique identifier for this part of speech
 * @property string          $name         The Dutch name of the grammatical category
 * @property string          $value        The English equivalent or supplementary information
 * @property bool            $suggestible. The column that indicates is the data can be used in the suggestion form.
 * @property Carbon|null     $created_at   When the record was created
 * @property Carbon|null     $updated_at   When the record was last modified
 *
 * @package App\Models
 */
#[Fillable(columns: ['name', 'value', 'suggestible'])]
final class PartOfSpeech extends Model
{
    /** @use HasFactory<PartOfSpeechFactory> */
    use HasFactory;

    /**
     * Default eager loading configuration.
     *
     * We load 'articles' by default to avoid N+1 issues when displaying  categories alongside their associated entries. 
     * Use with caution if memory usage becomes a bottleneck.
     * 
     * @var list<string>
     */
    protected $with = ['articles'];

    /**
     * Get the articles associated with this part of speech. 
     * 
     * Defines the one-to-many relationship with the Article Model. 
     * Since we eager-load this relationship by default, ensure you are aware of the 
     * memory impact when retrieving large collections of PartofSpeech records. 
     * 
     * @return HasMany<Article, covariant $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Define attribute type casting. 
     * 
     * Ensure 'suggestible' is always treated as a boolean to maintain strict type safety 
     * when evaluating it in our blade views or logic. 
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'suggestible' => 'boolean',
        ];
    }
}
