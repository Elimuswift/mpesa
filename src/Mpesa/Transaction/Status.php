<?php

namespace Elimuswift\Mpesa\Transaction;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

/**
 * @author Leitato Albert <wizqydy@gmail.com>
 */
class Status
{
    use MakesRequest;

    protected $endpoint;
    protected $engine;

    /**
     * Treansaction Status constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine   = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/transactionstatus/v1/query');
    }

    /**
     * Initiate  transaction status request transaction.
     *
     * @return mixed
     */
    public function check($transaction, $identifier = 1)
    {
        $paybill    = $this->engine->config->get('mpesa.paybill_number');
        $initiator  = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body       = [
            'Initiator'              => $initiator,
            'SecurityCredential'     => $credential,
            'CommandID'              => 'TransactionStatusQuery',
            'TransactionID'          => $transaction,
            'PartyA'                 => $paybill,
            'RecieverIdentifierType' => $identifier,
            'Remarks'                => 'Transaction Status request',
            'QueueTimeOutURL'        => $this->callback('mpesa.queue_timeout_callback'),
            'ResultURL'              => $this->callback('mpesa.status_result_url'),
        ];

        return $this->handleRequest($body);
    }
}
