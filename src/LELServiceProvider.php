
<?php

namespace Piccard\LEL;

use Illuminate\Support\ServiceProvider;

class LELServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        require_once __DIR__ . '/Http/routes.php';
        require_once __DIR__ . '/Http/helpers.php';

        // command: vendor:publish
        $this->publishes([
            __DIR__ . '/config/laravel-easy-log.php' => config_path('laravel-easy-log.php'),
            __DIR__ . '/resources/views' => base_path('resources/views/vendor/lel'),
            __DIR__ . '/public' => public_path('vendor/lel'),
            __DIR__ . '/fonts' => public_path('fonts/vendor/bootstrap-sass/bootstrap'),

        ], 'lel');

        // register a view file namespace
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'lel');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-easy-log.php', 'laravel-easy-log'
        );
    }

}

