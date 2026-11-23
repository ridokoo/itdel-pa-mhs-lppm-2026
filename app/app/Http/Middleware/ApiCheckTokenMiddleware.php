<?php

namespace App\Http\Middleware;

use App\Http\Api\UserApi;
use App\Models\HakAksesModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil token dari header Authorization
        $authToken = $request->bearerToken();

        if (empty($authToken)) {
            // Redirect to login page if auth token is not set
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access, token is missing',
            ], 403);
        }

        $response = UserApi::getMe($authToken);

        if ($response->status != 'success') {
            // If the token is invalid, redirect to login page
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access, token is invalid',
            ], 403);
        }

        $user = $response->data->user;

        $akses = HakAksesModel::where('user_id', $user->id)->first();

        $user->akses = $akses
            ? explode(',', $akses->akses)
            : [];

        $request->attributes->set('auth', $user);

        return $next($request);
    }
}



