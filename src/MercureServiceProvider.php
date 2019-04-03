<?php

namespace Idplus\Mercure;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Idplus\Mercure\Jwt\JwtProvider;
use Idplus\Mercure\Exceptions\InvalidConfigurationException;
use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;

class MercureServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $source = realpath(__DIR__.'/../config/mercure.php');
        $this->publishes([$source => config_path('mercure.php')], 'config');
        $router->aliasMiddleware('mercure.discover', \Idplus\Mercure\Middleware\MercureDiscover::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $source = realpath(__DIR__.'/../config/mercure.php');
        $this->mergeConfigFrom($source, 'mercure');

        $this->app->singleton(JwtProvider::class, function () {
            $this->guardAgainstInvalidConfiguration(config('mercure'));
            return config('mercure.jwt_provider') !== null && class_exists(config('mercure.jwt_provider'))
                ? app(config('mercure.jwt_provider')) : new StaticJwtProvider(config('mercure.hub.jwt'));
        });

        $this->app->bind(Publisher::class, function () {
            return new Publisher(
                config('mercure.hub.url'),
                $this->app->make(JwtProvider::class)
            );
        });
    }

    protected function guardAgainstInvalidConfiguration(array $mercureConfig = null)
    {
        if (!($mercureConfig['hub']['url'] ?? false) || !is_string($mercureConfig['hub']['url'])) {
            throw InvalidConfigurationException::hubUrlNotSpecified();
        }

        if (!empty($mercureConfig['hub']['jwt']) && !empty($mercureConfig['jwt_provider'])) {
            throw InvalidConfigurationException::ambiguousJwtClass();
        }

        if (empty($mercureConfig['hub']['jwt']) && empty($mercureConfig['jwt_provider'])) {
            throw InvalidConfigurationException::jwtProviderNotSpecified();
        }
    }
}
