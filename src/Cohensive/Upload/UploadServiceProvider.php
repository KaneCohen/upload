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
            $validator = new Validator();
            $sanitizer = new LaravelStrSanitizer();
            $fileFactory = new FileHandlerFactory();

            return new Factory($validator, $sanitizer, $fileFactory);
        });
    }

    public function provides()
    {
        return ['upload'];
    }
}