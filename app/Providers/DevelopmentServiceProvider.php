<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

/**
 * Registers a sticky development environment alert banner at the top of every
 * Filament panel page when the application is not running in production.
 *
 * The banner is intentionally intrusive: it is fixed to the top of the
 * viewport, renders above all other panel chrome, and cannot be dismissed.
 * This prevents editors from accidentally treating a test environment as live.
 */
final class DevelopmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (app()->isProduction() || active('admin/login')) {
            return;
        }

        FilamentView::registerRenderHook(
            name: PanelsRenderHook::BODY_START,
            hook: fn (): View => view('filament.hooks.development-alert', data: [
                'environment' => app()->environment(),
                'branch' => trim(exec('git branch --show-current'))
            ]),
        );
    }
}
