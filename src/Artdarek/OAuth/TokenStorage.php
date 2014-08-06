<?php
/**
 * @author     Samuel Vasko <samvasko@gmail.com>
 * @copyright  Copyright (c) 2013
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

namespace Artdarek\OAuth;

use OAuth\Common\Token\TokenInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use Illuminate\Session\SessionManager as Session;


/**
 * Implementation of token storage using Laravel session
 * that can be changed in config/session.php
 */
class TokenStorage implements TokenStorageInterface
{

    /**
     * Holds reference to Laravel session
     * @var Session
     */
    protected $session;

    /**
     * Base name for all tokens
     * @var string
     */
    protected $sessionVariable;

    function __construct(Session $session, $sessionVariable = 'OAuth')
    {
        $this->session = $session;
        $this->sessionVariable = $sessionVariable;
    }

    /**
     * Creates name where token is stored
     * @param  string $service Name of the service
     * @return string          Dot delimited key
     */
    protected function storageName($service)
    {
        return $this->sessionVariable.'.'.$service;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveAccessToken($service)
    {
        $name = $this->storageName($service);
        if ($token = $this->session->get($name))
            return unserialize($token);

        throw new TokenNotFoundException('Token not found in session, are you sure you stored it?');
    }

    /**
     * {@inheritDoc}
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $name = $this->storageName($service);
        $this->session->put($name, serialize($token));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccessToken($service)
    {
        $name = $this->storageName($service);
        return $this->session->has($name);
    }

    /**
     * {@inheritDoc}
     */
    public function clearToken($service)
    {
        $name = $this->storageName($service);
        $this->session->remove($service);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllTokens()
    {
        $this->session->remove($this->sessionVariable);

        return $this;
    }
    
    /**
     * {@inheritDoc}
     */    
    public function storeAuthorizationState($service, $state)
    {

        return $this;    
    }
    
    /**
     * {@inheritDoc}
     */    
    public function hasAuthorizationState($service)
    {

        return $this;    
    }

    /**
     * {@inheritDoc}
     */    
    public function retrieveAuthorizationState($service)
    {

        return $this;    
    }
    
    /**
     * {@inheritDoc}
     */    
    public function clearAuthorizationState($service)
    {

        return $this;    
    }
    
    /**
     * {@inheritDoc}
     */    
    public function clearAllAuthorizationStates()
    {

        return $this;    
    }    
}

