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
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('upload', function($app) {
            $options = $app['config']['upload.options'];
            if ( ! is_null($options)) $options = [];
            return new LaravelFactory($options);
        });
    }

    public function provides()
    {
        return ['upload'];
    }
}
