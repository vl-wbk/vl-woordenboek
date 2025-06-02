<?php

declare(strict_types=1);

namespace App\Models;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

final class Blog extends Model implements Feedable
{
    use HasFactory;
    use HasUlids;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
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
        return static::with('author')->where('status', Status::Published)->get();
    }

    public function getLinkAttribute()
    {
        return route('news:show', $this);
    }
}
