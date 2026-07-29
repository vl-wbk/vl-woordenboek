<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Database\Factories\WordOfTheDayFactory;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * This model acts as our curation engine. It Allows Community Managers and maintainers with the correct permissions  to spotlight linguistic gems from the dictionary. 
 * By decloupling the 'Article' (the data) from the 'WordOfTheDay' (the schedule), we can manage a chronological queue of features content without bloating  the main articles table.
 * 
 * For contributors: this model is key to our front-facing engagement.
 * If you're looking to improve how we surface content to users or how we track editorial history, this is the place to start.
 * 
 * @property int               $id                          The unique identifier for the entry. 
 * @property int|null          $scheduled_by                Foreign key - The unique identifier from the user who scheduled this word.
 * @property int               $article_id                  Foreign key - The unique identifier of the article being feature
 * @property Carbon            $scheduled_for               The date the article is set to be the "Word of the Day".
 * @property string            $scheduling_reason           A text description of why this specific article was chosen. 
 * @property Carbon|null       $created_at                  Timestamp when the record was created.
 * @property Carbon|null       $updated_at                  Timestamp when the record was last modified.
 * 
 * @property-read string|null  $formatted_scheduled_for     The date formatted for display (e.g., "01 January, 2024"). 
 * @property-read Article      $article                     The Article instance associated with this entry.
 * @property-read User|null    $planner                     The User instance who scheduled the entry.
 * 
 * @package App\Models 
 */
final class WordOfTheDay extends Model implements Feedable
{
    /** @use HasFactory<WordOfTheDayFactory> */
    use HasFactory;

    /**
     * We use $guarded for the ID to prevent accidental overwrites during mass assignment, 
     * while keeping other fields open for easy contribution through our editorial forms.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Every 'Word of the Day' must point to a valid article. 
     * This relationship ensures that our featured content is always backed by the rich linguistic data stored in our dictionary.
     *
     * @return BelongsTo<Article, covariant $this>
     */
    public function article(): BelongsTo
    {
        return $this->BelongsTo(Article::class);
    }

     /**
     * Tracks the authorship of the schedule. 
     * 
     * In an open-source/open-data spirit, we believe in 'giving credit where credit is due.'
     * This links the entry to the specific contributor who contributed to article in the application.
     *
     * @return BelongsTo<User, covariant $this>
     */
    public function planner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    /**
     * Type-casting is essential for our contributors to have a predictable developer experience. 
     * By casting 'scheduled_for' to a date, we ensure Carbon's API is always available for date manipulations.
     *
     * @return array<string, string>
     */
    protected function casts(): array 
    {
        return [
            'scheduled_for' => 'date',
        ];
    }

    /**
     * This accessor provides a standardized format for the frontend.
     * By centralizing the date format here (d F, Y), we ensure that the  "Word of the Day" looks consistent whether it's on the homepage, an archive list, or a social share card.
     * 
     * @return Attribute<string, never>
     */
    protected function formattedScheduledFor(): Attribute
    {
        return Attribute::get(fn () => $this->scheduled_for->translatedFormat('d F, Y'));
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->authorName($this->article->author->name)
            ->link(route('word-information.show', $this->article))
            ->summary((string) str($this->article->description)->words(20)->markdown()->stripTags()->trim())
            ->updated($this->scheduled_for)
            ->title($this->article->word);
    }

    public static function getFeedItems()
    {
        return WordOfTheDay::with('article')
            ->where('scheduled_for', '<=', today())
            ->orderBy('scheduled_for', 'desc')
            ->get();
    }
}
