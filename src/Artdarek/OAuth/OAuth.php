<?php namespace Artdarek\OAuth;

/*
|--------------------------------------------------------------------------
| How to use
|--------------------------------------------------------------------------
| eg:
|
| $fb = OAuth::consumer('Facebook'); 
|
| returns a configured consumer object
| class details found here:
|     https://github.com/Lusitanian/PHPoAuthLib/blob/master/src/OAuth/OAuth2/Service/Facebook.php
|
| credentials and scope are loaded from config/oauth.php
|
*/

use Illuminate\Support\ServiceProvider;

use \OAuth\ServiceFactory;
use \OAuth\Common\Consumer\Credentials;

use \Config;
use \URL;

class OAuth {

    /**
     * @param  string $service
     * @return \OAuth\Common\Service\AbstractService
     */
    public function consumer( $service, $url = null ) {

        // create a factory. but remember: this is not java.
        $service_factory = new ServiceFactory();

        // get storage
        $storage_name = Config::get('oauth-4-laravel::storage') ?: 'Session'; // default

        $cn = "\\OAuth\Common\\Storage\\$storage_name";
        $storage = new $cn();

        // create credentials object
        $credentials = new Credentials(
            Config::get("oauth-4-laravel::consumers.$service.client_id"),
            Config::get("oauth-4-laravel::consumers.$service.client_secret"),
            $url ?: URL::current()
        );

        // get scope (default to empty array)
        $scope = Config::get("oauth-4-laravel::consumers.$service.scope") ?: array();

        // return the service consumer object
        return $service_factory->createService($service, $credentials, $storage, $scope);

    }
}
