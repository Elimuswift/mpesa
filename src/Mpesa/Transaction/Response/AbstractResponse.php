<?php

namespace Elimuswift\Mpesa\Transaction\Response;

abstract class AbstractResponse
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
            if (is_array($item)) {
                collect($item)->map(function ($items) use ($property) {
                    foreach ($items as $value) {
                        if (is_array($value)) {
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
                                    $this->key = array_key_exists('Key', $data) ? $data['Key'] : null;
                                    $this->value = array_key_exists('Value', $data) ? $data['Value'] : null;
                                    if (in_array($this->key, ['AccountBalance', 'DebitPartyCharges'])) {
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
                                    return collect(explode('&', $raw))
                                    ->transform(function ($account) {
                                        return explode('|', $account);
                                    });
                                }
                            };
                            $this->{lcfirst(str_replace(' ', '_', $item->key))} = $item->value;
                        }
                    }
                });
            } else {
                $this->{$property} = $item;
            }
        });
    }
}
