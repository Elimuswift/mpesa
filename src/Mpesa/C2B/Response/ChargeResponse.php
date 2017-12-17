<?php

namespace Elimuswift\Mpesa\C2B\Response;

final class ChargeResponse
{
    public $merchantRequestID;
    public $checkoutRequestID;
    public $resultCode;
    public $resultDesc;
    public $callbackMetadata = [];

    public function __construct(array $data)
    {
        collect($data)->map(function ($item, $key) {
            $property = lcfirst($key);
            if (is_array($item)) {
                collect($item)->map(function ($items) use ($property) {
                    foreach ($items as $value) {
                        $item = new class($value) {
                            public $name;

                            public $value;

                            public function __construct($data)
                            {
                                foreach ($data as $key => $foo) {
                                    $this->{lcfirst($key)} = $foo;
                                }
                            }
                        };
                        $this->{$property}[$item->name] = $item->value;
                    }
                });
            } else {
                $this->{$property} = $item;
            }
        });
    }
}
