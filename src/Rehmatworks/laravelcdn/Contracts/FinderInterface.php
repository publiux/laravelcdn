<?php

namespace Rehmatworks\laravelcdn\Contracts;

/**
 * Interface FinderInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface FinderInterface
{
    public function read(AssetInterface $paths);
}
