<?php

namespace Elimuswift\Mpesa\Transaction\Response;

class Reversal
{
    public $resultType;

    public $resultCode;

    public $resultDesc;

    public $originatorConversationID;

    public $conversationID;

    public $transactionID;

    public function __construct(array $data)
    {
        $requestData = collect($data);
        $requestData->map(function ($item, $key) {
            $property = lcfirst($key);
            if (!is_array($item)) {
                $this->{$property} = $item;
            }
        });
    }
}
