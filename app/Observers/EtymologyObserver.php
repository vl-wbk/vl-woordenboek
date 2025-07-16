<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Etymology;

final readonly class EtymologyObserver
{
    public function creating(Etymology $etymology): void
    {
        $userId = auth()->id();

        match (true) {
            $etymology->status->isArchived() => $etymology->fill(['archived_at' => now(), 'author_id' => $userId, 'archived_by' => $userId]),
            $etymology->status->isRejected() => $etymology->fill(['rejected_at' => now(), 'author_id' => $userId, 'rejected_by' => $userId]),
            $etymology->status->isPublished() => $etymology->fill(['published_at' => now(), 'author_id' => $userId, 'published_by' => $userId]),
        };
    }
}
