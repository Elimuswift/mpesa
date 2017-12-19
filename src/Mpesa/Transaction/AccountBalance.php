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
        $this->engine   = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/accountbalance/v1/query');
    }

    /**
     * Initiate the account balance request transaction.
     *
     * @return mixed
     */
    public function request()
    {
        $paybill    = $this->engine->config->get('mpesa.paybill_number');
        $initiator  = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body       = [
            'Initiator'          => $initiator,
            'SecurityCredential' => $credential,
            'CommandID'          => 'AccountBalance',
            'PartyA'             => $paybill,
            'IdentifierType'     => '4',
            'Remarks'            => 'Account balance request',
            'QueueTimeOutURL'    => $this->callback('mpesa.queue_timeout_callback'),
            'ResultURL'          => $this->callback('mpesa.account_balance_result_url'),
        ];

        return $this->handleRequest($body);
    }
}
