# Laravel Mercure Publisher


Laravel Mercure Publisher is a simple wrapper around the [Symfony Mercure Component](https://github.com/symfony/mercure), leveraging *Server Sent Events* with the [Mercure protocol](https://github.com/dunglas/mercure).

## Installation

This package can be installed through Composer.

``` bash
composer require idplus/laravel-mercure-publisher
```

The package will automatically register its service provider.

Out of the box, you can define the "Mercure Hub Url" and his "JWT Secret" via Environment Variable in your `.env` file:

``` bash
...
MERCURE_PUBLISH_URL=http://127.0.0.1:3000/hub
MERCURE_JWT_SECRET=your.token.here
```

Optionally, you can publish the entire config file to `config/mercure.php` with this command:

``` bash
php artisan vendor:publish --provider="Idplus\Mercure\MercureServiceProvider"
```

Here is the default contents of the configuration file:

```php
return [
    'hub' => [
        'url' => env('MERCURE_PUBLISH_URL','http://127.0.0.1:3000/hub'),
        'jwt' => env('MERCURE_JWT_SECRET'),
    ],
    'jwt_provider' => null,
    'queue_name' => null,
];
```

## Usage

First, make sure you have [Mercure](https://github.com/dunglas/mercure) installed and running.

### Publishing

The `Symfony Mercure Component` loaded with this package provides an Update value object representing the update to publish.
You can then use the `Publify` service to dispatch updates to the Hub.

The `Publify` service can be injected using dependency injection in any other services or controllers:

```php
<?php
namespace App\Http\Controllers;

use Symfony\Component\Mercure\Update;
use Idplus\Mercure\Publify;

class MercureController extends Controller
{
    public function pub(Publify $publisher)
    {
        // ...
        $data = ['status' => 'OutOfStock'];
        $update = new Update(
            'http://topic.iri.local/sub/1',
            json_encode($data)
        );
        $publisher($update);
        // ...

        return view('pub', $data);
    }
```
### Subscribing
In your frontend `blade` view you can subscribe to updates in JavaScript like this:

```javascript
const es = new EventSource('{{ config('mercure.hub.url') }}?topic=' + encodeURIComponent('http://topic.iri.local/sub/1'));
es.onmessage = e => {
    // Will be called every time an update is published by the server
    alert(JSON.parse(e.data).status);
}
```

## Advanced usage

### Delegate the JWT generation to your custom service

Instead of directly storing a JWT in the configuration, you can create a service that will return a customized token:

```php
<?php
namespace App;

use Idplus\Mercure\Jwt\JwtProvider;

class CustomJwt implements JwtProvider
{
    /**
     * Return custom JWT
     *
     * @return string
     */
    public function __invoke(): string
    {
        return 'my.custom.jwt';
    }
}
```

You need to reference this class in the `config/mercure.php` configuration file:
```php
    ...
    'jwt_provider' => "\App\CustomJwt",
```
### Async dispatching

By default, Updates sent with the `Publify` service are synchronous.

You can dispatch the updates asynchronously using Laravel Queue. You just have to fill the `queue_name` variable in the Mercure configuration file. For example, you can use _'default'_ or _'high'_.

Don't forget to run `php artisan queue:work` beforehand.

### Mercure Hub Auto-Discovery

This packege auto-register the `mercure.discover` Middleware. You can use it on your *subscribes* routes to inject the URL of the Mercure Hub in a `Link` HTTP header. 

The hub URL can be automatically discovered:

```php
<?php

namespace App\Http\Controllers;

class MercureController extends Controller
{
    public function __construct()
    {
        $this->middleware('mercure.discover'); // Auto inject 'Link' header
    }
...
```

## References

* [Mercure documentation](https://github.com/dunglas/mercure)
* [Symfony integration document](https://symfony.com/doc/current/mercure.html)

You should also try [Laravel Mercure Broadcaster](https://github.com/mvanduijker/laravel-mercure-broadcaster) for another type of implementation.


## License

This package is published under the [MIT License](LICENSE.md) (MIT).