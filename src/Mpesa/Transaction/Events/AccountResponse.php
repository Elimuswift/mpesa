<?php

namespace Elimuswift\Mpesa\Transaction\Events;

use Elimuswift\Mpesa\Transaction\Response\AccountBalance;

class AccountResponse
{
    public $response;

    public function __construct(AccountBalance $response)
    {
        $this->response = $response;
    }
}
