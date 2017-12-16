<?php

namespace Elimuswift\Mpesa\Transaction;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

/**
 * @author Leitato Albert <wizqydy@gmail.com>
 */
class Reversal
{
    use MakesRequest;

    protected $endpoint;
    protected $engine;

    /**
     * Treansaction Reversal constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/reversal/v1/request');
    }

    /**
     * Initiate  transaction reversal request transaction.
     *
     * @return mixed
     */
    public function reverse($transaction, $number, $amount)
    {
        $paybill = $this->engine->config->get('mpesa.paybill_number');
        $initiator = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body = [
            'Initiator' => $initiator,
            'SecurityCredential' => $credential,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transaction,
            'Amount' => $amount,
            'ReceiverParty' => $number,
            'RecieverIdentifierType' => '4',
            'Remarks' => 'Transaction reversal request',
            'QueueTimeOutURL' => $this->engine->config->get('mpesa.queue_timeout_callback'),
            'ResultURL' => $this->engine->config->get('mpesa.reversal_result_url'),
        ];

        return $this->handleRequest($body);
    }
}
