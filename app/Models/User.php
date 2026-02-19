<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens; // Make sure this line is present
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable; // Make sure HasApiTokens is included here

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if($this->role === null) {
            return false; // No role assigned, deny access
        }
        // Check if the user has the required role for the panel
        if ($panel->getId() === 'utilisateur') {
            return $this->role->name === RoleEnum::UTILISATEUR->value;
        }
        // Check if the user has the required role for the admin panel
        if ($panel->getId() === 'admin') { 
            return $this->role->name === RoleEnum::ADMIN->value;
        }
        // If the panel is not specifically handled, deny access
        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === RoleEnum::ADMIN->value;
    }
}


// namespace App\Models;

// use App\Enums\RoleEnum;
// use Filament\Models\Contracts\FilamentUser;
// use Filament\Panel;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable implements FilamentUser
// {
//     use HasApiTokens, Notifiable;

//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//         'role_id',
//     ];

//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     protected $casts = [
//         'email_verified_at' => 'datetime',
//     ];

//     // ⚠️ précharge toujours le rôle (évite les nulls inattendus)
//     protected $with = ['role'];

//     public function role(): BelongsTo
//     {
//         return $this->belongsTo(Role::class);
//     }

//     /** Comparaison robuste, insensible à la casse */
//     public function hasRole(string $roleName): bool
//     {
//         $current = $this->role?->name ?? '';
//         return strcasecmp($current, $roleName) === 0;
//     }

//     /** Filament : contrôle d’accès par panel */
//     public function canAccessPanel(Panel $panel): bool
//     {
//         // si pas de rôle, on refuse
//         if (! $this->role?->name) {
//             return false;
//         }

//         switch ($panel->getId()) {
//             case 'utilisateur':
//                 return $this->hasRole(RoleEnum::UTILISATEUR->value) || $this->hasRole('utilisateur') || $this->hasRole('user');
//             case 'admin':
//                 return $this->hasRole(RoleEnum::ADMIN->value) || $this->hasRole('admin');
//             default:
//                 return false;
//         }
//     }

//     public function isAdmin(): bool
//     {
//         return $this->hasRole(RoleEnum::ADMIN->value) || $this->hasRole('admin');
//     }
// }
