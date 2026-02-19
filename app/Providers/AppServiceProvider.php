<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
// app/Providers/AppServiceProvider.php
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Microsoft\Provider as MicrosoftProvider;





class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
          // Rediriger vers la home après TOUT logout Filament (admin + utilisateur)
    $this->app->bind(LogoutResponseContract::class, function () {
        return new class implements LogoutResponseContract {
            public function toResponse($request)
            {
                return redirect('/');
            }
        };
    });
    }

    // public function boot(): void
    // {
    //     Filament::serving(function () {
    //         // Appliquer le style uniquement sur les pages de login des deux panels
    //         if (request()->is('admin/login') || request()->is('utilisateurs/login')) {
    //             FilamentAsset::register([
    //                 // ✅ on passe l'URL directement en 2e argument de make()
    //                 Css::make('login-bg', asset('css/login-bg.css')),
    //             ], 'app');
    //         }
    //     });

    // }
    public function boot(): void
{
    Filament::serving(function () {
        // 1) Injecter le favicon sur TOUTES les pages Filament (head)
//         FilamentView::registerRenderHook(
//     PanelsRenderHook::HEAD_START,
//     fn (): string => '<link rel="icon" type="image/png" href="' . asset('jesa-logo.png?v=1') . '">'
// );

        // 2) Appliquer le style uniquement sur les pages de login des deux panels
        if (request()->is('admin/login') || request()->is('utilisateurs/login')) {
            FilamentAsset::register([
                // on passe l'URL directement en 2e argument de make()
                Css::make('login-bg', asset('css/login-bg.css')),
            ], 'app');
        }
    });

    Event::listen(function (SocialiteWasCalled $event) {
        $event->extendSocialite('microsoft', MicrosoftProvider::class);
    });
}

}

