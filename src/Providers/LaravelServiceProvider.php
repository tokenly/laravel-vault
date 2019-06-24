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
 * Class LaravelServiceProvider.
 */
class LaravelServiceProvider extends AbstractServiceProvider
{
    /** @inheritdoc */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/vault.php');

        $this->publishes([$path => config_path('vault.php')], 'vault');
        $this->mergeConfigFrom($path, 'vault');
    }
}
