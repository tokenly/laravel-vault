<?php

namespace Tokenly\Vault\Services;

use Error;
use Illuminate\Http\Response;
use Jippi\Vault\Client;
use Jippi\Vault\Exception\ClientException;

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
     * @param \Jippi\Vault\Client|null $client
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invoke($method, $url, array $params = [])
    {
        try {
            return $this->client->{$method}($url, $params);
        } catch (Error $exception) {
            throw new ClientException($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
