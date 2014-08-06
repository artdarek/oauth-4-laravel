<?php
/**
 * @author     Dariusz Prząda <artdarek@gmail.com>
 * @copyright  Copyright (c) 2013
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

namespace Artdarek\OAuth;

use Illuminate\Support\ServiceProvider;
use OAuth\ServiceFactory;

class OAuthServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('artdarek/oauth-4-laravel');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register 'oauth'
        $this->app['oauth'] = $this->app->share(function($app)
        {
            $sf = new ServiceFactory();
            $ts = new TokenStorage(\App::make('session'));

            return new OAuth($sf, $ts);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('oauth');
    }

}