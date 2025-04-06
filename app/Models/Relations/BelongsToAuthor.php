<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToAuthor
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setCurrentUserAsAuthor(): void
    {
        $this->setAuthor(auth()->user());
    }

    public function setAuthor(User $user): void
    {
        $this->author()->associate($user)->save();
    }
}
