<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class LogApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);

        // Log API access to database
        if (auth()->check()) {
            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                'action' => $request->getMethod(),
                'resource' => $request->path(),
                'ip_address' => $request->ip(),
                'status_code' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'request_data' => json_encode($request->except(['password', 'password_confirmation'])),
                'created_at' => now(),
            ]);
        }

        return $response;
    }
}
