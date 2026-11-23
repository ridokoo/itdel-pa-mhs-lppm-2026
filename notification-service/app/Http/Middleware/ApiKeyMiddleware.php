<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // app/Http/Middleware/ApiKeyMiddleware.php
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-API-Key');

        if ($key !== config('app.internal_api_key', env('INTERNAL_API_KEY'))) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}

