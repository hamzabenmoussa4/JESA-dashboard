<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Redirection vers Google (avec gestion d’état par session)
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            // Récupération de l’utilisateur Google via la session
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }

        // (Optionnel) Restreindre au domaine jesa.com
        // if (! str_ends_with($googleUser->getEmail(), '@jesa.com')) {
        //     return redirect('/')->with('error', 'Only @jesa.com accounts are allowed.');
        // }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
                'password'          => bcrypt(Str::random(40)),  // placeholder
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, true);

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return redirect()->route('filament.utilisateur.pages.dashboard');
    }
}
