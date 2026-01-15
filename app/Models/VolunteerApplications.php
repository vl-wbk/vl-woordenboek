<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Volunteers\ApplicationState;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class VolunteerApplications extends Model
{
    protected $guarded = ['id', 'user_id'];

    protected $attributes = [
        'state' => ApplicationState::Open,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



    protected function casts(): array
    {
        return [
            'regions' => 'array',
            'state' => ApplicationState::class,
        ];
    }
}
