<?php

/*
 * This file is part of tokenly-vault.
 *
 * (c) Aleksandr Efimov <sanches.com@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tokenly\Vault\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Vault.
 *
 * @method static \Tokenly\Vault\Vault setToken(string $token)
 * @method static \Tokenly\Vault\Wrapper sys()
 * @method static \Tokenly\Vault\Wrapper data()
 * @method static array raw(string $method, string $url, array $params = [])
 */
class Vault extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tokenly.vault';
    }
}
