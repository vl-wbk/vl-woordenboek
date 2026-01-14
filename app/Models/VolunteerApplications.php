<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Volunteers\ApplicationState;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Model;

final class VolunteerApplications extends Model
{
    protected $guarded = ['id', 'user_id'];

    protected $attributes = [
        'state' => ApplicationState::Open,
    ];

    protected function casts(): array
    {
        return [
            'regions' => 'array',
            'state' => ApplicationState::class,
        ];
    }
}
