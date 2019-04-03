<?php
namespace Idplus\Mercure\Tests;

use Idplus\Mercure\MercureServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Symfony\Component\Mercure\Update;

abstract class TestCase extends Orchestra
{
    /** @var \Symfony\Component\Mercure\Update */
    protected $testUpdate;

    public function setUp(): void
    {
        parent::setUp();
        $this->testUpdate = new Update(
            'http://mercure.local/topic/1',
            json_encode(['status' => 'OutOfStock'])
        );
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MercureServiceProvider::class];
    }
}