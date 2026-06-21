<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'error_code' => 'UNAUTHORIZED',
            ], 401);
        }

        $user = auth()->user();

        if (!$user->hasPermissionTo($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke modul ini',
                'error_code' => 'PERMISSION_DENIED',
            ], 403);
        }

        return $next($request);
    }
}
