<?php
namespace Publiux\laravelcdn\Providers;

use Publiux\laravelcdn\Providers\Contracts\ProviderInterface;

/**
 * Class Provider
 *
 * @category Drivers Abstract Class
 * @package  Publiux\laravelcdn\Providers
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
abstract class Provider implements ProviderInterface
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Instance of the console object
     */
    public $console;

    abstract function upload($assets);
} 
