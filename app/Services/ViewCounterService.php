<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\SyncViewCount;
use Illuminate\Support\Facades\Redis;

final readonly class ViewCounterService
{
    public function incrementAndSync($model, int $delayInSeconds = 60)
    {
        $modelName = strtolower(class_basename($model));
        $cacheKey = "{$modelName}:views:{$model->id}";
        $lockKey = "{$modelName}:sync:lock:{$model->id}";

        Redis::incr($cacheKey);

        if (! Redis::get($lockKey)) {
            Redis::setex($lockKey, $delayInSeconds, true);

            SyncViewCount::dispatch(get_class($model), $model->id, $cacheKey, $lockKey)
                ->delay(now()->addSeconds($delayInSeconds));
        }
    }
}
