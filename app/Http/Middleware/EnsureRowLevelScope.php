<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRowLevelScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store row-level scope constraints
        app()->bind('row_level_scope', function () {
            $context = session('session_context');
            
            return [
                'user_id' => $context['user_id'] ?? null,
                'division_id' => $context['division_id'] ?? null,
                'is_superuser' => $context['is_superuser'] ?? false,
            ];
        });

        return $next($request);
    }
}
