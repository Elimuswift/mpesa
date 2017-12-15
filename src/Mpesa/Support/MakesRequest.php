<?php

namespace Elimuswift\Mpesa\Support;

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

            return \json_decode($response->getBody());
        } catch (RequestException $exception) {
            return \json_decode($exception->getResponse()->getBody());
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
}
