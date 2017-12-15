<?php

namespace Elimuswift\Mpesa\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Elimuswift\Mpesa\C2B\Events\C2BConfirmed;
use Elimuswift\Mpesa\C2B\Response\Confirmation;
use Illuminate\Routing\Controller as BaseController;

class MpesaWebHookController extends BaseController
{
    /**
     * Handle business to customer charge confirmation.
     *
     * @param Request $request
     **/
    public function handleB2CResult(Request $request)
    {
    }

    /**
     * Use this function to process the C2B Confirmation result callback.
     *
     * @return string
     */
    public function processC2BRequestConfirmation(Request $request)
    {
        $data = $request->all();
        $confirmation = new Confirmation($data);
        event(new C2BConfirmed($confirmation));
        $this->logRequest($data, "-------- C2B Confirmation Request --------\r\n");

        return response()->json(
            [
                'ResultCode' => 0,
                'ResultDesc' => 'Confirmation recieved successfully',
            ]
        );
    }

    /**
     * Log the incoming request to a log file.
     *
     * @param array  $data
     * @param string $occassion
     **/
    protected function logRequest(array $data, $occassion)
    {
        $file = fopen(storage_path('logs/mpesa.log'), 'a+');
        //log incoming request
        fwrite($file, $occassion);
        fwrite($file, json_encode($data));
        fwrite($file, "\r\n");
        fclose($file);
    }
}
