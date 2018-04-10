<?php

namespace Elimuswift\Mpesa\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Elimuswift\Mpesa\Tests\TestCase;
use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Client as Mpesa;
use Elimuswift\Mpesa\Native\NativeCache;
use Elimuswift\Mpesa\Native\NativeConfig;

class QueryStatusTest extends TestCase
{
    /**
     * The API transactor.
     *
     * @var Core
     */
    protected $gateway;

    /**
     * Set mock API response.
     *
     **/
    public function setUp()
    {
        $this->cleanCache();
        $mock = new MockHandler([
            new Response(202, [], \file_get_contents(__DIR__.'/../Fixtures/authentication.json')),
            new Response(202, [], \file_get_contents(__DIR__.'/../Fixtures/payment-status.json')),
        ]);
        $handler = HandlerStack::create($mock);
        $config = new NativeConfig();
        $cache = new NativeCache($config);
        $client = new Client(['handler' => $handler]);
        $engine = new Core($client, $config, $cache);
        $this->gateway = new Mpesa($engine);
    }

    /**
     * Test Mpesa B2C Transaction status.
     *
     * @covers \Elimuswift\Mpesa\C2B\QueryStatus::check
     * @test
     */
    public function testTransactionStatus()
    {
        $status = $this->gateway->transactionStatus();
        $response = $status->check('ws_CO_27072017151044001');
        $this->assertEquals($response->CheckoutRequestID, 'ws_CO_27072017151044001');
    }
}
