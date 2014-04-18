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
     * {@inheritDoc}
     */
    public function retrieveAccessToken($service)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function storeAccessToken($service, TokenInterface $token)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function hasAccessToken($service)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function clearToken($service)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function clearAllTokens()
    {

    }
}

