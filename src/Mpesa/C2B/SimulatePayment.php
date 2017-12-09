<?php

namespace Elimuswift\Mpesa\C2B;

use Elimuswift\Mpesa\Engine\Core;
use GuzzleHttp\Exception\RequestException;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;

class SimulatePayment
{
    protected $pushEndpoint;
    protected $engine;
    protected $number;
    protected $amount;
    protected $reference;

    /**
     * SimulatePayment constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->pushEndpoint = EndpointsRepository::build('mpesa/c2b/v1/simulate');
    }

    /**
     * Set the request amount to be deducted.
     *
     * @param int $amount
     *
     * @return $this
     */
    protected function request($amount)
    {
        if (!\is_numeric($amount)) {
            throw new \InvalidArgumentException('The amount must be numeric');
        }

        $this->amount = $amount;
    }

    /**
     * Set the Mobile Subscriber Number to deduct the amount from.
     * Must be in format 2547XXXXXXXX.
     *
     * @param int $number
     *
     * @return $this
     */
    protected function from($number)
    {
        if (!starts_with($number, '2547')) {
            throw new \InvalidArgumentException('The subscriber number must start with 2547');
        }

        $this->number = $number;
    }

    /**
     * Set the product reference number to bill the account.
     *
     * @param int    $reference
     * @param string $description
     *
     * @return $this
     */
    protected function usingReference($reference)
    {
        \preg_match('/[^A-Za-z0-9]/', $reference, $matches);

        if (\count($matches)) {
            throw new \InvalidArgumentException('Reference should be alphanumeric.');
        }

        $this->reference = $reference;
    }

    public function simulate($amount, $number, $reference)
    {
        $shortCode = $this->engine->config->get('mpesa.short_code');
        $this->request($amount);
        $this->from($number);
        $this->usingReference($reference);
        $body = [
            'ShortCode' => $shortCode,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $this->amount,
            'Msisdn' => $this->number,
            'BillRefNumber' => $this->reference,
        ];

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
        return $this->engine->client->request('POST', $this->pushEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer '.$this->engine->auth->authenticate(),
                'Content-Type' => 'application/json',
            ],
            'json' => $body,
        ]);
    }
}
