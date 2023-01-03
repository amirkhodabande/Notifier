<?php

namespace Amir\Notifier\Tests;

use Amir\Notifier\Providers\NotifierServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            NotifierServiceProvider::class
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('notifier.sms-provider.url', 'https://www.sms.com');
        $app['config']->set('notifier.sms-provider.retry-time', 3);
        $app['config']->set('notifier.sms-provider.sleep-time', 100);

        $app['config']->set('notifier.mail-provider.url', 'https://www.mail.com');
        $app['config']->set('notifier.mail-provider.retry-time', 3);
        $app['config']->set('notifier.mail-provider.sleep-time', 100);
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