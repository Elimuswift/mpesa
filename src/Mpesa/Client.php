<?php

namespace Elimuswift\Mpesa;

use Elimuswift\Mpesa\C2B\Charge;
use Elimuswift\Mpesa\Engine\Core as Mpesa;

class Client
{
    /**
     * The Core Class
     * 	 *.
     *
     * @var Mpesa
     **/
    protected $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * undocumented function.
     *
     * @author
     **/
    public function charge()
    {
        return new Charge($this->mpesa);
    }
}
