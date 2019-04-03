<?php

namespace Idplus\Mercure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\Publisher;

class NotifyMercure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $update;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Update $update)
    {
        $this->update = $update;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Publisher $publisher)
    {
        $publisher($this->update);
    }
}
