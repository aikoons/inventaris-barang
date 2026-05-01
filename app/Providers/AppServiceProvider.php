<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS in production (Railway terminates SSL at reverse proxy level)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Use Bootstrap 5 pagination (project uses Bootstrap, not Tailwind)
        Paginator::useBootstrapFive();
    }
}
