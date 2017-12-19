<?php

namespace Elimuswift\Mpesa;

use Elimuswift\Mpesa\C2B\Charge;
use Elimuswift\Mpesa\C2B\Registrar;
use Elimuswift\Mpesa\B2B\PayMerchant;
use Elimuswift\Mpesa\Transaction\Status;
use Elimuswift\Mpesa\C2B\SimulatePayment;
use Elimuswift\Mpesa\Transaction\Reversal;
use Elimuswift\Mpesa\Engine\Core as Mpesa;
use Elimuswift\Mpesa\Transaction\AccountBalance;

/**
 * Exposes the mpesa core API functionality.
 *
 * @author Leitato Albert <wizqydy@gmail.com>
 */
class Client
{
    /**
     * The Core Class.
     *
     * @var Mpesa
     **/
    protected $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * Expose the charge API.
     **/
    public function charge()
    {
        return new Charge($this->mpesa);
    }

    /**
     * Expose Transaction simulation on C2B API.
     **/
    public function simulate()
    {
        return new SimulatePayment($this->mpesa);
    }

    /**
     * Expose URL registrar on C2B API.
     **/
    public function registrar()
    {
        return new Registrar($this->mpesa);
    }

    /**
     * Expose the B2C API.
     **/
    public function b2c()
    {
        return new Payment($this->mpesa);
    }

    /**
     * Expose the B2B API.
     **/
    public function b2b()
    {
        return new PayMerchant($this->mpesa);
    }

    /**
     * Expose the Transaction reversal API.
     **/
    public function reversal()
    {
        return new Reversal($this->mpesa);
    }

    /**
     * Expose the Transaction Status API.
     **/
    public function transactionStatus()
    {
        return new Status($this->mpesa);
    }

    /**
     * Expose the Account Balance  API.
     **/
    public function accountBalance()
    {
        return new AccountBalance($this->mpesa);
    }
}
