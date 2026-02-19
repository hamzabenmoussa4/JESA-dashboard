<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Resources\AdminResource\Widgets\UserStatsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->favicon(asset('images/jesa-logo.png'))
//  ->plugin(
//     FilamentSocialitePlugin::make()
//         ->providers([
//             Provider::make('google')->label('Google'),
//             // (optionnel) Provider::make('microsoft')->label('Microsoft'),
//         ])
//         ->registration(false)
// )
         

            // ② Overlay d’intro (hamburger) sur login & dashboard admin
            ->renderHook('panels::body.start', function () {
                $r = request();

                if ($r->routeIs('filament.admin.auth.login')) {
                    return view('partials.intro-overlay', [
                        'subtitle' => 'JESA CONNECT | ADMIN LOGIN',
                    ]);
                }

                if ($r->routeIs('filament.admin.pages.dashboard')) {
                    return view('partials.intro-overlay', [
                        'subtitle' => 'JESA CONNECT | ADMIN DASHBOARD',
                    ]);
                }

                return '';
            })

            // Branding
            ->brandLogo(fn () => view('filament.custom-logo'))

            ->homeUrl(url('/'))
            ->login(CustomLogin::class)

            // Couleur principale du thème
            ->colors([
                'primary' => Color::Amber,
            ])

            // Découverte automatique
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                UserStatsWidget::class,
            ])

            // Middlewares
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
                   // \App\Http\Middleware\EnsurePanelAccess::class . ':admin,filament.admin.auth.login',

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
