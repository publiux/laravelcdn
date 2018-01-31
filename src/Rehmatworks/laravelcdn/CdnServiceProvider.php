<?php

namespace Rehmatworks\laravelcdn;

use Illuminate\Support\ServiceProvider;

/**
 * Class CdnServiceProvider.
 *
 * @category Service Provider
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 * @author  Abed Halawi <abed.halawi@vinelab.com>
 */
class CdnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/cdn.php' => config_path('cdn.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        // implementation bindings:
        //-------------------------
        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\CdnInterface',
            'Rehmatworks\laravelcdn\Cdn'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Providers\Contracts\ProviderInterface',
            'Rehmatworks\laravelcdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\AssetInterface',
            'Rehmatworks\laravelcdn\Asset'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\FinderInterface',
            'Rehmatworks\laravelcdn\Finder'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\ProviderFactoryInterface',
            'Rehmatworks\laravelcdn\ProviderFactory'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\CdnFacadeInterface',
            'Rehmatworks\laravelcdn\CdnFacade'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Contracts\CdnHelperInterface',
            'Rehmatworks\laravelcdn\CdnHelper'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Validators\Contracts\ProviderValidatorInterface',
            'Rehmatworks\laravelcdn\Validators\ProviderValidator'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Validators\Contracts\CdnFacadeValidatorInterface',
            'Rehmatworks\laravelcdn\Validators\CdnFacadeValidator'
        );

        $this->app->bind(
            'Rehmatworks\laravelcdn\Validators\Contracts\ValidatorInterface',
            'Rehmatworks\laravelcdn\Validators\Validator'
        );

        // register the commands:
        //-----------------------
        $this->app->singleton('cdn.push', function ($app) {
            return $app->make('Rehmatworks\laravelcdn\Commands\PushCommand');
        });

        $this->commands('cdn.push');

        $this->app->singleton('cdn.empty', function ($app) {
            return $app->make('Rehmatworks\laravelcdn\Commands\EmptyCommand');
        });

        $this->commands('cdn.empty');

        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app->singleton('CDN', function ($app) {
            return $app->make('Rehmatworks\laravelcdn\CdnFacade');
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
//        $this->app->booting(function () {
//            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
//            $loader->alias('Cdn', 'Rehmatworks\laravelcdn\Facades\CdnFacadeAccessor');
//        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
