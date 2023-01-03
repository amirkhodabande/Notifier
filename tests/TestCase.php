<?php

namespace Amir\Notifier\Tests;

use Amir\Notifier\Providers\NotifierServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(dirname(__DIR__).'/database/factories'));
    }

    protected function getPackageProviders($app)
    {
        return [
            NotifierServiceProvider::class
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('notifier.sms.test-provider.url', 'https://www.sms.com');

        $app['config']->set('notifier.email.custom-provider.url', 'https://www.mail.com');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}