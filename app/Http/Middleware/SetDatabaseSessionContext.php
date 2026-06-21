<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseSessionContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Store session context in database
            session([
                'session_context' => [
                    'user_id' => $user->id,
                    'user_role' => $user->roles->pluck('name')->first(),
                    'division_id' => $user->employee?->division_id,
                    'department' => $user->employee?->division?->name,
                    'is_superuser' => $user->hasRole('SUPERADMIN'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'started_at' => now(),
                ],
            ]);
        }

        return $next($request);
    }
}
