<?php
// FILE: app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL; // <-- 1. Add this line here
use Illuminate\Support\Facades\View;
use App\Models\CmsSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Add this block to force secure HTTPS links on Render
        if (config('app.env') === 'production' || app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Use our custom pagination view
        Paginator::defaultView('vendor.pagination.simple-default');
        Paginator::defaultSimpleView('vendor.pagination.simple-default');

        // Share CMS settings with the footer on every page
        View::composer('layouts.app', function ($view) {
            if (!array_key_exists('settings', $view->getData())) {
                $view->with('settings', CmsSetting::pluck('value', 'key'));
            }
        });
    }
}
