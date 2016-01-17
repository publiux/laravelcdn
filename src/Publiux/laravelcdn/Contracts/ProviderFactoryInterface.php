<?php
namespace Publiux\laravelcdn\Contracts;

/**
 * Interface ProviderFactoryInterface
 * @package  Vinelab\Cdn\Contracts
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderFactoryInterface
{

    public function create($configurations);

}
