<?php

namespace Tokenly\Vault\Services;

use Jippi\Vault\Client;

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

    public function raw($method, $url, $params)
    {
        return $this->client->{$method}($url, $params);
    }
}
