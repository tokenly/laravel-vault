
# My Package

A Laravel interface for Hashicorp Vault.

This is a wrapper around https://github.com/jippi/vault-php-sdk.


# Installation

### Add the package via composer

```
composer require tokenly/laravel-vault
```

## Usage with Laravel

### Add the Service Provider

Add the following to the `providers` array in your application config:

```
Tokenly\Vault\VaultServiceProvider::class,
```

### Set the environment variables

```
VAULT_ADDR=https://127.0.0.1:8200
VAULT_CA_CERT_PATH=/path/to/ca.cert


### Use it


```php

// get the vault seal status
$vault = app('vault');
$seal_status = $vault->sys()->sealStatus();

// seal the vault
$token = '1389b58b-0000-4800-a000-1d8869aee825'; // your vault authentication token
$vault = app('vault')->setToken($token);
$vault->sys()->seal();


```

