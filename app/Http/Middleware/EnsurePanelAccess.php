<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class EnsurePanelAccess
// {
//     /**
//      * @param  string  $requiredPanel   'admin' or 'utilisateur'
//      * @param  string  $loginRouteName  e.g. 'filament.admin.auth.login' or 'filament.utilisateur.auth.login'
//      */
//     public function handle(Request $request, Closure $next, string $requiredPanel, string $loginRouteName)
//     {
//         // Not logged in? Let Filament handle redirection to login.
//         if (! Auth::check()) {
//             return $next($request);
//         }

//         $user = Auth::user();

//         // Try to read role name from relation or column.
//         // If you have roles table => $user->role->name
//         // If you store role directly on users table => $user->role
//         $roleName = $user->role->name ?? $user->role ?? null;
//         $roleName = is_string($roleName) ? strtolower($roleName) : null;

//         // Also accept a boolean column if you have it (optional)
//         $isAdminFlag = isset($user->is_admin) ? (bool) $user->is_admin : null;

//         $isAdmin = ($roleName === 'admin') || ($isAdminFlag === true);

//         // Access rules
//         if ($requiredPanel === 'admin' && ! $isAdmin) {
//             Auth::logout();

//             return redirect()->route($loginRouteName)
//                 ->with('auth_error', 'No admin account found for this Google email.');
//         }

//         if ($requiredPanel === 'utilisateur' && $isAdmin) {
//             Auth::logout();

//             return redirect()->route($loginRouteName)
//                 ->with('auth_error', 'No user account found for this Google email.');
//         }

//         return $next($request);
//     }
// }
