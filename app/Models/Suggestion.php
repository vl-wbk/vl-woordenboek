<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LanguageStatus;
use App\Enums\SuggestionStatus;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property SuggestionStatus $state The enumeration clpass that contains all the states of the suggestion.
 */
final class Suggestion extends Model
{
    /** @use HasFactory<\Database\Factories\SuggestionFactory> */
    use HasFactory;
    use BelongsToManyRegions;

    protected $fillable = ['word', 'state', 'assignee_id', 'rejector_id', 'approver_id', 'description', 'example', 'characteristics'];

    protected $attributes = [
        'state' => SuggestionStatus::New,
        'status' => LanguageStatus::Onbekend,
    ];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejector_id');
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'state' => SuggestionStatus::class,
            'status' => LanguageStatus::class,
        ];
    }
}
