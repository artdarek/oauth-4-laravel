<?php namespace Artdarek\OAuth;

use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['oauth'] = new OAuth;
    }

}