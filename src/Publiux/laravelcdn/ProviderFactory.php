<?php

namespace Publiux\laravelcdn;

use Illuminate\Support\Facades\App;
use Publiux\laravelcdn\Contracts\ProviderFactoryInterface;
use Publiux\laravelcdn\Exceptions\MissingConfigurationException;
use Publiux\laravelcdn\Exceptions\UnsupportedProviderException;

/**
 * Class ProviderFactory
 * This class is responsible of creating objects from the default
 * provider found in the config file.
 *
 * @category Factory
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ProviderFactory implements ProviderFactoryInterface
{
    const DRIVERS_NAMESPACE = 'Publiux\\laravelcdn\\Providers\\';

    /**
     * Create and return an instance of the corresponding
     * Provider concrete according to the configuration.
     *
     * @param array $configurations
     *
     * @throws \Publiux\laravelcdn\UnsupportedDriverException
     *
     * @return mixed
     */
    public function create($configurations = [])
    {
        // get the default provider name
        $provider = isset($configurations['default']) ? $configurations['default'] : null;

        if (!$provider) {
            throw new MissingConfigurationException('Missing Configurations: Default Provider');
        }

        // prepare the full driver class name
        $driver_class = self::DRIVERS_NAMESPACE.ucwords($provider).'Provider';

        if (!class_exists($driver_class)) {
            throw new UnsupportedProviderException("CDN provider ($provider) is not supported");
        }

        // initialize the driver object and initialize it with the configurations
        $driver_object = App::make($driver_class)->init($configurations);

        return $driver_object;
    }
}
