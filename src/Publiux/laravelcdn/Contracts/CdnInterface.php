<?php
namespace Publiux\laravelcdn\Contracts;

/**
 * Interface CdnInterface
 * @package  Vinelab\Cdn\Contracts
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface CdnInterface
{

    public function push();

    public function emptyBucket();


} 
