<?php

namespace Elimuswift\Mpesa\C2B\Response;

final class Confirmation
{
    public $TransactionType;

    public $TransID;

    public $TransTime;

    public $TransAmount;

    public $BusinessShortCode;

    public $BillRefNumber;

    public $InvoiceNumber;

    public $OrgAccountBalance;

    public $ThirdPartyTransID;

    public $MSISDN;

    public $FirstName;

    public $MiddleName;

    public $LastName;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
