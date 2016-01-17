<?php

namespace Publiux\laravelcdn\Contracts;

/**
 * Interface CdnFacadeInterface.
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface CdnFacadeInterface
{
    public function asset($path);
}
