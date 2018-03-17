<?php

namespace Elimuswift\Mpesa\Support;

use GuzzleHttp\Exception\RequestException;

trait MakesRequest
{
    /**
     * Prepare and send HTTP request.
     *
     * @param array $body
     *
     * @return mixed API response
     **/
    protected function handleRequest($body = [])
    {
        try {
            $response = $this->makeRequest($body);

            return \json_decode($response->getBody()->getContents());
        } catch (RequestException $exception) {
            return \json_decode($exception->getResponse());
        }
    }

    /**
     * Initiate the request.
     *
     * @param array $body
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    private function makeRequest($body = [])
    {
        return $this->engine->client->request('POST', $this->endpoint, [
            'headers' => [
                'Authorization' => 'Bearer '.$this->engine->auth->authenticate(),
                'Content-Type' => 'application/json',
            ],
            'json' => $body,
        ]);
    }

    /**
     * Generate the result callback url.
     *
     * The generated url is based on the values provided in the configuration
     *
     * @param string $option
     *
     * @return string
     **/
    protected function callback($option)
    {
        $config = $this->engine->config;
        $callback = \trim($config->get($option), '/');
        if ($config->get('mpesa.default_callbacks')) {
            $endpoint = \trim($config->get('mpesa.callbacks_endpoint'), '/');
            $callback = "{$endpoint}/{$callback}";
        }

        return $callback;
    }
}
