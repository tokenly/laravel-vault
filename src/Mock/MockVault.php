<?php

namespace Tokenly\Vault\Mock;

use Exception;
use Tokenly\Vault\Mock\MockVaultDataWrapper;
use Tokenly\Vault\Vault;

class MockVault extends Vault
{
    static $STORE;

    public function __construct()
    {
        $address = 'https://127.0.0.1:8200';
        parent::__construct($address, $_ca_cert_path=null);
    }

    public function data() {
        return new MockVaultDataWrapper();
    }

    public function sys() {
        throw new Exception("Mock sys not implemented", 1);
    }


}