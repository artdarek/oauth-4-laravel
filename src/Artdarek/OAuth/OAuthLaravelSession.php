<?php namespace Artdarek\OAuth;

use Illuminate\Support\Facades\Session;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;

class OAuthLaravelSession implements TokenStorageInterface
{

    /**
     * @param string $service
     *
     * @return TokenInterface
     *
     * @throws TokenNotFoundException
     */
    public function retrieveAccessToken($service)
    {
        if ($this->hasAccessToken($service)) {
            return unserialize(Session::get('oauth.token.'.$service));
        }

        throw new TokenNotFoundException('Token not found in session, are you sure you stored it?');
    }

    /**
     * @param string $service
     * @param TokenInterface $token
     *
     * @return TokenStorageInterface
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $serializedToken = serialize($token);
        Session::put('oauth.token.'.$service, $serializedToken);

        return $this;
    }

    /**
     * @param string $service
     *
     * @return bool
     */
    public function hasAccessToken($service)
    {
         return Session::has('oauth.token.'.$service);
    }

    /**
     * Delete the users token. Aka, log out.
     *
     * @param string $service
     *
     * @return TokenStorageInterface
     */
    public function clearToken($service)
    {
        Session::forget('oauth.token.'.$service);
        return $this;
    }

    /**
     * Delete *ALL* user tokens. Use with care. Most of the time you will likely
     * want to use clearToken() instead.
     *
     * @return TokenStorageInterface
     */
    public function clearAllTokens()
    {
        Session::forget('oauth.token');
        return $this;
    }

    /**
     * Store the authorization state related to a given service
     *
     * @param string $service
     * @param string $state
     *
     * @return TokenStorageInterface
     */
    public function storeAuthorizationState($service, $state)
    {
        Session::put('oauth.state.'.$service, $state);
        return $this;
    }

    /**
     * Check if an authorization state for a given service exists
     *
     * @param string $service
     *
     * @return bool
     */
    public function hasAuthorizationState($service)
    {
        return Session::has('oauth.state.'.$service);
    }

    /**
     * Retrieve the authorization state for a given service
     *
     * @param string $service
     *
     * @return string
     * @throws AuthorizationStateNotFoundException
     */
    public function retrieveAuthorizationState($service)
    {
        if ($this->hasAuthorizationState($service)) {
            return Session::get('oauth.state.'.$service);
        }

        throw new AuthorizationStateNotFoundException('State not found in session, are you sure you stored it?');
    }

    /**
     * Clear the authorization state of a given service
     *
     * @param string $service
     *
     * @return TokenStorageInterface
     */
    public function clearAuthorizationState($service)
    {
        Session::forget('oauth.state.'.$service);
        return $this;
    }

    /**
     * Delete *ALL* user authorization states. Use with care. Most of the time you will likely
     * want to use clearAuthorization() instead.
     *
     * @return TokenStorageInterface
     */
    public function clearAllAuthorizationStates()
    {
        Session::forget('oauth.state');
        return $this;
    }
}
