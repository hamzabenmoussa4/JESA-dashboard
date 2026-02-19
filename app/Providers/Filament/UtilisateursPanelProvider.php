<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Utilisateurs\Resources\InterlocuteurResource;
use App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets\InterlocuteurBadges;
use App\Filament\Utilisateurs\Resources\UtilisateurResource\Widgets\InterlocuteurStats;
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
use App\Filament\Utilisateurs\Resources\Widgets\UserTopResponsiblesWidget;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;



class UtilisateursPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('utilisateur')
            ->path('utilisateurs')
//             ->plugin(
//     FilamentSocialitePlugin::make()
//         ->providers([
//             Provider::make('google')->label('Google'),
//             // (optionnel) Provider::make('microsoft')->label('Microsoft'),
//         ])
//         ->registration(false)
// )
         ->renderHook('panels::body.start', function () {
    $r = request();

    // Page de login Utilisateur
    if ($r->routeIs('filament.utilisateur.auth.login')) {
        return view('partials.intro-overlay', [
            'subtitle' => 'JESA CONNECT | USER LOGIN',
        ]);
    }

    // Dashboard Utilisateur
    if ($r->routeIs('filament.utilisateur.pages.dashboard')) {
        return view('partials.intro-overlay', [
            'subtitle' => 'JESA CONNECT | USER DASHBOARD',
        ]);
    }

    return ''; // rien sur les autres pages
})

             ->favicon(asset('images/jesa-logo.png'))
           // ->brandName('JESA CONNECT') // ← Ajoute cette ligne
->brandLogo(fn () => view('filament.custom-logo'))

            ->homeUrl(url('/')) 
            //->login(\App\Filament\Pages\Auth\CustomLogin::class)         
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Utilisateurs/Resources'), for: 'App\\Filament\\Utilisateurs\\Resources')
            ->discoverPages(in: app_path('Filament/Utilisateurs/Pages'), for: 'App\\Filament\\Utilisateurs\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Utilisateurs/Widgets'), for: 'App\\Filament\\Utilisateurs\\Widgets')
            ->widgets([
                InterlocuteurBadges::class,
                //EchangesParMoisChart::class,
                   UserTopResponsiblesWidget::class,
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
                    //\App\Http\Middleware\EnsurePanelAccess::class . ':utilisateur,filament.utilisateur.auth.login',

            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
