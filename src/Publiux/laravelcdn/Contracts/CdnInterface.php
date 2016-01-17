<?php

namespace Publiux\laravelcdn\Contracts;

/**
 * Interface CdnInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface CdnInterface
{
    public function push();

    public function emptyBucket();
}
