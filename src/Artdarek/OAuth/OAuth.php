<?php 
/**
 * @author     Dariusz Prząda <artdarek@gmail.com>
 * @copyright  Copyright (c) 2013
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

namespace Artdarek\OAuth;

use Illuminate\Support\ServiceProvider;

use \Config;
use \URL;

use \OAuth\ServiceFactory;
use \OAuth\Common\Consumer\Credentials;

class OAuth 
{
    /**
     * @var ServiceFactory
     */
    private $serviceFactory;

    /**
     * Constructor
     */
    public function __construct() {

        // create a service factory
        $this->serviceFactory = new ServiceFactory();

    } 

    /**
     * Create storage instance
     *
     * @param string $storageName
     * @return OAuth\Common\\Storage
     */
    public function createStorageInstance($storageName)
    {
        $storageClass = "\\OAuth\Common\\Storage\\$storageName";
        $storage = new $storageClass();

        return $storage;
    }

    /**
     * @param  string $service
     * @param  string $url
     * @param  array  $scope
     * @return \OAuth\Common\Service\AbstractService
     */
    public function consumer( $service, $url = null, $scope = null ) 
    {
        // get storage object
        $storage_name = Config::get('oauth-4-laravel::storage', 'Session');
        $storage = $this->createStorageInstance( $storage_name );

        // create credentials object
        $credentials = new Credentials(
            Config::get("oauth-4-laravel::consumers.$service.client_id"),
            Config::get("oauth-4-laravel::consumers.$service.client_secret"),
            $url ?: URL::current()
        );

        // check if scopes were provided
        if (is_null($scope))
        {
            // get scope from config (default to empty array)
            $scope = Config::get("oauth-4-laravel::consumers.$service.scope", array() );
        }

        // return the service consumer object
        return $this->serviceFactory->createService($service, $credentials, $storage, $scope);

    }
}