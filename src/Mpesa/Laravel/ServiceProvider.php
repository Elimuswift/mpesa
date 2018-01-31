<?php

namespace Elimuswift\Mpesa\Laravel;

use GuzzleHttp\Client;
use Elimuswift\Mpesa\Client as Mpesa;
use Illuminate\Support\ServiceProvider as RootProvider;
use Elimuswift\Mpesa\C2B\Registrar;
use Elimuswift\Mpesa\Contracts\CacheStore;
use Elimuswift\Mpesa\Contracts\ConfigurationStore;
use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Laravel\Stores\LaravelCache;
use Elimuswift\Mpesa\Laravel\Stores\LaravelConfig;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleTor\Middleware;

class ServiceProvider extends RootProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../assets/config/mpesa.php' => config_path('mpesa.php'),
        ]);
        $this->publishes([
            __DIR__.'/../../../assets/storage/mpesa_public.key' => storage_path('mpesa_public.key'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    /**
     * Registrar the application services.
     */
    public function register()
    {
        $this->bindInstances();
        $this->registerFacades();
        $this->mergeConfigFrom(__DIR__.'/../../../assets/config/mpesa.php', 'mpesa');
    }

    private function bindInstances()
    {
        $this->app->bind(ConfigurationStore::class, LaravelConfig::class);
        $this->app->bind(CacheStore::class, LaravelCache::class);
        $this->app->bind(Core::class, function ($app) {
            $config = $app->make(ConfigurationStore::class);
            $cache = $app->make(CacheStore::class);
            $stack = new HandlerStack();
            $stack->setHandler(new CurlHandler());
            $stack->push(Middleware::tor());
            //$client = new Client(['handler' => $stack]);
            $client = new Client();
            
            return new Core($client, $config, $cache);
        });
    }

    private function registerFacades()
    {
        $this->app->bind('mpesa.client', function () {
            return $this->app->make(Mpesa::class);
        });
    }
}
