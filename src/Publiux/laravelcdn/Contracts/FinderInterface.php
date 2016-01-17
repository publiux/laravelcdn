<?php
namespace Publiux\laravelcdn\Contracts;

/**
 * Interface FinderInterface
 * @package  Vinelab\Cdn\Contracts
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface FinderInterface
{

    public function read(AssetInterface $paths);

}
