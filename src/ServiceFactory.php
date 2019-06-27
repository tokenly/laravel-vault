<?php

namespace Tokenly\Vault;

use Jippi\Vault\ServiceFactory as BaseServiceFactory;

class ServiceFactory extends BaseServiceFactory
{
    protected static $services = [
        'sys' => 'Jippi\Vault\Services\Sys',
        'data' => 'Jippi\Vault\Services\Data',
        'auth/token' => 'Jippi\Vault\Services\Auth\Token',
        'auth/approle' => 'Jippi\Vault\Services\Auth\AppRole',
        'raw' => 'Tokenly\Vault\Services\Raw',
    ];
}
