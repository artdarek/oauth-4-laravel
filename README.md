# OAuth wrapper for Laravel 4

## Usage

Just follow the steps below and you will be able to get a [service class object](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/OAuth2/Service) with this one rule:

```php
$fb = OAuth::consumer('Facebook');
```

Optionally, add a second parameter with the URL which the service needs to redirect to.

## How to integrate

### Alias

Add an alias to the bottom of app/config/app.php

```php
'OAuth' => 'hannesvdvreken\OAuth\facade\OAuth',
```

and register this service provider at the bottom of the `$providers` array:

```php
'hannesvdvreken\OAuth\OAuthServiceProvider',
```

### Credentials

Add your credentials to app/config/oauth.php

```php
return array(

    'storage' => 'Session',

    'consumers' => array(
        'Facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scope' => array(),
        ),
    ),
);
```

The `Storage` attribute is optional and defaults to `Session`. Other [options](https://github.com/Lusitanian/PHPoAuthLib/tree/master/src/OAuth/Common/Storage).
