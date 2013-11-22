# OAuth wrapper for Laravel 4

oauth-4-laravel is a simple laravel 4 service provider (wrapper) for [Lusitanian/PHPoAuthLib](https://github.com/Lusitanian/PHPoAuthLib) 
which provides oAuth support in PHP 5.3+ and is very easy to integrate with any project which requires an oAuth client.

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
'OAuth' => 'Artdarek\OAuth\Facade\OAuth',
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
		    'scope'         => array(),
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

## Usage examples

###Facebook:

Configuration:
Add your Facebook credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
'Facebook' => array(
    'client_id'     => 'Your Facebook client ID',
    'client_secret' => 'Your Facebook Client Secret',
    'scope'         => array('email','read_friendlists','user_online_presence'),
),	
```
In your Controller use the following code:

```php
/**
 * Login user with facebook
 *
 * @return void
 */

public function loginWithFacebook() {
	
	// get data from input
	$code = Input::get( 'code' );
	
	// get fb service
	$fb = OAuth::consumer( 'Facebook' );
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( !empty( $code ) ) {
		
		// This was a callback request from google, get the token
		$token = $fb->requestAccessToken( $code );
		
		// Send a request with it
		$result = json_decode( $fb->request( '/me' ), true );
		
		$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array().
		dd($result);
	
	}
	// if not ask for permission first
	else {
		// get fb authorization
		$url = $fb->getAuthorizationUri();
		
		// return to facebook login url
		return Response::make()->header( 'Location', (string)$url );
	}

}
```
###Google:

Configuration:
Add your Google credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
'Google' => array(
    'client_id'     => 'Your Google client ID',
    'client_secret' => 'Your Google Client Secret',
    'scope'         => array('userinfo_email', 'userinfo_profile'),
),	
```
In your Controller use the following code:

```php
public function loginWithGoogle() {

	// get data from input
	$code = Input::get( 'code' );
	
	// get google service
	$googleService = OAuth::consumer( 'Google' );
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( !empty( $code ) ) {
	
		// This was a callback request from google, get the token
		$token = $googleService->requestAccessToken( $code );
		
		// Send a request with it
		$result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
		
		$message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array().
		dd($result);
	        
	}
	// if not ask for permission first
	else {
		// get googleService authorization
		$url = $googleService->getAuthorizationUri();
		
		// return to facebook login url
		return Response::make()->header( 'Location', (string)$url );
	}
}
```



###Linkedin:

Configuration:
Add your Linkedin credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
'Linkedin' => array(
    'client_id'     => 'Your Linkedin API ID',
    'client_secret' => 'Your Linkedin API Secret',
),	
```
In your Controller use the following code:

```php

 public function loginWithLinkedin() {

        // get data from input
        $code = Input::get( 'code' );

        $linkedinService = OAuth::consumer( 'Linkedin' );


        if ( !empty( $code ) ) {

            // This was a callback request from linkedin, get the token
            $token = $linkedinService->requestAccessToken( $code );
            // Send a request with it. Please note that XML is the default format.
            $result = json_decode($linkedinService->request('/people/~?format=json'), true);

            // Show some of the resultant data
            echo 'Your linkedin first name is ' . $result['firstName'] . ' and your last name is ' . $result['lastName'];


            //Var_dump
            //display whole array().
            dd($result);

        }// if not ask for permission first
        else {
            // get linkedinService authorization
            $url = $linkedinService->getAuthorizationUri(array('state'=>'DCEEFWF45453sdffef424'));

            // return to linkedin login url
            return Response::make()->header( 'Location', (string)$url );
        }


    }

```

### More usage examples:

For examples go [here](https://github.com/Lusitanian/PHPoAuthLib/tree/master/examples)

