<?php


Route::group(['prefix' => 'mpesa/callbacks', 'namespace' => 'Elimuswift\Mpesa\Laravel\Http\Controllers'], function () {
    Route::post('/stk-push/result', 'MpesaWebHookController@processOnlineCheckOutResult')
        ->name('mpesa.b2c.callback');
    Route::post('confirmation/result', 'MpesaWebHookController@processC2BRequestConfirmation')
        ->name('mpesa.confirmation.callback');
    Route::post('b2b/result', 'MpesaWebHookController@handleB2BPaymentResult')
         ->name('mpesa.b2b.callback');
    Route::post('b2c/result', 'MpesaWebHookController@handleB2CPayoutResult')
         ->name('mpesa.b2c.callback');
    Route::post('reversal/result', 'MpesaWebHookController@handleReversalResult')
         ->name('mpesa.reversal.callback');
    Route::post('status/result', 'MpesaWebHookController@handleTransactionStatusResult')
         ->name('mpesa.status.callback');
    Route::post('account-balance/result', 'MpesaWebHookController@handleAccountBalanceResult')
         ->name('mpesa.balance.callback');
    Route::post('request-timeout', 'MpesaWebHookController@requestTimeOut')
         ->name('mpesa.timeout.callback');
});
