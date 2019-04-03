<?php

namespace Idplus\Mercure;

use Idplus\Mercure\Jobs\NotifyMercure;
use Symfony\Component\Mercure\Update;

class Publify
{
    /**
     * Send update to Mercure hub
     *
     * @param Symfony\Component\Mercure\Update $update
     *
     * @return bool
     */
    public function __invoke(Update $update): bool
    {
        $customQueue = config('mercure.queue_name');
        if(!is_null($customQueue)) {
            NotifyMercure::dispatch($update)->onQueue($customQueue);
        }
        else {
            NotifyMercure::dispatchNow($update);
        }

        return true;
    }
}