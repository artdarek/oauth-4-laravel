<?php namespace Artdarek\OAuth\Facade;

use Illuminate\Support\Facades\Facade;

class OAuth extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'oauth'; }

}