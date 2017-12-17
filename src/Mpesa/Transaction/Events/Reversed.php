<?php

namespace Elimuswift\Mpesa\Transaction\Events;

use Elimuswift\Mpesa\Transaction\Response\Reversal;

class Reversed
{
    public $resonse;

    public function __construct(Reversal $response)
    {
        $this->response = $response;
    }
}
