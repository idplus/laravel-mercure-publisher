<?php

namespace Idplus\Mercure\Middleware;

use Closure;
use Illuminate\Http\Request;

class MercureDiscover
{
    /**
     * Handle incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $hub_url = config('mercure.hub.url');

        if (!empty($hub_url) || !$response->isRedirection()) {
            $response->headers->set('Link', '<' . $hub_url . '>; rel=\'mercure\'');
        }

        return $response;
    }
}