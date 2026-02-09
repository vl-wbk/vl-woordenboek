<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

final class SyncViewCount implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;

    public function __construct(
        protected string $modelClass,
        protected int $modelId,
        protected string $cacheKey,
        protected string $lockKey,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $count = Redis::getset($this->cacheKey, 0);

        if ($count > 0) {
            $this->modelClass::where('id', $this->modelId)
                ->increment('views', (int) $count);
        }

        Redis::del($this->lockKey);
    }
}
