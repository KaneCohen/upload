<?php

namespace Cohensive\Upload;

use Illuminate\Support\ServiceProvider;
use Cohensive\Upload\Sanitizer\LaravelStrSanitizer;

class UploadServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boots the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('upload.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'upload'
        );

        $this->app->singleton('upload', function($app) {
            return new LaravelFactory($app['config']['upload']);
        });

        $this->app->singleton(UploadFactoryInterface::class, function($app) {
            return new LaravelFactory($app['config']['upload']);
        });

        $this->app->singleton(LaravelFactory::class, function($app) {
            return new LaravelFactory($app['config']['upload']);
        });
    }

    public function provides()
    {
        return [UploadFactoryInterface::class, LaravelFactory::class, 'upload'];
    }
}
