<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    |
    | This determines the state of the package, whether to use the sandbox or not.
    |
    | Possible values: sandbox | production
    */

    'status' => 'sandbox',

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | These are the credentials to be used to transact with the M-Pesa API
    */

    'consumer_key' => env('MPESA_CONSUMER_KEY', ''),

    'consumer_secret' => env('MPESA_SECRET_KEY', ''),

    'production_endpoint' => '',

    'initiator' => env('MPESA_INITIATOR', 'apitest336'),

    'initiator_password' => env('MPESA_INITIATOR_PASSWORD', '336reset'),

    /*
    |--------------------------------------------------------------------------
    | Public Key
    |--------------------------------------------------------------------------
    |
    | The absolute path to safaricom's public key certificate.
    | This key is used to generate the security credetial for transactions
    |
    |
     */

    'public_key' => __DIR__.'/../storage/mpesa_public.key',

    /*
    |--------------------------------------------------------------------------
    | File Cache Location
    |--------------------------------------------------------------------------
    |
    | This will be the location on the disk where the caching will be done.
    |
    */

    'cache_location' => 'cache',

    /*
    |-------------------------------------------------------------------------
    | Result Callback Endpoint
    |-------------------------------------------------------------------------
    |
    | This option enables the use of the callbacks provided by this package
    | If you want to implement your own callbacks feel free to change
    | 'default_callbacks' => false
    | and add a fully qualified domain name to the callbacks for example
    | 'stk_callback' => 'https://my-domain.com/callbacks/stk-push'
    |
    | The default behaviour of the package is that it appends the callback urls
    | to the URL provided on the 'callbacks_endpoint' config value
    |
     */
    'default_callbacks' => true,
    'callbacks_endpoint' => env('MPESA_CALLBACKS_ENDPOINT', 'https://sandbox.localtunnel.me'),

   /*
    |--------------------------------------------------------------------------
    | STK Callback URL
    |--------------------------------------------------------------------------
    |
    | This is a fully qualified endpoint that will be be queried by Safaricom's
    | API on completion or failure of the transaction.
    |
    */

    'stk_callback' => 'mpesa/callbacks/stk-push/result',

    /*
    |--------------------------------------------------------------------------
    | Identity Validation Callback URL
    |--------------------------------------------------------------------------
    |
    | This is a fully qualified endpoint that will be be queried by Safaricom's
    | API on completion or failure of the transaction.
    |
    */

    'id_validation_callback' => 'mpesa/callbacks/confirmation/result',

    /*
    |--------------------------------------------------------------------------
    | Callback Method
    |--------------------------------------------------------------------------
    |
    | This is the request method to be used on the Callback URL on communication
    | with your server.
    |
    | e.g. GET | POST
    |
    */

    'callback_method' => 'POST',

    /*
    |--------------------------------------------------------------------------
    | Paybill Number
    |--------------------------------------------------------------------------
    |
    | This is a registered Paybill Number that will be used as the Merchant ID
    | on every transaction. This is also the account to be debited.
    |
    |
    |
    */
    'paybill_number' => env('MPESA_PAYBILL', 600336),

    /*
    |-------------------------------------------------------------------------
    | Online Short Code
    |-------------------------------------------------------------------------
    | Online payments paybill number
    |
     */

    'short_code' => env('MPESA_ONLINE', 174379),

    /*
    |--------------------------------------------------------------------------
    | SAG Passkey
    |--------------------------------------------------------------------------
    |
    | This is the secret SAG Passkey generated by Safaricom on registration
    | of the Merchant's Paybill Number.
    |
    */

    'passkey' => env('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),

    /*
    |--------------------------------------------------------------------------
    | Result URL Endpoints
    |--------------------------------------------------------------------------
    |
    | These endpoints  will be used by mpesa to send responses after a transaction has been proccessed
    | A request will be sent to these endpoints with regard to the API
    |
    |
    */

    'account_balance_result_url' => 'mpesa/callbacks/account-balance/result',
    'b2c_result_url' => 'mpesa/callbacks/b2c/result',
    'b2b_result_url' => 'mpesa/callbacks/b2b/result',
    'reversal_result_url' => 'mpesa/callbacks/reversal/result',
    'status_result_url' => 'mpesa/callbacks/status/result',

    /*
    |-------------------------------------------------------------------------
    | Queue Timeout URL
    |-------------------------------------------------------------------------
    |
    | This endpoint will receive a POST request if a timeout is encontered while attempting to proccess
    | an Mpesa request
    |
     */
    'queue_timeout_callback' => 'mpesa/callbacks/request-timeout',
];
