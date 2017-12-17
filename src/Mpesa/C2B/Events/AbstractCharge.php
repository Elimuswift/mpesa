<?php

namespace Elimuswift\Mpesa\C2B\Events;

use Elimuswift\Mpesa\C2B\Response\ChargeResponse;

abstract class AbstractCharge
{
    /**
     * Request received from mpesa server.
     *
     * @var ChargeResponse
     */
    public $resonse;

    /**
     * Create new event instance.
     *
     * @param ChargeResponse $result
     */
    public function __construct(ChargeResponse $result)
    {
        $this->response = $result;
    }
}
