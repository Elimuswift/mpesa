<?php

namespace Elimuswift\Mpesa\Abstracts;

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
            $property = \lcfirst($key);
            if (\is_array($item)) {
                collect($item)->map(function ($items) use ($property) {
                    foreach ($items as $value) {
                        if (\is_array($value)) {
                            $item = new class($value) {
                                public $key;

                                public $value;

                                /**
                                 * Instantiate the anonymous class.
                                 *
                                 * @param array $data
                                 */
                                public function __construct($data)
                                {
                                    $this->key = \array_key_exists('Key', $data) ? $data['Key'] : null;
                                    $this->value = \array_key_exists('Value', $data) ? $data['Value'] : null;
                                    if (\in_array($this->key, ['DebitAccountCurrentBalance', 'InitiatorAccountCurrentBalance'])) {
                                        $this->value = $this->parseAmount($this->value);
                                    }
                                }

                                /**
                                 * Parse the amount string into an object.
                                 *
                                 * @param string $raw
                                 *
                                 * @return object
                                 **/
                                protected function parseAmount($raw)
                                {
                                    $str = \str_replace('=', ':', $raw);
                                    $amount = \json_decode(\preg_replace('/[a-zA-Z]+/', '"${0}"', $str));

                                    return \is_object($amount) ? $amount->Amount : new class() {
                                        public $BasicAmount;
                                        public $MinimumAmount;
                                        public $CurrencyCode;
                                    };
                                }
                            };
                            $this->{$property}[$item->key] = $item->value;
                        }
                    }
                });
            } else {
                $this->{$property} = $item;
            }
        });
    }
}
