<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded(['id'])]
class Appeal extends Model
{
    public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

    public function reputationLog(): BelongsTo
    {
        return $this->belongsTo(ReputationLog::class);
    }

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }
}
