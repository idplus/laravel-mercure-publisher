<?php
namespace Idplus\Mercure\Tests;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Idplus\Mercure\Jobs\NotifyMercure;
use Idplus\Mercure\Publify;

class PublifyTest extends TestCase
{
    /** @var \Idplus\Mercure\Notify */
    protected $notify;


    public function setUp(): void
    {
        parent::setUp();
        config()->set('mercure.hub.jwt', '0.0.0');
        $this->notify = new Publify();
    }

    /** @test */
    public function it_can_send_notification_to_mercure()
    {
        Bus::fake();
        $testUpdate=$this->testUpdate;
        $this->notify->__invoke($this->testUpdate);
        Bus::assertDispatched(NotifyMercure::class, function ($job) use ($testUpdate) {
            return $job->update === $testUpdate;
        });
    }

    /** @test */
    public function it_can_queue_notification_to_mercure_now()
    {
        Queue::fake();
        $this->expectException('RuntimeException'); // Workaround : can't mock publisher call (final class)
        $this->notify->__invoke($this->testUpdate);
        Queue::assertNotPushed(NotifyMercure::class);
    }
    
    /** @test */
    public function it_can_queue_notification_to_mercure()
    {
        Queue::fake();
        config()->set('mercure.queue_name', "default");
        $this->notify->__invoke($this->testUpdate);
        Queue::assertPushed(NotifyMercure::class);
    }

    /** @test */
    public function it_can_queue_notification_to_mercure_via_custom_queue()
    {
        Queue::fake();
        config()->set('mercure.queue_name', "high");
        $this->notify->__invoke($this->testUpdate);
        Queue::assertPushedOn('high', NotifyMercure::class);
    }
}