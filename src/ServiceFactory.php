<?php

namespace Tokenly\Vault;

use GuzzleHttp\Client as GuzzleClient;
use Jippi\Vault\Client;
use Psr\Log\LoggerInterface;

class ServiceFactory
{
    protected static $services = [
        'sys' => 'Jippi\Vault\Services\Sys',
        'data' => 'Jippi\Vault\Services\Data',
        'auth/token' => 'Jippi\Vault\Services\Auth\Token',
        'auth/approle' => 'Jippi\Vault\Services\Auth\AppRole',
        'raw' => 'Tokenly\Vault\Services\Raw',
    ];

    protected $client;

    public function __construct(array $options = [], LoggerInterface $logger = null, GuzzleClient $guzzleClient = null)
    {
        $this->client = new Client($options, $logger, $guzzleClient);
    }

    /**
     * @param $service
     * @return mixed
     */
    public function get($service)
    {
        if (!array_key_exists($service, self::$services)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The service "%s" is not available. Pick one among "%s".',
                    $service,
                    implode('", "', array_keys(self::$services))
                )
            );
        }

        $class = self::$services[$service];

        return new $class($this->client);
    }
}
