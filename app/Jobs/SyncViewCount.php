<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

final class SyncViewCount implements ShouldQueue
{
    use Queueable;
    use Dispatchable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param int $modelId The primary key of the model.
     * @param string $cacheKey The Redis key containing the buffered view count.
     */
    public function __construct(
        protected string $modelClass,
        protected int $modelId,
        protected string $cacheKey,
    ) {
    }

    /**
     * Execute the job.
     * * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        // 1. Get the current count from Redis
        $count = (int) Redis::get($this->cacheKey);

        if ($count <= 0) {
            return;
        }

        // 2. Perform atomic updates
        DB::transaction(function () use ($count) {
            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = $this->modelClass::findOrFail($this->modelId);

            // Use increment to perform the math in SQL: UPDATE ... SET views = views + X
            // We use timestamps = false to prevent the view sync from changing 'updated_at'
            $model->timestamps = false;
            $model->increment('views', $count);

            // 3. Subtract processed amount from Redis
            Redis::decrby($this->cacheKey, $count);
        });
    }

    /**
     * Get the middleware the job should pass through.
     * * @return array<int, object>
     */
    public function middleware(): array
    {
        return [
            (new \Illuminate\Queue\Middleware\WithoutOverlapping($this->cacheKey))
                ->releaseAfter(60)
        ];
    }
}
