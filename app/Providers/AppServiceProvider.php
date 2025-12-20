<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

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
        Paginator::useBootstrap();
        foreach ((array) config('permessions_en', []) as $config_permession => $value) {
            Gate::define($config_permession, function ($auth) use ($config_permession) {
                return $auth->hasAccess($config_permession);
            });
        }
    }
}