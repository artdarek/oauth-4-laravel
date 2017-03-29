<?php namespace Artdarek\OAuth;

/**
 * @author     Dariusz Prząda <artdarek@gmail.com>
 * @copyright  Copyright (c) 2013
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

use \Config;
use \URL;

use \OAuth\ServiceFactory;
use \OAuth\Common\Consumer\Credentials;

class OAuth {

    /**
     * @var ServiceFactory
     */
    private $_serviceFactory;

    /**
     * Storege name from config
     *
     * @var string
     */
    private $_storageClass = '\\OAuth\\Common\\Storage\\Session';

    /**
     * Client ID from config
     *
     * @var string
     */
    private $_client_id;

    /**
     * Client secret from config
     *
     * @var string
     */
    private $_client_secret;

    /**
     * Scope from config
     *
     * @var array
     */
    private $_scope = [];

    /**
     * Constructor
     *
     * @param ServiceFactory $serviceFactory - (Dependency injection) If not provided, a ServiceFactory instance will be constructed.
     */
    public function __construct(ServiceFactory $serviceFactory = null)
    {
        if (null === $serviceFactory)
        {
            // Create the service factory
            $serviceFactory = new ServiceFactory();
        }
        $this->_serviceFactory = $serviceFactory;
    }

    /**
     * Detect config and set data from it
     *
     * @param string $service
     */
    public function setConfig($service)
    {
        // if config/oauth-4-laravel.php exists use this one
        if (Config::get('oauth-5-laravel.consumers') != null)
        {

            $this->_storageClass  = Config::get('oauth-5-laravel.storage', $this->_storageClass);
            $this->_client_id     = Config::get("oauth-5-laravel.consumers.$service.client_id");
            $this->_client_secret = Config::get("oauth-5-laravel.consumers.$service.client_secret");
            $this->_scope         = Config::get("oauth-5-laravel.consumers.$service.scope", []);

            // esle try to find config in packages configs
        }
        else
        {
            $this->_storageClass  = Config::get('oauth-5-laravel::storage', $this->_storageClass);
            $this->_client_id     = Config::get("oauth-5-laravel::consumers.$service.client_id");
            $this->_client_secret = Config::get("oauth-5-laravel::consumers.$service.client_secret");
            $this->_scope         = Config::get("oauth-5-laravel::consumers.$service.scope", []);
        }
    }

    /**
     * Create storage instance
     *
     * @param string $storageName
     *
     * @return OAuth\Common\\Storage
     */
    public function createStorageInstance($storageClass)
    {
        $storage = new $storageClass();

        return $storage;
    }

    /**
     * Set the http client object
     *
     * @param string $httpClientName
     *
     * @return void
     */
    public function setHttpClient($httpClientName)
    {
        $httpClientClass = "\\OAuth\\Common\\Http\\Client\\$httpClientName";
        $this->_serviceFactory->setHttpClient(new $httpClientClass());
    }

    /**
     * @param  string $service
     * @param  string $url
     * @param  array $scope
     *
     * @return \OAuth\Common\Service\AbstractService
     */
    public function consumer($service, $url = null, $scope = null)
    {
        // get config
        $this->setConfig($service);

        // get storage object
        $storage = $this->createStorageInstance($this->_storageClass);

        // create credentials object
        $credentials = new Credentials(
            $this->_client_id,
            $this->_client_secret,
            $url ? : URL::current()
        );

        // check if scopes were provided
        if (is_null($scope))
        {
            // get scope from config (default to empty array)
            $scope = $this->_scope;
        }

        // return the service consumer object
        return $this->_serviceFactory->createService($service, $credentials, $storage, $scope);

    }
}