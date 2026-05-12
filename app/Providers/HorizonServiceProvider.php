<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Horizon is Linux-only (requires ext-pcntl).
 * This stub keeps the provider registered without crashing on Windows.
 * On a Linux production server, replace this with:
 *   php artisan horizon:install
 */
class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }
}
