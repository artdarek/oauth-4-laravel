<?php

namespace Artdarek\OAuth\Common\Storage;

use OAuth\Common\Token\TokenInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;
use Illuminate\Support\Facades\Session;

/**
 * Stores a token in a Laravel session.
 */
class LaravelSession implements TokenStorageInterface
{

    /**
     * @var string
     */
    protected $sessionNamespace;

    /**
     * @var string
     */
    protected $sessionVariableName;

    /**
     * @var string
     */
    protected $stateVariableName;

    /**
     * @param string $sessionNamespace prefix to Laravel sessions before dot notation
     * @param string $sessionVariableName the variable name to use within Laravel session
     * @param string $stateVariableName
     */
    public function __construct(
        $sessionNamespace = 'lusitanian',
        $sessionVariableName = 'oauth_token',
        $stateVariableName = 'oauth_state'
    ) {

        $this->sessionNamespace = $sessionNamespace;
        $this->sessionVariableName = $sessionNamespace.'.'.$sessionVariableName;
        $this->stateVariableName = $sessionNamespace.'.'.$stateVariableName;

    }

    /**
     * {@inheritDoc}
     */
    public function retrieveAccessToken($service)
    {
        if ($this->hasAccessToken($service)) {
            return Session::get($this->sessionVariableName.'.'.$service);
        }

        throw new TokenNotFoundException('Token not found in session, are you sure you stored it?');
    }

    /**
     * {@inheritDoc}
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        Session::put($this->sessionVariableName.".".$service, $token);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccessToken($service)
    {
        $token = Session::get($this->sessionVariableName.'.'.$service);
        return !empty($token);
    }

    /**
     * {@inheritDoc}
     */
    public function clearToken($service)
    {
        Session::forget($this->sessionVariableName . '.' . $service);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllTokens()
    {
        Session::forget($this->sessionVariableName);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function storeAuthorizationState($service, $state)
    {
        Session::put($this->stateVariableName.'.'.$service, $state);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAuthorizationState($service)
    {
        $state = Session::get($this->stateVariableName.'.'.$service);
        return !empty($state);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveAuthorizationState($service)
    {
        if ($this->hasAuthorizationState($service)) {
            return Session::get($this->stateVariableName.'.'.$service);
        }

        throw new AuthorizationStateNotFoundException('State not found in session, are you sure you stored it?');
    }

    /**
     * {@inheritDoc}
     */
    public function clearAuthorizationState($service)
    {
        Session::forget($this->stateVariableName.'.'.$service);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllAuthorizationStates()
    {
        Session::forget($this->stateVariableName);

        // allow chaining
        return $this;
    }

    public function __destruct()
    {

    }
}
