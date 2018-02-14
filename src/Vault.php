<?php

namespace Tokenly\Vault;

use Jippi\Vault\Client as VaultClient;
use Jippi\Vault\ServiceFactory;
use Tokenly\Vault\VaultWrapper;

/**
 * Class Vault
 */
class Vault
{

    protected $vault_service_factory = null;
    protected $options = null;

    /**
     * Vault constructor
     * @param string $address      Vault service address like https://127.0.0.1:8200
     * @param string $ca_cert_path Path to a certificate authority certificate
     */
    public function __construct($address, $ca_cert_path = null)
    {
        $this->options = [
            'base_uri' => $address,
        ];

        if ($ca_cert_path !== null) {
            $this->options['verify'] = $ca_cert_path;
        }
    }

    public function setToken($token)
    {
        $this->options = array_merge_recursive(
            $this->options,
            ['headers' => ['X-Vault-Token' => $token]]
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
     * @return array          the response data
     */
    public function sys()
    {
        return new VaultWrapper($this->getFactory('sys'));
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
     * @return array          the response data
     */
    public function data()
    {
        return new VaultWrapper($this->getFactory('data'));
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
     * @return array          the response data
     */
    public function authToken()
    {
        return new VaultWrapper($this->getFactory('auth/token'));
    }

    /**
     * Raw vault call with a path
     * @param  string $method get, put, post, delete
     * @param  string $url    URL path like /v1/sys/init
     * @param  array  $params params like ['body' => json_encode(['foo' => bar])]
     * @return array          the response data
     */
    public function raw($method, $url, $params = [])
    {
        $client = new VaultClient($this->options);
        $wrapper = new VaultWrapper(null);
        return $wrapper->raw($client, $method, $url, $params);
    }

    // ------------------------------------------------------------------------

    protected function getFactory($path)
    {
        $sf = new ServiceFactory($this->options);
        return $sf->get($path);
    }

}
