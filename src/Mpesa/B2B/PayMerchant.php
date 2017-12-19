<?php

namespace Elimuswift\Mpesa\B2B;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;
use Elimuswift\Mpesa\Generators\SecurityCredentialGenerator as Generator;

class PayMerchant
{
    use MakesRequest;

    protected $endpoint;
    protected $engine;
    protected $partyB;
    protected $amount;
    protected $remarks;
    protected $identifierType;
    protected $command = 'MerchantToMerchantTransfer';

    /**
     * Payment constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/b2b/v1/paymentrequest');
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

        $this->amount = (float) number_format($amount, 2);

        return $this;
    }

    /**
     * Unique command for each transaction.
     *
     * @param string $command
     **/
    public function occasion(string $command)
    {
        $commands = [
                        'BusinessPayBill',
                        'MerchantToMerchantTransfer',
                        'MerchantTransferFromMerchantToWorking',
                        'MerchantServicesMMFAccountTransfer',
                        'AgencyFloatAdvance',
                    ];
        if (in_array($command, $commands)) {
            throw new \InvalidArgumentException(" The CommandID {$command} is not supported for the B2B API");
        }
        $this->command = $command;

        return $this;
    }

    /**
     * Set the Merchant paybill number for the business receiving the money.
     *
     * @param int $number
     *
     * @return $this
     */
    public function toMerchant(int $merchantPaybill, int $identifierType = 4)
    {
        if (!in_array($identifierType, [1, 2, 4])) {
            throw new \InvalidArgumentException("The Identifier type $identifierType is not supported");
        }
        $this->partyB = $merchantPaybill;
        $this->identifierType = $identifierType;

        return $this;
    }

    /**
     * Set the remarks for the payment     *.
     *
     * @param string $remarks
     **/
    public function for(string $remarks)
    {
        if (!strlen($remarks) > 2) {
            throw new \InvalidArgumentException('The payment remarks must be at least three characters');
        }
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Initiate the B2B transaction.
     *
     * @return mixed
     */
    public function transact()
    {
        $paybill = $this->engine->config->get('mpesa.paybill_number');
        $initiator = $this->engine->config->get('mpesa.initiator');
        $credential = (new Generator($this->engine))->generate();
        $body = [
            'Initiator' => $initiator,
            'SecurityCredential' => $credential,
            'CommandID' => $this->command,
            'Amount' => $this->amount,
            'PartyA' => $paybill,
            'PartyB' => $this->partyB,
            'Remarks' => $this->remarks,
            'SenderIdentifierType' => $this->engine->config->get('mpesa.identifier_type') ?: 4,
            'RecieverIdentifierType' => $this->identifierType,
            'QueueTimeOutURL' => $this->callback('mpesa.queue_timeout_callback'),
            'ResultURL' => $this->callback('mpesa.b2b_result_url'),
        ];
        if ('BusinessPayBill' === $body['CommandID']) {
            if (null === $this->reference) {
                throw new \InvalidArgumentException('Account Reference is mandatory for “BusinessPaybill” CommandID.');
            }
            $body['AccountReference'] = $this->reference;
        }
        // dd($body);

        return $this->handleRequest($body);
    }
}
