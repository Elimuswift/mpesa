<?php

namespace Elimuswift\Mpesa\Transaction\Events;

use Elimuswift\Mpesa\Transaction\Response\TransactionStatus;

class StatusResponse
{
    public $response;

    public function __construct(TransactionStatus $response)
    {
        $this->response = $response;
    }
}
