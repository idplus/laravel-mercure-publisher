<?php
namespace Idplus\Mercure\Tests;

use Idplus\Mercure\Jwt\JwtProvider;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;

class JwtProviderTest extends TestCase
{
    /** @test */
    public function it_returns_a_default_JwtProvider_instance_when_resolving_jwt_using_the_container()
    {
        app('config')->set('mercure.hub.jwt', "0.0.0");
        $this->assertInstanceOf(StaticJwtProvider::class, $this->app->make(JwtProvider::class));
    }

    /** @test */
    public function it_returns_a_custom_JwtProvider_instance_when_resolving_jwt_using_the_container()
    {
        $this->app->forgetInstance(JwtProvider::class);
        app('config')->set('mercure.jwt_provider', CustomJwtClass::class);
        $this->assertInstanceOf(CustomJwtClass::class, $this->app->make(JwtProvider::class));
    }
}