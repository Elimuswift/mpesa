<?php

namespace Elimuswift\Mpesa\B2B\Events;

use Elimuswift\Mpesa\B2B\Response\PaymentProcessed;

class B2BPaymentProcessed
{
    /**
     * The resquest data from mpesa server.
     *
     * @var PaymentProcessed
     **/
    public $response;

    public function __construct(PaymentProcessed $response)
    {
        $this->response = $response;
    }
}
