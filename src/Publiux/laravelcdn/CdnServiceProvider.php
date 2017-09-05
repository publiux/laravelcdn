<?php

namespace Publiux\laravelcdn;

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
            'Publiux\laravelcdn\Contracts\CdnInterface',
            'Publiux\laravelcdn\Cdn'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Providers\Contracts\ProviderInterface',
            'Publiux\laravelcdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Contracts\AssetInterface',
            'Publiux\laravelcdn\Asset'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Contracts\FinderInterface',
            'Publiux\laravelcdn\Finder'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Contracts\ProviderFactoryInterface',
            'Publiux\laravelcdn\ProviderFactory'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Contracts\CdnFacadeInterface',
            'Publiux\laravelcdn\CdnFacade'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Contracts\CdnHelperInterface',
            'Publiux\laravelcdn\CdnHelper'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Validators\Contracts\ProviderValidatorInterface',
            'Publiux\laravelcdn\Validators\ProviderValidator'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Validators\Contracts\CdnFacadeValidatorInterface',
            'Publiux\laravelcdn\Validators\CdnFacadeValidator'
        );

        $this->app->bind(
            'Publiux\laravelcdn\Validators\Contracts\ValidatorInterface',
            'Publiux\laravelcdn\Validators\Validator'
        );

        // register the commands:
        //-----------------------
        $this->app->singleton('cdn.push', function ($app) {
            return $app->make('Publiux\laravelcdn\Commands\PushCommand');
        });

        $this->commands('cdn.push');

        $this->app->singleton('cdn.empty', function ($app) {
            return $app->make('Publiux\laravelcdn\Commands\EmptyCommand');
        });

        $this->commands('cdn.empty');

        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app->singleton('CDN', function ($app) {
            return $app->make('Publiux\laravelcdn\CdnFacade');
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
//        $this->app->booting(function () {
//            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
//            $loader->alias('Cdn', 'Publiux\laravelcdn\Facades\CdnFacadeAccessor');
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
