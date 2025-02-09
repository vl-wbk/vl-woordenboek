<?php

namespace App\Models;

use App\Enums\SuggestionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Suggestion extends Model
{
    /** @use HasFactory<\Database\Factories\SuggestionFactory> */
    use HasFactory;

    protected $fillable = ['word', 'status', 'description', 'example', 'characteristics'];

    protected $attributes = [
        'status' => SuggestionStatus::New
    ];

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    protected function casts(): array
    {
        return [
            'status' => SuggestionStatus::class,
        ];
    }
}
