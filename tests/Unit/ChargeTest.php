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

class ChargeTest extends TestCase
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
            new Response(202, [], \file_get_contents(__DIR__.'/../Fixtures/charge-response.json')),
        ]);
        $handler = HandlerStack::create($mock);
        $config = new NativeConfig();
        $cache = new NativeCache($config);
        $client = new Client(['handler' => $handler]);
        $engine = new Core($client, $config, $cache);
        $this->gateway = new Mpesa($engine);
    }

    /**
     * Test Mpesa B2C Charge.
     *
     * @covers \Elimuswift\Mpesa\C2B\Charge::create
     * @covers \Elimuswift\Mpesa\C2B\Charge::from
     * @covers \Elimuswift\Mpesa\C2B\Charge::usingReference
     * @test
     */
    public function testAChargeTransaction()
    {
        $charge = $this->gateway->charge();
        $response = $charge->request(1000)
                ->from(254700123456)
                ->usingReference('QW45FHG678', 'A test transaction')
                ->create();
        $this->assertEquals($response->CheckoutRequestID, 'ws_CO_27072017151044001');
    }

    /**
     * Test exception thown with invalid input.
     *
     * @expectedException \InvalidArgumentException
     * @covers \Elimuswift\Mpesa\C2B\Charge::request
     * @test
     */
    public function testItShouldFailWithInvalidAmount()
    {
        $this->gateway->charge()->request('dfvehi');
    }

    /**
     * Test exception thown with invalid input.
     *
     * @expectedException \InvalidArgumentException
     * @covers \Elimuswift\Mpesa\C2B\Charge::from
     * @test
     */
    public function testItShouldFailWithInvalidNumber()
    {
        $this->gateway->charge()->from('A sting');
    }

    /**
     * Test exception thown with invalid input.
     *
     * @expectedException \InvalidArgumentException
     * @covers \Elimuswift\Mpesa\C2B\Charge::usingReference
     * @test
     */
    public function testItShouldFailWithInvalidReference()
    {
        $this->gateway->charge()->usingReference('#$#%', 'A description');
    }
}
