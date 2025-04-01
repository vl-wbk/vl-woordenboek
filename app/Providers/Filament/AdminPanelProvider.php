<?php

namespace App\Providers\Filament;

use Cog\Laravel\Ban\Http\Middleware\ForbidBannedUser;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\FontProviders\BunnyFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Config;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\ResourceLock\ResourceLockPlugin;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('VL. woordenboek')
            ->maxContentWidth(MaxWidth::Full)
            ->topNavigation()
            ->font('Nunito', BunnyFontProvider::class)
            ->login()
            ->passwordReset()
            ->databaseNotifications()
            ->colors( [
                'primary' => '#2D6A4F', // Deep Evergreen (Main Accent, Rich & Grounded)
                'secondary' => '#52796F', // Moss Green (Subtle Secondary Highlights)
                'background' => '#F1F5E1', // Soft Lichen (Natural, Warm Background)
                'surface' => '#FFFFFF', // Birch White (For Cards, Modals, Panels)
                'text' => '#2B3A2F', // Dark Cypress (Highly Readable, No Eye Strain)
                'muted' => '#8D9A83', // Sage Green (For Subtle UI Elements)
                'border' => '#A3B18A', // Soft Olive (Delicate Dividers & Separators)
                'highlight' => '#B08968', // Earthy Bark (Important Accents, Buttons)
                'success' => '#3A5A40', // Forest Fern (Encouraging, Trustworthy Green)
                'warning' => '#D4A373', // Warm Amber (Gentle, Organic Warnings)
                'danger' => '#A53838', // Redwood (Strong but Natural Error Feedback)
                'shadow' => '#1E2F23', // Deep Pine (For Depth & Contrast)
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Verlaat beheersconsole')
                    ->url(fn (): string => route('home'))
                    ->icon('heroicon-o-arrow-left-start-on-rectangle'),
                MenuItem::make()
                    ->label('Account instellingen')
                    ->url(fn (): string => route('profile.settings'))
                    ->icon('heroicon-o-adjustments-horizontal')
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                ResourceLockPlugin::make(),
                EasyFooterPlugin::make()
                    ->withGithub()
                    ->withLoadTime()
                    ->withLinks([
                        ['title' => 'Voorwaarden', 'url' => url('voorwaarden')],
                    ]),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(Config::boolean('app.debug', false))
                    ->users($this->defaultLoginsDuringDevelopment())
                ])
            ->middleware([ForbidBannedUser::class])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * @return array<string, string>
     */
    private function defaultLoginsDuringDevelopment(): array
    {
        return [
            'Redacteur' => 'Redacteur@domain.tld',
            'Eind redacteur' => 'Eindredacteur@domain.tld',
            'Ontwikkelaar' => 'Ontwikkelaar@domain.tld',
            'Administrator' => 'Administrator@domain.tld',
        ];
    }
}
