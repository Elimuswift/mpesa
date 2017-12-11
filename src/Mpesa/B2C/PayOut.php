<?php

namespace Elimuswift\Mpesa\B2C;

use Elimuswift\Mpesa\Engine\Core;
use GuzzleHttp\Exception\RequestException;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

class PayOut
{
    protected $pushEndpoint;
    protected $engine;
    protected $number;
    protected $amount;
    protected $details;

    /**
     * PayOut constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->pushEndpoint = EndpointsRepository::build('mpesa/c2b/v1/simulate');
    }

    /**
     * Set the payout amount to be deducted.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function pay(float $amount)
    {
        if (!\is_numeric($amount)) {
            throw new \InvalidArgumentException('The amount must be numeric');
        }

        $this->amount = number_format($amount, 2);

        return $this;
    }

    /**
     * Set the Mobile Subscriber Number to receive the money.
     * Must be in format 2547XXXXXXXX.
     *
     * @param int $number
     *
     * @return $this
     */
    public function to(int $number)
    {
        if (!starts_with($number, '2547')) {
            throw new \InvalidArgumentException('The subscriber number must start with 2547');
        }

        $this->number = $number;

        return $this;
    }

    /**
     * Set the remarks for the payment     *.
     *
     * @param string $details
     **/
    public function for(string $details)
    {
        if (!strlen($details) > 2) {
            throw new \InvalidArgumentException('The payment remarks must be at least three characters');
        }
        $this->details = $details;

        return $this;
    }

    /**
     * Initiate the B2C transaction.
     *
     * @return mixed
     */
    public function transact()
    {
        $paybill = $this->engine->config->get('mpesa.pay_bill');
        $initiator = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body = [
            'InitiatorName' => $initiator,
            'SecurityCredential' => $credential,
            'CommandID' => 'BusinessPayment',
            'Amount' => $this->amount,
            'PartyA' => $paybill,
            'PartyB' => $this->number,
            'Remarks' => $this->details,
            'QueueTimeOutURL' => 'http://your_timeout_url',
            'ResultURL' => 'http://your_result_url',
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
