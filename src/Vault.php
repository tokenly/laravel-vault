<?php

namespace Tokenly\Vault;

/**
 * Class Vault.
 *
 * @method \Jippi\Vault\Services\Sys sys()
 * @method \Jippi\Vault\Services\Data data()
 * @method \Jippi\Vault\Services\Auth\Token authToken()
 * @method \Tokenly\Vault\Services\Raw raw()
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
     * @param string $method
     * @param array $args
     *
     * @return \Tokenly\Vault\Wrapper
     */
    public function __call($method, $args)
    {
        return new Wrapper($this->getFactory()->get($this->camel2dashed($method)));
    }

    /**
     * @param string $methodName
     *
     * @return string
     */
    protected function camel2dashed($methodName)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1/', $methodName));
    }

    /**
     * @return \Tokenly\Vault\ServiceFactory
     */
    protected function getFactory()
    {
        return new ServiceFactory($this->options);
    }
}
