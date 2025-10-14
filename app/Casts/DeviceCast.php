<?php

namespace App\Casts;

use App\Services\AgentService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DeviceCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $agent = tap(new AgentService(), fn($agent) => $agent->setUserAgent($model->user_agent));

        return match (true) {
            $agent->isMobile() => 'Mobiel',
            $agent->isTablet() => 'Tablet',
            $agent->isDesktop() => 'Desktop',
        };
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
