<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Actions;

 // Ajout du texte sous "Se souvenir de moi"
                        

class CustomLogin extends BaseLogin
{
    // Adapte ce chemin si tu as placé le Blade ailleurs :
    // - resources/views/filament/pages/custom-login.blade.php  -> 'filament.pages.custom-login'
    // - resources/views/filament/auth/custom-login.blade.php   -> 'filament.auth.custom-login'
   // protected static string $view = 'filament.pages.custom-login';  //il envoit vers la page view pour l'image ....

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        
                         
                    ])
                    ->statePath('data')
            ),
        ];
    }

    public function getRetourUrl(): string
    {
        return url('/');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            Actions\Action::make('Back')
                ->label('Back')
                ->url($this->getRetourUrl())
                ->color('gray')
                ->extraAttributes(['class' => 'text-sm']),
        ];
    }

 

} 
