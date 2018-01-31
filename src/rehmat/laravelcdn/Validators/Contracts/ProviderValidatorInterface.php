<?php

namespace Rehmatworks\laravelcdn\Validators\Contracts;

/**
 * Interface ProviderValidatorInterface.
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
interface ProviderValidatorInterface
{
    public function validate($configuration, $required);
}
