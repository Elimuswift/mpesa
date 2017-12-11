<?php


Route::group(['prefix' => 'mpesa/callbacks', 'namespace' => 'Elimuswift\Mpesa\Laravel\Http\Controllers'], function () {
    Route::match(['get', 'post'], '/stk-push/response', 'MpesaWebHookController@processSTKPushRequestCallback')->name('mpesa.stk-push.callback');
    Route::match(['get', 'post'], 'confirmation/response', 'MpesaWebHookController@processC2BRequestConfirmation')->name('mpesa.transaction.callback');
});
