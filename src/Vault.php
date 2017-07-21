<?php

namespace Tokenly\Vault;

use Exception;
use Illuminate\Support\Facades\Log;
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
     * Vault constructor.
     *
     */
    /**
     * Vault constructor
     * @param string $address      Vault service address like https://127.0.0.1:8200
     * @param string $ca_cert_path Path to a certificate authority certificate
     */
    public function __construct($address, $ca_cert_path=null)
    {
        $this->options = [
            'base_uri' => $address,
        ];

        if ($ca_cert_path !== null) {
            $this->options['verify'] = $ca_cert_path;
        }
    }

    public function setToken($token) {
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
     * @return [type] [description]
     */
    public function sys() {
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
     * @return [type] [description]
     */
    public function data() {
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
     * @return [type] [description]
     */
    public function authToken() {
        return new VaultWrapper($this->getFactory('auth/token'));
    }

    // ------------------------------------------------------------------------
    
    protected function getFactory($path) {
        $sf = new ServiceFactory($this->options);
        return $sf->get($path);
    }

}
