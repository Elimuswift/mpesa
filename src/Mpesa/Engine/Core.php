<?php

namespace Elimuswift\Mpesa\Engine;

use GuzzleHttp\ClientInterface;
use Elimuswift\Mpesa\Auth\Authenticator;
use Elimuswift\Mpesa\Contracts\CacheStore;
use Elimuswift\Mpesa\Contracts\ConfigurationStore;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;

/**
 * Class Core.
 *
 * @category PHP
 *
 * @author   David Mjomba <smodavprivate@gmail.com>
 */
class Core
{
    /**
     * @var ConfigurationStore
     */
    public $config;

    /**
     * @var CacheStore
     */
    public $cache;

    /**
     * @var Core
     */
    public static $instance;

    /**
     * @var ClientInterface
     */
    public $client;

    /**
     * @var Authenticator
     */
    public $auth;

    /**
     * Core constructor.
     *
     * @param ClientInterface    $client
     * @param ConfigurationStore $configStore
     * @param CacheStore         $cacheStore
     */
    public function __construct(ClientInterface $client, ConfigurationStore $configStore, CacheStore $cacheStore)
    {
        $this->config = $configStore;
        $this->cache  = $cacheStore;
        $this->setClient($client);

        $this->initialize();

        self::$instance = $this;
    }

    /**
     * Initialize the Core process.
     */
    private function initialize()
    {
        new EndpointsRepository($this->config);
        $this->auth = new Authenticator($this);
    }

    /**
     * Set http client.
     *
     * @param ClientInterface $client
     **/
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}
