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

use Illuminate\Support\ServiceProvider;
use Tokenly\Vault\Vault;

/**
 * Class AbstractServiceProvider.
 */
abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    abstract public function boot();

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();
        $this->registerVault();
    }

    /**
     * Bind some aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $this->app->alias('tokenly.vault', Vault::class);
    }

    /**
     * Register the Artisan command.
     *
     * @return void
     */
    protected function registerVault()
    {
        $this->app->singleton('tokenly.vault', function ($app) {
            return new Vault(
                $this->config('address'),
                $this->config('certificate')
            );
        });
    }

    /**
     * Helper to get the config values.
     *
     * @param  string  $key
     * @param  string  $default
     *
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return config("vault.$key", $default);
    }

    /**
     * Get an instantiable configuration instance.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    protected function getConfigInstance($key)
    {
        $instance = $this->config($key);

        if (is_string($instance)) {
            return $this->app->make($instance);
        }

        return $instance;
    }
}
