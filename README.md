# OAuth wrapper for Laravel 4

## Usage

Just follow the steps below and you will be able to get a [service class object](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service) with this one rule:

```php
$fb = OAuth::consumer('Facebook');
```

Optionally, add a second parameter with the URL which the service needs to redirect to:

```php
$fb = OAuth::consumer('Facebook','http://url.to.redirect.to');
```

## Installation

Add oauth-4-laravel to your composer.json file:

```
"require": {
  "artdarek/oauth-4-laravel": "dev-master"
}
```

Use composer to install this package.

```
$ composer update
```

Create configuration file using artisan

```
$ php artisan config:publish artdarek/oauth-4-laravel
```

## How to integrate

### Alias

Add an alias to the bottom of app/config/app.php

```php
'OAuth' => 'Artdarek\OAuth\facade\OAuth',
```

and register this service provider at the bottom of the `$providers` array:

```php
'Artdarek\OAuth\OAuthServiceProvider',
```

### Credentials

Add your credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scope'         => [],
        ),		

	)

);
```

The `Storage` attribute is optional and defaults to `Session`. Other [options](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/Common/Storage).
