<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Site;
use App\Observers\SiteObserver;
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
        Site::observe(SiteObserver::class);
    }
}
