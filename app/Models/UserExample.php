<?php

namespace App\Models;

use App\Enums\Articles\ExampleSentenceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExample extends Model
{
    protected $guarded = ['id'];

    protected $attributes = [
        'status' => ExampleSentenceStatus::Pending,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => ExampleSentenceStatus::class,
        ];
    }
}
