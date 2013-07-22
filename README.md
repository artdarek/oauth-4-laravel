# OAuth wrapper for Laravel 4

oauth-4-laravel is a simple laravel 4 service provider (wrapper) for [Lusitanian/PHPoAuthLib](https://github.com/Lusitanian/PHPoAuthLib) 
witch provides oAuth support in PHP 5.3+ and is very easy to integrate with any project which requires an oAuth client.

---
 
- [Supported services](#supported-services)
- [Installation](#installation)
- [Registering the Package](#registering-the-package)
- [Configuration](#configuration)
- [Usage](#usage)
- [Basic usage](#basic-usage)
- [More usage examples](#more-usage-examples)

## Supported services

The library supports both oAuth 1.x and oAuth 2.0 compliant services. A list of currently implemented services can be found below. More services will be implemented soon.

Included service implementations:

 - OAuth1
   - Twitter
   - FitBit

 - OAuth2
   - Google
   - Microsoft
   - Facebook
   - GitHub
   - BitLy
   - Yammer
   - SoundCloud
   - Foursquare
   - Instagram
   - LinkedIn
   - Box
   - Tumblr
   - Vkontakte

To learn more about Lusitanian/PHPoAuthLib go [here](https://github.com/Lusitanian/PHPoAuthLib) 

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

## Configuration

### Registering the Package

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

The `Storage` attribute is optional and defaults to `Session`. 
Other [options](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/Common/Storage).

## Usage

### Basic usage

Just follow the steps below and you will be able to get a [service class object](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service) with this one rule:

```php
$fb = OAuth::consumer('Facebook');
```

Optionally, add a second parameter with the URL which the service needs to redirect to:

```php
$fb = OAuth::consumer('Facebook','http://url.to.redirect.to');
```

### More usage examples

For examples go [here](https://github.com/Lusitanian/PHPoAuthLib/tree/master/examples)

