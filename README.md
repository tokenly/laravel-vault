# Laravel vault

A Laravel interface for Hashicorp Vault.

This is a wrapper around [jippi/vault-php-sdk](https://github.com/jippi/vault-php-sdk).

## Installing

Require this package, with [Composer](https://getcomposer.org/), in the root directory of your project.

``` bash
$ composer require tokenly/laravel-vault
```

### Laravel 5.x:

After updating composer, add the ServiceProvider to the providers array in `config/app.php`

 ```php
'providers' => [
    ...
    Tokenly\Vault\Providers\LaravelServiceProvider::class,
],
```

### Lumen:

After updating composer add the following lines to register provider in `bootstrap/app.php`

```php
$app->register(Tokenly\Vault\Providers\LumenServiceProvider::class);
```

### Set the environment variables

```
VAULT_ADDRESS=https://127.0.0.1:8200
VAULT_CERTIFICATE=/path/to/ca.cert
```

## Usage

```php
<?php

namespace App\Http\Controllers;

use Tokenly\Vault\Facades\Vault;

class ExampleController extends Controller
{
    protected $sealStatus;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sealStatus = Vault::sys()->sealStatus();
        
        $vault = Vault::setToken('1389b58b-0000-4800-a000-1d8869aee825');
        $vault->sys()->seal();
    }
}
```
