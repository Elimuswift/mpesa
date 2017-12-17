<?php

namespace Elimuswift\Mpesa\Transaction\Response;

class TransactionStatus extends AbstractResponse
{
    public $receiptNo;

    public $conversation_ID;

    public $finalisedTime;

    public $amount;

    public $transactionStatus;

    public $reasonType;

    public $transactionReason;

    public $debitPartyCharges = [];

    public $debitAccountType;

    public $initiatedTime;

    public $originator_Conversation_ID;

    public $creditPartyName;

    public $ocassion;

    public $debitPartyName;
}
