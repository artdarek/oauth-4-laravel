# OAuth wrapper for Laravel 5

oauth-5-laravel is a simple laravel 5 service provider (wrapper) for [Lusitanian/PHPoAuthLib](https://github.com/Lusitanian/PHPoAuthLib) 
which provides oAuth support in PHP 5.4+ and is very easy to integrate with any project which requires an oAuth client.

Was first developed by [Artdarek](https://github.com/artdarek/oauth-4-laravel) for Laravel 4 and I ported it to Laravel 5.

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
    - BitBucket
    - Etsy
    - FitBit
    - Flickr
    - Scoop.it!
    - Tumblr
    - Twitter
    - Xing
    - Yahoo
 - OAuth2
    - Amazon
    - BitLy
    - Box
    - Dailymotion
    - Dropbox
    - Facebook
    - Foursquare
    - GitHub
    - Google
    - Harvest
    - Heroku
    - Instagram
    - LinkedIn
    - Mailchimp
    - Microsoft
    - PayPal
    - Pocket
    - Reddit
    - RunKeeper
    - SoundCloud
    - Vkontakte
    - Yammer
- more to come!

To learn more about Lusitanian/PHPoAuthLib go [here](https://github.com/Lusitanian/PHPoAuthLib) 

## Installation

Add oauth-5-laravel to your composer.json file:

```
"require": {
  "oriceon/oauth-5-laravel": "dev-master"
}
```

Use composer to install this package.

```
$ composer update
```

### Registering the Package

Register the service provider within the ```providers``` array found in ```config/app.php```:

```php
'providers' => [
	// ...
	
	Artdarek\OAuth\OAuthServiceProvider::class,
]
```

Add an alias within the ```aliases``` array found in ```config/app.php```:


```php
'aliases' => [
	// ...
	
	'OAuth'     => Artdarek\OAuth\Facade\OAuth::class,
]
```

## Configuration

There are two ways to configure oauth-5-laravel.
You can choose the most convenient way for you. 
You can use package config file which can be 
generated through command line by artisan (option 1) or 
you can simply create a config file called ``oauth-5-laravel.php`` in 
your ``config`` directory (option 2).

#### Option 1

Create configuration file for package using artisan command

```
$ php artisan vendor:publish --provider="Artdarek\OAuth\OAuthServiceProvider"
```

#### Option 2

Create configuration file manually in config directory ``config/oauth-5-laravel.php`` and put there code from below.

```php
<?php

use OAuth\Common\Storage\Session;

return [ 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => new Session(), 

	/**
	 * Consumers
	 */
	'consumers' => [

		/**
		 * Facebook
		 */
		'Facebook' => [
		    'client_id'     => '',
		    'client_secret' => '',
		    'scope'         => [],
		],		

	]

];
```

### Credentials

Add your credentials to ``config/oauth-5-laravel.php`` (depending on which option of configuration you choose)


The `Storage` attribute is optional and defaults to `Session`. 
Other [options](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/Common/Storage).

## Usage

### Basic usage

Just follow the steps below and you will be able to get a [service class object](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service) with this one rule:

```php
$fb = \OAuth::consumer('Facebook');
```

Optionally, add a second parameter with the URL which the service needs to redirect to, otherwise it will redirect to the current URL.

```php
$fb = \OAuth::consumer('Facebook', 'http://url.to.redirect.to');
```

## Usage examples

###Facebook:

Configuration:
Add your Facebook credentials to ``config/oauth-5-laravel.php``

```php
'Facebook' => [
    'client_id'     => 'Your Facebook client ID',
    'client_secret' => 'Your Facebook Client Secret',
    'scope'         => ['email','read_friendlists','user_online_presence'],
],	
```
In your Controller use the following code:

```php

public function loginWithFacebook(Request $request)
{
	// get data from request
	$code = $request->get('code');
	
	// get fb service
	$fb = \OAuth::consumer('Facebook');
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( ! is_null($code))
	{
		// This was a callback request from facebook, get the token
		$token = $fb->requestAccessToken($code);
		
		// Send a request with it
		$result = json_decode($fb->request('/me'), true);
		
		$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array.
		dd($result);
	}
	// if not ask for permission first
	else
	{
		// get fb authorization
		$url = $fb->getAuthorizationUri();
		
		// return to facebook login url
		return redirect((string)$url);
	}
}
```
###Google:

Configuration:
Add your Google credentials to ``config/oauth-5-laravel.php``

```php
'Google' => [
    'client_id'     => 'Your Google client ID',
    'client_secret' => 'Your Google Client Secret',
    'scope'         => ['userinfo_email', 'userinfo_profile'],
],	
```
In your Controller use the following code:

```php

public function loginWithGoogle(Request $request)
{
	// get data from request
	$code = $request->get('code');
	
	// get google service
	$googleService = \OAuth::consumer('Google');
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( ! is_null($code))
	{
		// This was a callback request from google, get the token
		$token = $googleService->requestAccessToken($code);
		
		// Send a request with it
		$result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
		
		$message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array.
		dd($result);
	}
	// if not ask for permission first
	else
	{
		// get googleService authorization
		$url = $googleService->getAuthorizationUri();
		
		// return to google login url
		return redirect((string)$url);
	}
}
```


###Twitter:

Configuration:
Add your Twitter credentials to ``config/oauth-5-laravel.php``

```php
'Twitter' => [
    'client_id'     => 'Your Twitter client ID',
    'client_secret' => 'Your Twitter Client Secret',
    // No scope - oauth1 doesn't need scope
],
```
In your Controller use the following code:

```php

public function loginWithTwitter(Request $request)
{
	// get data from request
	$token  = $request->get('oauth_token');
	$verify = $request->get('oauth_verifier');
	
	// get twitter service
	$tw = \OAuth::consumer('Twitter');
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( ! is_null($token) && ! is_null($verify))
	{
		// This was a callback request from twitter, get the token
		$token = $tw->requestAccessToken($token, $verify);
		
		// Send a request with it
		$result = json_decode($tw->request('account/verify_credentials.json'), true);
		
		$message = 'Your unique Twitter user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array.
		dd($result);
	}
	// if not ask for permission first
	else
	{
		// get request token
		$reqToken = $tw->requestRequestToken();
		
		// get Authorization Uri sending the request token
		$url = $tw->getAuthorizationUri(['oauth_token' => $reqToken->getRequestToken()]);

		// return to twitter login url
		return redirect((string)$url);
	}
}
```



###Linkedin:

Configuration:
Add your Linkedin credentials to ``config/oauth-5-laravel.php``

```php
'Linkedin' => [
    'client_id'     => 'Your Linkedin API ID',
    'client_secret' => 'Your Linkedin API Secret',
],
```
In your Controller use the following code:

```php

 public function loginWithLinkedin(Request $request)
 {
	// get data from request
	$code = $request->get('code');

	$linkedinService = \OAuth::consumer('Linkedin');


	if ( ! is_null($code))
	{
		// This was a callback request from linkedin, get the token
		$token = $linkedinService->requestAccessToken($code);

		// Send a request with it. Please note that XML is the default format.
		$result = json_decode($linkedinService->request('/people/~?format=json'), true);

		// Show some of the resultant data
		echo 'Your linkedin first name is ' . $result['firstName'] . ' and your last name is ' . $result['lastName'];

		//Var_dump
		//display whole array.
		dd($result);

	}
	// if not ask for permission first
	else
	{
		// get linkedinService authorization
		$url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);

		// return to linkedin login url
		return redirect((string)$url);
	}
}

```
###Yahoo:

Configuration:
Add your Yahoo credentials to ``config/oauth-5-laravel.php``

```php
'Yahoo' => [
	'client_id'     => 'Your Yahoo API KEY',
	'client_secret' => 'Your Yahoo API Secret',
],
```
In your Controller use the following code:

```php

public function loginWithYahoo(Request $request)
{
	// get data from request
    $token  = $request->get('oauth_token');
    $verify = $request->get('oauth_verifier');

    \OAuth::setHttpClient('CurlClient');

    // get yahoo service
    $yh = \OAuth::consumer('Yahoo');

    // if code is provided get user data and sign in
    if ( ! is_null($token) && ! is_null($verify))
    {
		// This was a callback request from yahoo, get the token
		$token = $yh->requestAccessToken($token, $verify);

		$xid = [$token->getExtraParams()];
		$result = json_decode($yh->request('https://social.yahooapis.com/v1/user/' . $xid[0]['xoauth_yahoo_guid'] . '/profile?format=json'), true);

		//Var_dump
		//display whole array.
		dd($result);
    }
    // if not ask for permission first
    else
    {
        // get request token
        $reqToken = $yh->requestRequestToken();

        // get Authorization Uri sending the request token
        $url = $yh->getAuthorizationUri(['oauth_token' => $reqToken->getRequestToken()]);

        // return to yahoo login url
        return redirect((string)$url);
    }
}

```
### More usage examples:

For examples go [here](https://github.com/Lusitanian/PHPoAuthLib/tree/master/examples)

