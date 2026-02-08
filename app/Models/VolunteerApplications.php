<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Volunteers\ApplicationState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property ApplicationState $state
 * @property ?int $volunteer_position_id
 * @property int $user_id
 * @property string $firstname
 * @property string $lastname
 * @property ?string $motovation
 * @property ?string $background
 * @property array|null $regions
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
final class VolunteerApplications extends Model
{
    /**
     * @var list<string>
     */
    protected $guarded = ['id', 'user_id'];

    /**
     * @var array<string, ApplicationState>
     */
    protected $attributes = [
        'state' => ApplicationState::Open,
    ];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<VolunteerPosition, covariant $this>
     */
    public function volunteerPosition(): BelongsTo
    {
        return $this->belongsTo(VolunteerPosition::class);
    }

    protected function casts(): array
    {
        return [
            'regions' => 'array',
            'state' => ApplicationState::class,
        ];
    }
}
