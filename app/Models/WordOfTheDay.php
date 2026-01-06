<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WordOfTheDay extends Model
{
    protected $guarded = ['id'];

    public function article(): BelongsTo
    {
        return $this->BelongsTo(Article::class);
    }

    public function planner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    protected function casts(): array 
    {
        return [
            'scheduled_for' => 'date',
        ];
    }

    protected function formattedScheduledFor(): Attribute
    {
        return Attribute::get(fn () => 
            $this->scheduled_for?->translatedFormat('d F, Y')
        );
    }
}
