<?php

namespace Tokenly\Vault\Mock;

use Illuminate\Support\Facades\Log;

class MockVaultDataWrapper
{
    static $STORE;

    public function __call($method, $args) {
        throw new Exception("Mock __call $method not implemented", 1);
    }

    public function raw(VaultClient $client, $method, $url, $params) {
        throw new Exception("Mock raw $method not implemented", 1);
    }

    public function write($path, $body)
    {
        // Log::debug("[MockVaultWrapper] writeToVault {$path}");
        self::$STORE[$path] = $body;

        return [
            'success' => true,
            'code'    => 204,
            'data'    => null,
            'error'   => null,
            'raw'     => null,
        ];
    }

    public function get($path)
    {
        // Log::debug("[MockVaultWrapper] readFromVault {$path}: ".(isset(self::$STORE[$path]) ? substr(json_encode(self::$STORE[$path]), 0, 20).'...' : null));
        if (isset(self::$STORE[$path])) {
            return [
                'success' => true,
                'code'    => 200,
                'data'    => ['data' => self::$STORE[$path]],
                'error'   => null,
                'raw'     => json_encode(self::$STORE[$path]),
            ];
        }
        return [
            'success' => false,
            'code'    => 404,
            'data'    => null,
            'error'   => 'not found',
            'raw'     => null,
        ];
    }


}