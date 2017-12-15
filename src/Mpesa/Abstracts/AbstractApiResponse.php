<?php

namespace Elimuswift\Mpesa\Abstracts;

use Illuminate\Support\Str;

abstract class AbstractApiResponse
{
    public $resultType;

    public $resultCode;

    public $resultDesc;

    public $originatorConversationID;

    public $conversationID;

    public $transactionID;

    public $resultParameters = [];

    public $referenceData = [];

    public function __construct(array $data)
    {
        $requestData = collect($data);
        $requestData->map(function ($item, $key) {
            $property = Str::camel($key);
            if (is_array($item)) {
                collect($item)->map(function ($items) use ($property) {
                    foreach ($items as $value) {
                        $item = (object) $value;
                        $this->{$property}[$item->Key] = property_exists($item, 'Value') ? $item->Value : null;
                    }
                });
            } else {
                $this->{$property} = $item;
            }
        });
    }
}
