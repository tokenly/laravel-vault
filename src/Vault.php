<?php

namespace Tokenly\Vault;

use Jippi\Vault\Client;
use Jippi\Vault\ServiceFactory;

/**
 * Class Vault.
 */
class Vault
{
    /** @var array */
    protected $options;

    /**
     * Vault constructor.
     *
     * @param string $address Vault service address like https://127.0.0.1:8200
     * @param string $certificate Path to a certificate authority certificate
     */
    public function __construct($address, $certificate = null)
    {
        $this->options = [
            'base_uri' => $address,
        ];

        if ($certificate) {
            $this->options['verify'] = $certificate;
        }
    }

    /**
     * @param string $token
     *
     * @return \Tokenly\Vault\Vault
     */
    public function setToken($token)
    {
        $this->options = array_merge_recursive(
            $this->options,
            [
                'headers' => [
                    'X-Vault-Token' => $token
                ]
            ]
        );

        return $this;
    }

    /**
     * Calls Vault and returns a result like
     * [
     *   "success" => true,
     *   "code" => 200,
     *   "data" => [
     *     "initialized" => true,
     *   ],
     *   "raw" => "{"initialized":true}\n",
     *   "error" => null,
     * ]
     *
     * @return \Tokenly\Vault\VaultWrapper the response data
     */
    public function sys()
    {
        return new VaultWrapper($this->getFactory()->get('sys'));
    }

    /**
     * Calls Vault and returns a result like
     * [
     *   "success" => true,
     *   "code" => 200,
     *   "data" => [
     *     "initialized" => true,
     *   ],
     *   "raw" => "{"initialized":true}\n",
     *   "error" => null,
     * ]
     *
     * @return \Tokenly\Vault\VaultWrapper the response data
     */
    public function data()
    {
        return new VaultWrapper($this->getFactory()->get('data'));
    }

    /**
     * Calls Vault and returns a result like
     * [
     *   "success" => true,
     *   "code" => 200,
     *   "data" => [
     *     "initialized" => true,
     *   ],
     *   "raw" => "{"initialized":true}\n",
     *   "error" => null,
     * ]
     *
     * @return \Tokenly\Vault\VaultWrapper the response data
     */
    public function authToken()
    {
        return new VaultWrapper($this->getFactory()->get('auth/token'));
    }

    /**
     * Raw vault call with a path.
     *
     * @param  string $method get, put, post, delete
     * @param  string $url URL path like /v1/sys/init
     * @param  array $params params like ['body' => json_encode(['foo' => bar])]
     *
     * @return array the response data
     */
    public function raw($method, $url, $params = [])
    {
        return (new VaultWrapper(null))
            ->raw($this->getClient(), $method, $url, $params);
    }

    /**
     * @return ServiceFactory
     */
    protected function getFactory()
    {
        return new ServiceFactory($this->options);
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return new Client($this->options);
    }
}
