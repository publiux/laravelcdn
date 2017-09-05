<?php

namespace Publiux\laravelcdn;

use Illuminate\Support\Facades\Request;
use Publiux\laravelcdn\Contracts\CdnFacadeInterface;
use Publiux\laravelcdn\Contracts\CdnHelperInterface;
use Publiux\laravelcdn\Contracts\ProviderFactoryInterface;
use Publiux\laravelcdn\Exceptions\EmptyPathException;
use Publiux\laravelcdn\Validators\CdnFacadeValidator;

/**
 * Class CdnFacade.
 *
 * @category
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class CdnFacade implements CdnFacadeInterface
{
    /**
     * @var array
     */
    protected $configurations;

    /**
     * @var \Publiux\laravelcdn\Contracts\ProviderFactoryInterface
     */
    protected $provider_factory;

    /**
     * instance of the default provider object.
     *
     * @var \Publiux\laravelcdn\Providers\Contracts\ProviderInterface
     */
    protected $provider;

    /**
     * @var \Publiux\laravelcdn\Contracts\CdnHelperInterface
     */
    protected $helper;

    /**
     * @var \Publiux\laravelcdn\Validators\CdnFacadeValidator
     */
    protected $cdn_facade_validator;

    /**
     * Calls the provider initializer.
     *
     * @param \Publiux\laravelcdn\Contracts\ProviderFactoryInterface $provider_factory
     * @param \Publiux\laravelcdn\Contracts\CdnHelperInterface       $helper
     * @param \Publiux\laravelcdn\Validators\CdnFacadeValidator      $cdn_facade_validator
     */
    public function __construct(
        ProviderFactoryInterface $provider_factory,
        CdnHelperInterface $helper,
        CdnFacadeValidator $cdn_facade_validator
    ) {
        $this->provider_factory = $provider_factory;
        $this->helper = $helper;
        $this->cdn_facade_validator = $cdn_facade_validator;

        $this->init();
    }

    /**
     * Read the configuration file and pass it to the provider factory
     * to return an object of the default provider specified in the
     * config file.
     */
    private function init()
    {
        // return the configurations from the config file
        $this->configurations = $this->helper->getConfigurations();

        // return an instance of the corresponding Provider concrete according to the configuration
        $this->provider = $this->provider_factory->create($this->configurations);
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::asset('')}} to convert the path into
     * it's CDN url.
     *
     * @param $path
     *
     * @throws Exceptions\EmptyPathException
     *
     * @return mixed
     */
    public function asset($path)
    {
        // if asset always append the public/ dir to the path (since the user should not add public/ to asset)
        return $this->generateUrl($path, 'public/');
    }

    /**
     * check if package is surpassed or not then
     * prepare the path before generating the url.
     *
     * @param        $path
     * @param string $prepend
     *
     * @return mixed
     */
    private function generateUrl($path, $prepend = '')
    {
        // if the package is surpassed, then return the same $path
        // to load the asset from the localhost
        if (isset($this->configurations['bypass']) && $this->configurations['bypass']) {
            return Request::root().'/'.$path;
        }

        if (!isset($path)) {
            throw new EmptyPathException('Path does not exist.');
        }

        // Add version number
        //$path = str_replace(
        //    "build",
        //    $this->configurations['providers']['aws']['s3']['version'],
        //    $path
        //);

        // remove slashes from begging and ending of the path
        // and append directories if needed
        $clean_path = $prepend.$this->helper->cleanPath($path);

        // call the provider specific url generator
        return $this->provider->urlGenerator($clean_path);
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::mix('')}} to convert the Laravel 5.4 webpack mix
     * generated file path into it's CDN url.
     *
     * @param $path
     *
     * @return mixed
     *
     * @throws Exceptions\EmptyPathException, \InvalidArgumentException
     */
    public function mix($path)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('mix-manifest.json')), true);
        }
        if (isset($manifest['/' . $path])) {
            return $this->generateUrl($manifest['/' . $path], 'public/');
        }
        if (isset($manifest[$path])) {
            return $this->generateUrl($manifest[$path], 'public/');
        }
        throw new \InvalidArgumentException("File {$path} not defined in asset manifest.");
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::elixir('')}} to convert the elixir generated file path into
     * it's CDN url.
     *
     * @param $path
     *
     * @throws Exceptions\EmptyPathException, \InvalidArgumentException
     *
     * @return mixed
     */
    public function elixir($path)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('build/rev-manifest.json')), true);
        }
        if (isset($manifest[$path])) {
            return $this->generateUrl('build/' . $manifest[$path], 'public/');
        }
        throw new \InvalidArgumentException("File {$path} not defined in asset manifest.");
    }

    /**
     * this function will be called from the 'views' using the
     * 'Cdn' facade {{Cdn::path('')}} to convert the path into
     * it's CDN url.
     *
     * @param $path
     *
     * @throws Exceptions\EmptyPathException
     *
     * @return mixed
     */
    public function path($path)
    {
        return $this->generateUrl($path);
    }
}
