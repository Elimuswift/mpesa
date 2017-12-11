<?php

namespace Elimuswift\Mpesa\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MpesaWebHookController extends BaseController
{
    /**
     * undocumented function.
     *
     * @param Request $request
     **/
    public function handleB2CResult(Request $request)
    {
    }

    /**
     * Use this function to process the B2B request callback.
     *
     * @return string
     */
    public function processB2BRequestCallback(Request $request)
    {
        $requestData = $request->input();
        $resultCode = $requestData->Result->ResultCode;
        $resultDesc = $requestData->Result->ResultDesc;
        $originatorConversationID = $requestData->Result->OriginatorConversationID;
        $conversationID = $requestData->Result->ConversationID;
        $transactionID = $requestData->Result->TransactionID;
        $transactionReceipt = $requestData->Result->ResultParameters->ResultParameter[0]->Value;
        $transactionAmount = $requestData->Result->ResultParameters->ResultParameter[1]->Value;
        $b2CWorkingAccountAvailableFunds = $requestData->Result->ResultParameters->ResultParameter[2]->Value;
        $b2CUtilityAccountAvailableFunds = $requestData->Result->ResultParameters->ResultParameter[3]->Value;
        $transactionCompletedDateTime = $requestData->Result->ResultParameters->ResultParameter[4]->Value;
        $receiverPartyPublicName = $requestData->Result->ResultParameters->ResultParameter[5]->Value;
        $B2CChargesPaidAccountAvailableFunds = $requestData->Result->ResultParameters->ResultParameter[6]->Value;
        $B2CRecipientIsRegisteredCustomer = $requestData->Result->ResultParameters->ResultParameter[7]->Value;
        $result = [
          'resultCode' => $resultCode,
          'resultDesc' => $resultDesc,
            'originatorConversationID' => $originatorConversationID,
            'conversationID' => $conversationID,
            'transactionID' => $transactionID,
            'transactionReceipt' => $transactionReceipt,
            'transactionAmount' => $transactionAmount,
            'b2CWorkingAccountAvailableFunds' => $b2CWorkingAccountAvailableFunds,
            'b2CUtilityAccountAvailableFunds' => $b2CUtilityAccountAvailableFunds,
            'transactionCompletedDateTime' => $transactionCompletedDateTime,
            'receiverPartyPublicName' => $receiverPartyPublicName,
            'B2CChargesPaidAccountAvailableFunds' => $B2CChargesPaidAccountAvailableFunds,
            'B2CRecipientIsRegisteredCustomer' => $B2CRecipientIsRegisteredCustomer,
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the B2C request callback.
     *
     * @return string
     */
    public function processB2CRequestCallback(Request $request)
    {
        $callbackData = $request->input();
        $resultCode = $callbackData->Result->ResultCode;
        $resultDesc = $callbackData->Result->ResultDesc;
        $originatorConversationID = $callbackData->Result->OriginatorConversationID;
        $conversationID = $callbackData->Result->ConversationID;
        $transactionID = $callbackData->Result->TransactionID;
        $initiatorAccountCurrentBalance = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $debitAccountCurrentBalance = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
        $amount = $callbackData->Result->ResultParameters->ResultParameter[2]->Value;
        $debitPartyAffectedAccountBalance = $callbackData->Result->ResultParameters->ResultParameter[3]->Value;
        $transCompletedTime = $callbackData->Result->ResultParameters->ResultParameter[4]->Value;
        $debitPartyCharges = $callbackData->Result->ResultParameters->ResultParameter[5]->Value;
        $receiverPartyPublicName = $callbackData->Result->ResultParameters->ResultParameter[6]->Value;
        $currency = $callbackData->Result->ResultParameters->ResultParameter[7]->Value;
        $result = [
            'resultCode' => $resultCode,
            'resultDesc' => $resultDesc,
            'originatorConversationID' => $originatorConversationID,
            'conversationID' => $conversationID,
            'transactionID' => $transactionID,
            'initiatorAccountCurrentBalance' => $initiatorAccountCurrentBalance,
            'debitAccountCurrentBalance' => $debitAccountCurrentBalance,
            'amount' => $amount,
            'debitPartyAffectedAccountBalance' => $debitPartyAffectedAccountBalance,
            'transCompletedTime' => $transCompletedTime,
            'debitPartyCharges' => $debitPartyCharges,
            'receiverPartyPublicName' => $receiverPartyPublicName,
            'currency' => $currency,
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the C2B Validation request callback.
     *
     * @return string
     */
    protected function handleC2BRequest($request)
    {
        return [
            'transTime' => $request->TransTime,
            'transAmount' => $request->TransAmount,
            'businessShortCode' => $request->BusinessShortCode,
            'billRefNumber' => $request->BillRefNumber,
            'invoiceNumber' => $request->InvoiceNumber,
            'orgAccountBalance' => $request->OrgAccountBalance,
            'thirdPartyTransID' => $request->ThirdPartyTransID,
            'msisdn' => $request->MSISDN,
            'firstName' => $request->FirstName,
            'lastName' => $request->LastName,
            'middleName' => $request->MiddleName,
            'transID' => $request->TransID,
            'transactionType' => $request->TransactionType,
        ];
    }

    /**
     * Use this function to process the C2B Confirmation result callback.
     *
     * @return string
     */
    public function processC2BRequestConfirmation(Request $request)
    {
        $data = file_get_contents('php://input');
        \Log::log('critical', $data);
        file_put_contents(storage_path('logs/mpesa.log'), $data);

        return $request->all();
    }

    /**
     * Use this function to process the Account Balance request callback.
     *
     * @return string
     */
    public function processAccountBalanceRequestCallback()
    {
        $callbackJSONData = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSONData);
        $resultType = $callbackData->Result->ResultType;
        $resultCode = $callbackData->Result->ResultCode;
        $resultDesc = $callbackData->Result->ResultDesc;
        $originatorConversationID = $callbackData->Result->OriginatorConversationID;
        $conversationID = $callbackData->Result->ConversationID;
        $transactionID = $callbackData->Result->TransactionID;
        $accountBalance = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $BOCompletedTime = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
        $result = [
          'resultDesc' => $resultDesc,
          'resultCode' => $resultCode,
          'originatorConversationID' => $originatorConversationID,
          'conversationID' => $conversationID,
          'transactionID' => $transactionID,
          'accountBalance' => $accountBalance,
          'BOCompletedTime' => $BOCompletedTime,
          'resultType' => $resultType,
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the Reversal request callback.
     *
     * @return string
     */
    public function processReversalRequestCallBack()
    {
        $callbackJSONData = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSONData);
        $resultType = $callbackData->Result->ResultType;
        $resultCode = $callbackData->Result->ResultCode;
        $resultDesc = $callbackData->Result->ResultDesc;
        $originatorConversationID = $callbackData->Result->OriginatorConversationID;
        $conversationID = $callbackData->Result->ConversationID;
        $transactionID = $callbackData->Result->TransactionID;
        $result = [
          'resultType' => $resultType,
          'resultCode' => $resultCode,
          'resultDesc' => $resultDesc,
          'conversationID' => $conversationID,
          'transactionID' => $transactionID,
          'originatorConversationID' => $originatorConversationID,
        ];

        return json_encode($result);
    }

    /**
     * Use this function to process the STK push request callback.
     *
     * @return string
     */
    public function processSTKPushRequestCallback()
    {
        $callbackJSONData = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSONData);
        $resultCode = $callbackData->stkCallback->ResultCode;
        $resultDesc = $callbackData->stkCallback->ResultDesc;
        $merchantRequestID = $callbackData->stkCallback->MerchantRequestID;
        $checkoutRequestID = $callbackData->stkCallback->CheckoutRequestID;
        $amount = $callbackData->stkCallback->CallbackMetadata->Item[0]->Value;
        $mpesaReceiptNumber = $callbackData->stkCallback->CallbackMetadata->Item[1]->Value;
        $balance = $callbackData->stkCallback->CallbackMetadata->Item[2]->Value;
        $b2CUtilityAccountAvailableFunds = $callbackData->stkCallback->CallbackMetadata->Item[3]->Value;
        $transactionDate = $callbackData->stkCallback->CallbackMetadata->Item[4]->Value;
        $phoneNumber = $callbackData->stkCallback->CallbackMetadata->Item[5]->Value;
        $result = [
            'resultDesc' => $resultDesc,
            'resultCode' => $resultCode,
            'merchantRequestID' => $merchantRequestID,
            'checkoutRequestID' => $checkoutRequestID,
            'amount' => $amount,
            'mpesaReceiptNumber' => $mpesaReceiptNumber,
            'balance' => $balance,
            'b2CUtilityAccountAvailableFunds' => $b2CUtilityAccountAvailableFunds,
            'transactionDate' => $transactionDate,
            'phoneNumber' => $phoneNumber,
        ];
        $data = json_encode($result);
        file_put_contents(storage_path('logs/mpesa.log'), $data);

        return $data;
    }

    /**
     * Use this function to process the STK Push  request callback.
     *
     * @return string
     */
    public function processSTKPushQueryRequestCallback()
    {
        $callbackJSONData = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSONData);
        $responseCode = $callbackData->ResponseCode;
        $responseDescription = $callbackData->ResponseDescription;
        $merchantRequestID = $callbackData->MerchantRequestID;
        $checkoutRequestID = $callbackData->CheckoutRequestID;
        $resultCode = $callbackData->ResultCode;
        $resultDesc = $callbackData->ResultDesc;
        $result = [
            'resultCode' => $resultCode,
            'responseDescription' => $responseDescription,
            'responseCode' => $responseCode,
            'merchantRequestID' => $merchantRequestID,
            'checkoutRequestID' => $checkoutRequestID,
            'resultDesc' => $resultDesc,
        ];

        $data = json_encode($result);
        file_put_contents(storage_path('logs/mpesa.log'), $data);

        return $data;
    }

    /**
     * Use this function to process the Transaction status request callback.
     *
     * @return string
     */
    public function processTransactionStatusRequestCallback()
    {
        $callbackJSONData = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSONData);
        $resultCode = $callbackData->Result->ResultCode;
        $resultDesc = $callbackData->Result->ResultDesc;
        $originatorConversationID = $callbackData->Result->OriginatorConversationID;
        $conversationID = $callbackData->Result->ConversationID;
        $transactionID = $callbackData->Result->TransactionID;
        $ReceiptNo = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
        $ConversationID = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
        $FinalisedTime = $callbackData->Result->ResultParameters->ResultParameter[2]->Value;
        $Amount = $callbackData->Result->ResultParameters->ResultParameter[3]->Value;
        $TransactionStatus = $callbackData->Result->ResultParameters->ResultParameter[4]->Value;
        $ReasonType = $callbackData->Result->ResultParameters->ResultParameter[5]->Value;
        $TransactionReason = $callbackData->Result->ResultParameters->ResultParameter[6]->Value;
        $DebitPartyCharges = $callbackData->Result->ResultParameters->ResultParameter[7]->Value;
        $DebitAccountType = $callbackData->Result->ResultParameters->ResultParameter[8]->Value;
        $InitiatedTime = $callbackData->Result->ResultParameters->ResultParameter[9]->Value;
        $OriginatorConversationID = $callbackData->Result->ResultParameters->ResultParameter[10]->Value;
        $CreditPartyName = $callbackData->Result->ResultParameters->ResultParameter[11]->Value;
        $DebitPartyName = $callbackData->Result->ResultParameters->ResultParameter[12]->Value;
        $result = [
            'resultCode' => $resultCode,
            'resultDesc' => $resultDesc,
            'originatorConversationID' => $originatorConversationID,
            'conversationID' => $conversationID,
            'transactionID' => $transactionID,
            'ReceiptNo' => $ReceiptNo,
            'ConversationID' => $ConversationID,
            'FinalisedTime' => $FinalisedTime,
            'Amount' => $Amount,
            'TransactionStatus' => $TransactionStatus,
            'ReasonType' => $ReasonType,
            'TransactionReason' => $TransactionReason,
            'DebitPartyCharges' => $DebitPartyCharges,
            'DebitAccountType' => $DebitAccountType,
            'InitiatedTime' => $InitiatedTime,
            'OriginatorConversationID' => $OriginatorConversationID,
            'CreditPartyName' => $CreditPartyName,
            'DebitPartyName' => $DebitPartyName,
        ];

        return json_encode($result);
    }
}
