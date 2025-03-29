<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\ArticleStates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class ArticleBuilder extends Builder
{
    public function archive(?string $archivingReason = null): void
    {
        DB::transaction(function () use ($archivingReason): void {
            $this->model->update(attributes: [
                'state' => ArticleStates::Archived, 'archiving_reason' => $archivingReason, 'archived_at' => now()
            ]);

            $this->model->archiever()->associate(auth()->user())->save();
        });
    }

    public function unarchive(): void
    {
        DB::transaction(function (): void {
            $this->model->update(attributes: ['state' => ArticleStates::Published, 'archiving_reason' => null, 'archived_at' => null]);
            $this->model->archiever()->associate(null)->save();
        });
    }
}
