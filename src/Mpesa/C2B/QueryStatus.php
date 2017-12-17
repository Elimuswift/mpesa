<?php

namespace Elimuswift\Mpesa\C2B;

use Carbon\Carbon;
use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Support\MakesRequest;
use Elimuswift\Mpesa\Repositories\EndpointsRepository;

class QueryStatus
{
    use MakesRequest;

    protected $engine;

    protected $endpoint;

    /**
     * QueryStatus constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
        $this->endpoint = EndpointsRepository::build('mpesa/stkpushquery/v1/query');
    }

    public function check($requestId)
    {
        $time = Carbon::now()->format('YmdHis');
        $shortCode = $this->engine->config->get('mpesa.short_code');
        $passkey = $this->engine->config->get('mpesa.passkey');
        $password = \base64_encode($shortCode.$passkey.$time);

        $body = [
            'BusinessShortCode' => $shortCode,
            'Password' => $password,
            'Timestamp' => $time,
            'CheckoutRequestID' => $requestId,
        ];

        return $this->handleRequest($body);
    }
}
