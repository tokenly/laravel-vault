<?php

namespace Tokenly\Vault\Services;

use Jippi\Vault\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Raw.
 */
class Raw
{
    /**
     * Client instance
     *
     * @var Client
     */
    private $client;

    /**
     * Create a new Data service with an optional Client
     *
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function raw($method, $url, array $params = [])
    {
        return $this->client->{$method}($url, $params);
    }
}
