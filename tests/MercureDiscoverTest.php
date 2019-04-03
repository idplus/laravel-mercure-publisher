<?php
namespace Idplus\Mercure\Tests;

class MercureDiscoverTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        \Route::middleware('mercure.discover')->any('/discover', function () {
            return 'discover';
        });
    }

    /** @test */
    public function it_include_mercure_discover_header_link()
    {
        $response = $this->get('/discover');
        $response->assertHeader('Link');
        $this->assertEquals(1, preg_match("/mercure/", $response->headers->get('Link')));
    }
}