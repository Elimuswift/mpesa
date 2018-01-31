<?php

namespace Elimuswift\Mpesa\B2C;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

class Payment
{
    use MakesRequest;

    protected $endpoint;
    protected $engine;
    protected $number;
    protected $amount;
    protected $details;

    /**
     * Payment constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine   = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/b2c/v1/paymentrequest');
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

        $this->amount =(float) \number_format($amount, 2);

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
        if (!\strlen($details) > 2) {
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
        $paybill    = $this->engine->config->get('mpesa.paybill_number');
        $initiator  = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body       = [
            'InitiatorName'      => $initiator,
            'SecurityCredential' => $credential,
            'CommandID'          => 'BusinessPayment',
            'Amount'             => $this->amount,
            'PartyA'             => $paybill,
            'PartyB'             => $this->number,
            'Remarks'            => $this->details,
            'QueueTimeOutURL'    => $this->callback('mpesa.b2c_timeout_url'),
            'ResultURL'          => $this->callback('mpesa.b2c_result_url'),
        ];

        return $this->handleRequest($body);
    }
}
