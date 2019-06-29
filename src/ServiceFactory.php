<?php

namespace Tokenly\Vault;

use Jippi\Vault\ServiceFactory as BaseServiceFactory;

class ServiceFactory extends BaseServiceFactory
{
    protected static $services = [
        'sys' => 'Jippi\Vault\Services\Sys',
        'data' => 'Jippi\Vault\Services\Data',
        'auth/token' => 'Jippi\Vault\Services\Auth\Token',
        'auth/approle'=>'Jippi\Vault\Services\Auth\AppRole',
        'raw' => 'Tokenly\Vault\Services\Raw',
    ];

    /**
     * @param $service
     * @return mixed
     */
    public function get($service)
    {
        if (!array_key_exists($service, self::$services)) {
            $servicesString = implode('", "', array_keys(self::$services));

            throw new \InvalidArgumentException(
                sprintf('The service "%s" is not available. Pick one among "%s".', $service, $servicesString)
            );
        }

        return new self::$services[$service]($this->client);
    }
}
