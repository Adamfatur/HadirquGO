<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan user telah login
        if (!Auth::check()) {
            Log::warning('User not authenticated');
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        // Log untuk memastikan role user
        Log::info('Checking user role in middleware', [
            'user_id' => $user->id,
            'expected_role' => $role,
            'user_roles' => $user->roles->pluck('name')->toArray(),
        ]);

        // Cek apakah user memiliki role yang sesuai
        if ($user->hasRole($role)) {
            Log::info('User has correct role', ['user_id' => $user->id, 'role' => $role]);
            return $next($request);
        }

        Log::warning('User does not have access to role', [
            'user_id' => $user->id,
            'required_role' => $role,
            'user_roles' => $user->roles->pluck('name')->toArray(),
        ]);

        // Redirect jika user tidak memiliki role yang sesuai
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
