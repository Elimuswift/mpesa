<?php

namespace Elimuswift\Mpesa\B2C\Events;

/*
* This Event Will be fired whenever a B2C Payment Result is intercepted
*/

use Elimuswift\Mpesa\B2C\Response\PaymentReceived;

class B2CResultReceived
{
    /**
     * PaymentReceived.
     *
     * @var PaymentReceived
     */
    public $response;

    public function __construct(PaymentReceived $response)
    {
        $this->response = $response;
    }
}
