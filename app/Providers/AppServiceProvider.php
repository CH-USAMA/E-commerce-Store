<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // helpers.php is also listed in composer.json's autoload.files, but that list is
        // baked into vendor/composer/autoload_files.php and only refreshed by
        // `composer dump-autoload`. vendor/ is gitignored, so on a host where we cannot
        // get a shell a plain `git pull` would leave image_url() undefined and fatal on
        // every view that renders an image. Requiring it here needs no autoloader
        // regeneration — this provider is already loaded. Each function in the file is
        // guarded by function_exists(), so loading it twice is harmless.
        require_once __DIR__ . '/../helpers.php';
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();

        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share settings globally with all views
        if (!app()->runningInConsole()) {
            view()->share('settings', \App\Models\Setting::all()->pluck('value', 'key'));
        }
    }
}
