<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder; // tambah ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DB::statement("SET TIMEZONE='Asia/Jakarta'");

        if (app()->environment('remote') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        RateLimiter::for('req-limit', function ($request) {
            return Limit::perMinutes(5, 300)
                ->by($request->auth?->id ?: $request->ip())
                ->response(function ($request, array $headers) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam 5 menit.',
                        'retry_after' => $headers['Retry-After'] ?? null,
                    ], 429);
                });
        });

        // Macro whereLike - works di MySQL & PostgreSQL
        Builder::macro('whereLike', function ($column, $value) {
            return $this->whereRaw('LOWER(' . $column . ') LIKE ?', [strtolower($value)]);
        });

        Builder::macro('orWhereLike', function ($column, $value) {
            return $this->orWhereRaw('LOWER(' . $column . ') LIKE ?', [strtolower($value)]);
        });
    }
}


