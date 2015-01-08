<?php

class StorageTest extends Orchestra\Testbench\TestCase {

    protected function getPackageProviders()
    {
        return array('Artdarek\OAuth\OAuthServiceProvider');
    }

    protected function getPackageAliases()
    {
        return array(
            'oauth' => 'Artdarek\OAuth\Facade\OAuth'
        );
    }

    public function testStorage()
    {
        $oauth = App::make('oauth');
        $consumer = $oauth->consumer('Facebook');
        $storage = $consumer->getStorage();
        $session = $storage->getSession();

        $this->assertInstanceOf('OAuth\Common\Storage\SymfonySession', $storage);
        $this->assertInstanceOf('Illuminate\Session\Store', $session);
    }

    public function testSharesLaravelSession()
    {
        $oauth = App::make('oauth');
        $consumer = $oauth->consumer('Facebook');
        $storage = $consumer->getStorage();
        $session = $storage->getSession();

        $session->set('foo', 'bar');
        $this->assertEquals('bar', Session::get('foo'));
    }

}
