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

### Registering the Package

Register the service provider within the ```providers``` array found in ```app/config/app.php```:

```php
'providers' => array(
	// ...
	
	'Artdarek\OAuth\OAuthServiceProvider'
)
```

Add an alias within the ```aliases``` array found in ```app/config/app.php```:


```php
'aliases' => array(
	// ...
	
	'OAuth' => 'Artdarek\OAuth\Facade\OAuth',
)
```

## Configuration

There are two ways to configure oauth-4-laravel.
You can choose the most convenient way for you. 
You can use package config file which can be 
generated through command line by artisan (option 1) or 
you can simply create a config file called ``oauth-4-laravel.php`` in 
your ``app\config\`` directory (option 2).

#### Option 1

Create configuration file for package using artisan command

```
$ php artisan config:publish artdarek/oauth-4-laravel
```

#### Option 2

Create configuration file manually in config directory ``app/config/oauth-4-laravel.php`` and put there code from below.

```php
<?php
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

### Credentials

Add your credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php`` or ``app/config/oauth-4-laravel.php`` (depending on which option of configuration you choose)


The `Storage` attribute is optional and defaults to `Session`. 
Other [options](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/Common/Storage).

## Usage

### Basic usage

Just follow the steps below and you will be able to get a [service class object](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service) with this one rule:

```php
$fb = OAuth::consumer('Facebook');
```

Optionally, add a second parameter with the URL which the service needs to redirect to, otherwise it will redirect to the current URL.

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
		
		// This was a callback request from facebook, get the token
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
		 return Redirect::to( (string)$url );
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
		
		// return to google login url
		return Redirect::to( (string)$url );
	}
}
```


###Twitter:

Configuration:
Add your Twitter credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
'Twitter' => array(
    'client_id'     => 'Your Twitter client ID',
    'client_secret' => 'Your Twitter Client Secret',
    // No scope - oauth1 doesn't need scope
),	
```
In your Controller use the following code:

```php
public function loginWithTwitter() {

	// get data from input
	$token = Input::get( 'oauth_token' );
	$verify = Input::get( 'oauth_verifier' );
	
	// get twitter service
	$tw = OAuth::consumer( 'Twitter' );
	
	// check if code is valid
	
	// if code is provided get user data and sign in
	if ( !empty( $token ) && !empty( $verify ) ) {
	
		// This was a callback request from twitter, get the token
		$token = $tw->requestAccessToken( $token, $verify );
		
		// Send a request with it
		$result = json_decode( $tw->request( 'account/verify_credentials.json' ), true );
		
		$message = 'Your unique Twitter user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		echo $message. "<br/>";
		
		//Var_dump
		//display whole array().
		dd($result);
	        
	}
	// if not ask for permission first
	else {
		// get request token
		$reqToken = $tw->requestRequestToken();
		
		// get Authorization Uri sending the request token
		$url = $tw->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));

		// return to twitter login url
		return Redirect::to( (string)$url );
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
            return Redirect::to( (string)$url );
        }


    }

```
###Yahoo:

Configuration:
Add your Yahoo credentials to ``app/config/packages/artdarek/oauth-4-laravel/config.php``

```php
'Yahoo' => array(
            'client_id'     => 'Your Yahoo API KEY',
            'client_secret' => 'Your Yahoo API Secret',  
),	
```
In your Controller use the following code:

```php

public function loginWithYahoo() {
   // get data from input
   	$token = Input::get( 'oauth_token' );
    $verify = Input::get( 'oauth_verifier' );
    // get yahoo service
    $yh = OAuth::consumer( 'Yahoo' );

    // if code is provided get user data and sign in
    if ( !empty( $token ) && !empty( $verify ) ) {
				// This was a callback request from yahoo, get the token
				$token = $yh->requestAccessToken( $token, $verify );
				$xid = array($token->getExtraParams());
				$result = json_decode( $yh->request( 'https://social.yahooapis.com/v1/user/'.$xid[0]['xoauth_yahoo_guid'].'/profile?format=json' ), true );	
                
                dd($result);								
    }
    // if not ask for permission first
    else {
        // get request token
        $reqToken = $yh->requestRequestToken();
        // get Authorization Uri sending the request token
        $url = $yh->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));
        // return to yahoo login url
        return Redirect::to( (string)$url );
    }
}

```
### More usage examples:

For examples go [here](https://github.com/Lusitanian/PHPoAuthLib/tree/master/examples)

