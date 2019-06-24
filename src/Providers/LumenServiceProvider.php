<?php

/*
 * This file is part of tokenly-vault.
 *
 * (c) Aleksandr Efimov <sanches.com@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tokenly\Vault\Providers;

/**
 * Class LumenServiceProvider.
 */
class LumenServiceProvider extends AbstractServiceProvider
{
    /** {@inheritdoc} */
    public function boot()
    {
        $this->app->configure('vault');

        $path = realpath(__DIR__.'/../../config/vault.php');
        $this->mergeConfigFrom($path, 'vault');
    }
}
