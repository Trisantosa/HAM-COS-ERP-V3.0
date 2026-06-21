<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    protected int $timeout = 3600; // 1 hour

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $now = now()->timestamp;

            if ($lastActivity && ($now - $lastActivity) > $this->timeout) {
                Auth::logout();
                session()->flush();

                return response()->json([
                    'success' => false,
                    'message' => 'Session Anda telah berakhir',
                    'error_code' => 'SESSION_TIMEOUT',
                ], 401);
            }

            session(['last_activity' => $now]);
        }

        return $next($request);
    }
}
