<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use App\Services\ReadTimeCalculator;
use BeyondCode\Comments\Traits\HasComments;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string               $id
 * @property int|null             $author_id
 * @property Status               $status
 * @property string               $title
 * @property string               $content
 * @property int                  $views
 * @property bool                 $comments_enabled
 * @property \Carbon\Carbon|null  $published_at
 * @property \Carbon\Carbon|null  $created_at
 * @property \Carbon\Carbon|null  $updated_at
 *
 * @package App\Models
 */
final class Blog extends Model implements Feedable
{
    use HasFactory;
    use HasUlids;
    use HasComments;

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => Status::Draft,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(callback: [
                'name' => config('app.name')
            ]);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->withDefault(callback: ['name' => config('app.name')]);
    }

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, table: 'post_categories');
    }

    protected function casts(): array
    {
        return [
            'comments_enabled' => 'boolean',
            'status' => Status::class,
        ];
    }

    /**
     * Get the estimated read time for the post.
     * This will automatically be available as $post->read_time.
     */
    public function getReadTimeAttribute(): string
    {
        // Resolve the service from the container
        $calculator = app(ReadTimeCalculator::class);

        return $calculator->calculate($this->content);
    }

    /**
     * Get the estimated read time for the post in raw minutes.
     * This will automatically be available as $post->read_time_in_minutes.
     */
    public function getReadTimeInMinutesAttribute(): int
    {
        $calculator = app(ReadTimeCalculator::class);

        return $calculator->calculateInMinutes($this->content);
    }

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

    public static function getFeedItems()
    {
        return Blog::with('author')->where('status', Status::Published)->get();
    }

    public function getLinkAttribute()
    {
        return route('news:show', $this);
    }
}
