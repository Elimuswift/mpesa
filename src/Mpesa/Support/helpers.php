<?php

use Elimuswift\Mpesa\Repositories\EndpointsRepository;

if (!\function_exists('mpesa_endpoint')) {
    /**
     * Build the mpesa endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     */
    function mpesa_endpoint($endpoint)
    {
        return EndpointsRepository::build($endpoint);
    }
}
if (!\function_exists('parse_account_balance')) {
    /**
     * Parse the  of the account balance response.
     *
     * @param string $balance [description]
     *
     * @return \Illuminate\Support\Collection
     */
    function parse_account_balance(string $balance)
    {
        return collect(\explode('&', $balance))
            ->transform(function ($account) {
                return \explode('|', $account);
            });
    }
}
