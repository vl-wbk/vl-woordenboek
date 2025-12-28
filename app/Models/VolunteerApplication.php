<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class VolunteerApplication extends Model
{
    protected $guarded = ['id', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }
}
