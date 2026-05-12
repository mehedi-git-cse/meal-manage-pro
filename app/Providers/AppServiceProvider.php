<?php

namespace App\Providers;

use App\Repositories\BazarRepository;
use App\Repositories\MealRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MealRepository::class);
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(BazarRepository::class);
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
