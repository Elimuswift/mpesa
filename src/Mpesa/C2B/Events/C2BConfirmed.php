<?php

namespace Elimuswift\Mpesa\C2B\Events;

use Elimuswift\Mpesa\C2B\Response\Confirmation;

class C2BConfirmed
{
    public $resonse;

    public function __construct(Confirmation $confirmation)
    {
        $this->response = $confirmation;
    }
}
