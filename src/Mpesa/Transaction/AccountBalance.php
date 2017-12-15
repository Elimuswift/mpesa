<?php

namespace Elimuswift\Mpesa\Transaction;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

/**
 * @author Leitato Albert <wizqydy@gmail.com>
 */
class AccountBalance
{
    use MakesRequest;

    protected $endpoint;
    protected $engine;

    /**
     * AccountBalance constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/accountbalance/v1/query');
    }

    /**
     * Initiate the account balance request transaction.
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
            'CommandID' => 'AccountBalance',
            'PartyA' => $paybill,
            'IdentifierType' => '4',
            'Remarks' => 'Account balance request',
            'QueueTimeOutURL' => $this->engine->config->get('mpesa.b2c_timeout_url'),
            'ResultURL' => $this->engine->config->get('mpesa.b2c_result_url'),
        ];

        return $this->handleRequest($body);
    }
}
