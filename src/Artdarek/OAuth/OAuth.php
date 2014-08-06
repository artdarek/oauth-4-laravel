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
    private $_serviceFactory;

    /**
     * @var TokenStorage
     */
    private $_storage;

    /**
     * Client ID from config
     * @var string
     */
    private $_client_id;

    /**
     * Client secret from config
     * @var string
     */
    private $_client_secret;

    /**
     * Scope from config
     * @var array
     */
    private $_scope = array();

    /**
     * Constructor
     *
     * @param ServiceFactory $serviceFactory
     */
    public function __construct(ServiceFactory $serviceFactory, TokenStorage $storage)
    {
        $this->_serviceFactory = $serviceFactory;
        $this->_storage = $storage;
    }

    /**
     * Detect config and set data from it
     *
     * @param string $service
     */
    public function loadConfig($service)
    {
        $this->_client_id = $this->getConfig('consumers.'.$service.'.client_id');
        $this->_client_secret = $this->getConfig('consumers.'.$service.'.client_secret');
        $this->_scope = $this->getConfig('consumers.'.$service.'.scope');
    }

    /**
     * Get config value for key
     * Looks at 3 different locations where they can be placed
     * @param  string $key   OAuth config value
     * @return string        Config value
     */
    protected function getConfig($key)
    {
        if ($value = Config::get('oauth.'.$key))
            return $value;
        /**
         * @deprecated This value should get removed in future releases
         * As it is too long and contains unecessary information
         */
        if ($value = Config::get('oauth-4-laravel.'.$key))
            return $value;

        if ($value = Config::get('oauth-4-laravel::'.$key))
            return $value;

    }

    /**
     * Set the http client object
     *
     * @param string $httpClientName
     * @return void
     */
    public function setHttpClient($httpClientName)
    {
        $httpClientClass = '\OAuth\Common\Http\Client\$httpClientName';
        $this->_serviceFactory->setHttpClient(new $httpClientClass());
    }

    /**
     * @param  string $service
     * @param  string $url
     * @param  array  $scope
     * @return \OAuth\Common\Service\AbstractService
     */
    public function consumer($service, $url = null, $scope = null)
    {
        // get config
        $this->loadConfig($service);

        // create credentials object
        $credentials = new Credentials(
            $this->_client_id,
            $this->_client_secret,
            $url ?: URL::current()
        );

        // check if scopes were provided
        if (is_null($scope))
            $scope = $this->_scope ? $this->_scope : array();

        // return the service consumer object
        return $this->_serviceFactory->createService($service, $credentials, $this->_storage, $scope);

    }
}
