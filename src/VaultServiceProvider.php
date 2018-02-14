<?php

namespace Tokenly\Vault;

use Illuminate\Support\ServiceProvider;
use Tokenly\Vault\Vault;

class VaultServiceProvider extends ServiceProvider
{

    public function register()
    {
        /**
         * for package configure
         */
        $configPath = __DIR__ . '/config/vault.php';
        $this->mergeConfigFrom($configPath, 'vault');
        $this->publishes([$configPath => config_path('vault.php')], 'vault');

        // bind classes
        $this->app->bind('vault', function ($app) {
            $config = $app['config']->get('vault');
            return new Vault($config['address'], $config['ca_cert_path']);
        });

        // bind implementation to the short name
        $this->app->bind(Vault::class, 'vault');
    }

}
