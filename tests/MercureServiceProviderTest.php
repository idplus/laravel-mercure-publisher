<?php
namespace Idplus\Mercure\Tests;
use Idplus\Mercure\Publify;
use Idplus\Mercure\Exceptions\InvalidConfigurationException;
use Symfony\Component\Mercure\Publisher;
class MercureServiceProviderTest extends TestCase
{
    /** @test */
    public function it_will_throw_an_exception_if_no_jwt_parameter_is_set()
    {
        $this->expectException(InvalidConfigurationException::class);
        $Publify = new Publify();
        $Publify($this->testUpdate);
    }

    /** @test */
    public function it_will_throw_an_exception_if_hub_url_parameter_is_not_set()
    {
        config()->set('mercure.hub.url', '');
        $this->expectException(InvalidConfigurationException::class);
        $Publify = new Publify();
        $Publify($this->testUpdate);
    }

    /** @test */
    public function it_will_throw_an_exception_if_more_than_one_jwt_provider_is_set()
    {
        config()->set('mercure.hub.jwt', '0.0.0');
        config()->set('mercure.jwt_provider', CustomJwtClass::class);
        $this->expectException(InvalidConfigurationException::class);
        $Publify = new Publify();
        $Publify($this->testUpdate);
    }

    /** @test */
    public function it_returns_an_instance_of_mercure_publisher()
    {
        config()->set('mercure.hub.jwt', '0.0.0');
        $publisher = $this->app->make(Publisher::class);
        $this->assertInstanceOf(\Symfony\Component\Mercure\Publisher::class, $publisher);
    }
}