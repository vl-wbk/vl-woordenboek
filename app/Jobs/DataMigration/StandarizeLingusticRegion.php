<?php

declare(strict_types=1);

namespace App\Jobs\DataMigration;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class StandarizeLingusticRegion implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Article $article
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->article->updateQuietly(attributes: [
            'region_id' => $this->convertLinguisticRegion()
        ]);
    }

    private function convertLinguisticRegion(): int
    {
    }
}
