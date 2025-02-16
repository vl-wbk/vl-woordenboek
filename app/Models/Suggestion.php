<?php

namespace App\Models;

use App\Enums\LanguageStatus;
use App\Enums\SuggestionStatus;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Suggestion extends Model
{
    /** @use HasFactory<\Database\Factories\SuggestionFactory> */
    use HasFactory;
    use BelongsToManyRegions;

    protected $fillable = ['word', 'state', 'assignee_id', 'rejector_id', 'approver_id', 'description', 'example', 'characteristics'];

    protected $attributes = [
        'state' => SuggestionStatus::New,
        'status' => LanguageStatus::Onbekend,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejector_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    protected function casts(): array
    {
        return [
            'state' => SuggestionStatus::class,
            'status' => LanguageStatus::class,
        ];
    }
}
