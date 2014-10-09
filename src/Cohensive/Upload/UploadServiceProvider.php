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
        $this->package('cohensive/upload');
        $this->app->bindShared('upload', function($app) {
            $options = $app['config']->get('upload::options');
            return new LaraveFactory($options);
        });
    }

    public function provides()
    {
        return ['upload'];
    }
}
