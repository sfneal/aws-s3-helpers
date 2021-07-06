<?php


namespace Sfneal\Helpers\Aws\S3\Providers;


use Illuminate\Support\ServiceProvider;

class S3HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any CrudModelAction services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__.'/../../config/s3-helpers.php' => config_path('s3-helpers.php'),
        ], 'config');
    }

    /**
     * Register any CrudModelAction services.
     *
     * @return void
     */
    public function register()
    {
        // Load config file
        $this->mergeConfigFrom(__DIR__.'/../../config/s3-helpers.php', 's3-helpers');
    }
}
