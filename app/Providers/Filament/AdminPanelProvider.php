<?php

namespace App\Providers\Filament;

use Filament\Support\Enums\Width;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Cog\Laravel\Ban\Http\Middleware\ForbidBannedUser;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Config;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Kenepa\ResourceLock\ResourceLockPlugin;
use Kenepa\TranslationManager\TranslationManagerPlugin;

/**
 * The AdminPanelProvider class is responsible for configuring the main Filament administration panel for the application, specifically for the "VL. woordenboek" (Flemish Dictionary) project.
 * This provider sets up all essential aspects of the Filament dashboard, including its appearance, authentication, middleware, and integrated plugins.
 *
 * This class serves as the central point for defining the administrative interface, ensuring a consistent and robust backend experience for content managers, editors, and developers.
 * It integrates various third-party Filament plugins to extend functionality such as resource locking, global search, and a customizable footer.
 *
 * @package App\Providers\Filament
 */
final class AdminPanelProvider extends PanelProvider
{
    /**
     * Configures the Filament administration panel.
     *
     * This method defines the core settings and functionalities of the Filament panel.
     * It sets up the panel's unique identifier, URL path, brand name, and favicon for visual identity.
     * It marks this as the default Filament panel and assigns it the unique ID 'admin', setting its URL prefix to `/admin`.
     * The brand name "VL. woordenboek" will be displayed in the admin header, and a custom favicon is configured.
     * The content area is set to span the full width, and a top-level navigation bar is enabled. A custom font, "Tilt Neon," is applied throughout the panel.
     *
     * The panel includes Filament's built-in login and password reset functionalities, along with database-driven notifications.
     * A custom color palette is defined for various UI elements using Filament's `Color` enum.
     * The user dropdown in the top-right corner features custom menu items: "Verlaat beheersconsole" (Leave admin console), which redirects to the application's home page, and "Account instellingen" (Account settings), which redirects to the user's profile settings page.
     *
     * Filament resources, pages, clusters, and widgets are automatically registered by discovering them within specified directories.
     * A stack of HTTP middleware is registered to apply to all requests within this panel.
     * This includes standard Laravel middleware for cookie handling, session management, CSRF protection, and binding route parameters, as well as Filament-specific middleware.
     * A global middleware, `ForbidBannedUser::class`, is also registered to restrict access for banned users, and the authentication middleware for the panel is explicitly specified.
     *
     * The configuration integrates several third-party Filament plugins to extend functionality.
     * The `ResourceLockPlugin` prevents multiple users from concurrently editing the same resource.
     * The `GlobalSearchModalPlugin` (SpotlightPlugin) adds a global search modal with custom styling, with tree and expanded URL features disabled.
     * The `EasyFooterPlugin` provides a customizable footer that includes a GitHub link, displays the page load time, and allows for custom links such as "Voorwaarden."
     * The `FilamentDeveloperLoginsPlugin` enables quick login for predefined developer accounts, but it is only active when `APP_DEBUG` is true in the environment.
     *
     * @param  Panel $panel  The Filament Panel instance to configure.
     * @return Panel         The configured Filament Panel instance.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('VL. woordenboek')
            ->favicon(asset('favicon/favicon-32x32.png'))
            ->maxContentWidth(Width::Full)
            ->topNavigation()
            ->font("Tilt Neon")
            ->login()
            ->passwordReset()
            ->databaseNotifications()
            ->userMenuItems([
                MenuItem::make()
                    ->label('Verlaat beheersconsole')
                    ->url(fn(): string => route('home'))
                    ->icon('heroicon-o-arrow-left-start-on-rectangle'),
                MenuItem::make()
                    ->label('Account instellingen')
                    ->url(fn(): string => route('profile.settings'))
                    ->icon('heroicon-o-adjustments-horizontal'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                TranslationManagerPlugin::make(),
                FilamentShieldPlugin::make(),
                ResourceLockPlugin::make(),
                GlobalSearchModalPlugin::make()
                ->searchItemTree(false)
                ->expandedUrlTarget(enabled: false)
                ->highlightQueryStyles([
                    'background-color' => 'yellow',
                    'font-weight' => 'bold',
                ]),
                EasyFooterPlugin::make()
                    ->withGithub()
                    ->withLoadTime()
                    ->withLinks([
                        ['title' => 'Voorwaarden', 'url' => url('voorwaarden')],
                    ]),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(Config::boolean('app.debug', false))
                    ->users($this->defaultLoginsDuringDevelopment()),
            ])
            ->middleware([ForbidBannedUser::class])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * Defines the default developer login accounts available during development.
     *
     * This private helper method provides a list of email addresses for various user roles that can be quickly logged into when the `FilamentDeveloperLoginsPlugin` is enabled (which typically occurs when `APP_DEBUG` is true).
     * This significantly streamlines the development and testing process by allowing developers to switch between different user roles without manually logging in or managing credentials.
     *
     * @return array<string, string> An associative array where keys are human-readable role names (e.g., 'Redacteur') and values are the corresponding email addresses.
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
