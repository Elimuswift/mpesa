<?php

namespace Elimuswift\Mpesa\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Elimuswift\Mpesa\C2B\Events\ChargeFailed;
use Elimuswift\Mpesa\C2B\Events\ChargeSuccess;
use Elimuswift\Mpesa\C2B\Events\C2BConfirmed;
use Elimuswift\Mpesa\C2B\Response\Confirmation;
use Elimuswift\Mpesa\C2B\Response\ChargeResponse;
use Elimuswift\Mpesa\B2B\Response\PaymentProcessed;
use Elimuswift\Mpesa\B2C\Response\PaymentReceived;
use Elimuswift\Mpesa\B2B\Events\B2BPaymentProcessed;
use Elimuswift\Mpesa\B2C\Events\B2CResultReceived;
use Elimuswift\Mpesa\Transaction\Events\Reversed;
use Elimuswift\Mpesa\Transaction\Response\Reversal;
use Illuminate\Routing\Controller as BaseController;
use Elimuswift\Mpesa\Transaction\Events\StatusResponse;
use Elimuswift\Mpesa\Transaction\Events\AccountResponse;
use Elimuswift\Mpesa\Transaction\Response\AccountBalance;
use Elimuswift\Mpesa\Transaction\Response\TransactionStatus;

class MpesaWebHookController extends BaseController
{
    /**
     * Handle business to customer charge confirmation.
     *
     * @param Request $request
     **/
    public function handleB2CPayoutResult(Request $request)
    {
        $data = $request->input('Result', []);
        $result = new PaymentReceived($data);
        event(new B2CResultReceived($result));
        $this->logRequest($data, "-------- B2C Payment Result Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handle business to business payment confirmation request.
     *
     * @param Request $request
     **/
    public function handleB2BPaymentResult(Request $request)
    {
        $data = $request->input('Result', []);
        $result = new PaymentProcessed($data);
        event(new B2BPaymentProcessed($result));
        $this->logRequest($data, "-------- B2B Payment Confirmation Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handle transaction status response.
     *
     * @param Request $request
     **/
    public function handleTransactionStatusResult(Request $request)
    {
        $data = $request->input('Result', []);
        $result = new TransactionStatus($data);
        event(new StatusResponse($result));
        $this->logRequest($data, "-------- Transaction Status Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handle account balance response.
     *
     * @param Request $request
     **/
    public function handleAccountBalanceResult(Request $request)
    {
        $data = $request->input('Result', []);
        $result = new AccountBalance($data);
        event(new AccountResponse($result));
        $this->logRequest($data, "-------- Account Balance Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handle transaction status response.
     *
     * @param Request $request
     **/
    public function handleReversalResult(Request $request)
    {
        $data = $request->input('Result', []);
        $result = new Reversal($data);
        event(new Reversed($result));
        $this->logRequest($data, "-------- Transaction Reversal Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Use this function to process the C2B Confirmation result callback.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function processC2BRequestConfirmation(Request $request)
    {
        $data = $request->input();
        $confirmation = new Confirmation($data);
        event(new C2BConfirmed($confirmation));
        $this->logRequest($data, "-------- C2B Confirmation Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handles Mpesa Online checkout stk push request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function processOnlineCheckOutResult(Request $request)
    {
        $data = $request->input('Body.stkCallback', []);
        $result = new ChargeResponse($data);
        if (0 !== $result->resultCode) {
            event(new ChargeFailed($result));
        } else {
            event(new ChargeSuccess($result));
        }
        $this->logRequest($data, "-------- Online Payment Charge Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Handles Mpesa Transaction timeoutrequest.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function requestTimeOut(Request $request)
    {
        $data = $request->input();
        $this->logRequest($data, "-------- Request Time Out Request --------\r\n");

        return $this->responseResult();
    }

    /**
     * Log the incoming request to a log file.
     *
     * @param array  $data
     * @param string $occassion
     **/
    protected function logRequest(array $data, $occassion)
    {
        $file = \fopen(storage_path('logs/mpesa.log'), 'a+');
        //log incoming request
        \fwrite($file, $occassion);
        \fwrite($file, \json_encode($data));
        \fwrite($file, "\r\n");
        \fclose($file);
    }

    /**
     * Tell Mpesa Server the request was received.
     *
     * @return \Illuminate\Http\Response
     **/
    protected function responseResult()
    {
        return response()->json(
            [
                'ResultCode' => 0,
                'ResultDesc' => 'Confirmation recieved successfully',
            ]
        );
    }
}
