<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Edwinmugendi\Mpesa\MpesaApi;

$mpesa_api = new MpesaApi();
$configs = array(
    'Authorization' => 'Bearer 16OGQOeQO6kImJAasXaA0I9GM1Ez',
    'Environment' => 'live',
    'Content-Type' => 'application/json',
    'Url' => '',
);
$configs = array(
    'Authorization' => 'Bearer Vflc8G3sj7sZP1UE60GfyqKlpQAI',
    'Environment' => 'sandbox',
    'Content-Type' => 'application/json',
    'Verbose' => '',
);

$api = 'stk_push';

if ($api == 'stk_push') {
    $parameters = array(
        'BusinessShortCode' => '603013',
        'Password' => 'TkNZpjhQ',
        'Timestamp' => '20171010101010',
        'TransactionType' => 'TransactionType',
        'Amount' => '10',
        'PartyA' => '254708374149',
        'PartyB' => '603013',
        'PhoneNumber' => '254708374149',
        'CallBackURL' => 'http://sapama.com',
        'AccountReference' => '1232',
        'TransactionDesc' => 'TESTING',
    );
} else if ($api == 'stk_query') {
    $parameters = array(
        'BusinessShortCode' => '603013',
        'Password' => 'TkNZpjhQ',
        'Timestamp' => '20171010101010',
        'CheckoutRequestID' => 'ws_co_123456789',
    );
} else if ($api == 'account_balance') {
    $parameters = array(
        'CommandID' => 'AccountBalance',
        'PartyA' => '603013',
        'IdentifierType' => '4',
        'Remarks' => 'Remarks',
        'Initiator' => 'apiop41',
        'SecurityCredential' => 'TkNZpjhQ',
        'QueueTimeOutURL' => 'http://sapama.com',
        'ResultURL' => 'http://sapama.com',
    );
} else if ($api == 'b2b_payment_request') {
    $parameters = array(
        'CommandID' => 'BusinessPayBill',
        'Amount' => '10',
        'PartyA' => '603013',
        'SenderIdentifierType' => '4',
        'PartyB' => '600000',
        'RecieverIdentifierType' => '4',
        'Remarks' => 'Remarks',
        'Initiator' => 'apiop41',
        'SecurityCredential' => 'TkNZpjhQ',
        'QueueTimeOutURL' => 'http://sapama.com',
        'ResultURL' => 'http://sapama.com',
        'AccountReference' => '12',
    );
} else if ($api == 'b2c_payment_request') {
    $parameters = array(
        'InitiatorName' => 'apiop41',
        'SecurityCredential' => 'TkNZpjhQ',
        'CommandID' => 'SalaryPayment',
        'Amount' => '10',
        'PartyA' => '603013',
        'PartyB' => '254708374149',
        'Remarks' => 'Remarks',
        'QueueTimeOutURL' => 'http://sapama.com',
        'ResultURL' => 'http://sapama.com',
        'Occasion' => '12',
    );
} else if ($api == 'reversal') {
    $parameters = array(
        'CommandID' => 'TransactionReversal',
        'ReceiverParty' => '254708374149',
        'RecieverIdentifierType' => '1',
        'Remarks' => 'remarks',
        'Initiator' => 'apiop41',
        'SecurityCredential' => 'TkNZpjhQ',
        'QueueTimeOutURL' => 'http://sapama.com',
        'ResultURL' => 'http://sapama.com',
        'TransactionID' => '11211',
        'Occasion' => '12',
        'Amount' => '10',
    );
} else if ($api == 'transaction_status_request') {
    $parameters = array(
        'CommandID' => 'TransactionStatusQuery',
        'PartyA' => '254708374149',
        'IdentifierType' => '603013',
        'Remarks' => 'remarks',
        'Initiator' => 'apiop41',
        'SecurityCredential' => 'TkNZpjhQ',
        'QueueTimeOutURL' => 'http://sapama.com',
        'ResultURL' => 'http://sapama.com',
        'TransactionID' => '11211',
        'Occasion' => '12',
    );
} else if ($api == 'c2b_register_url') {
    $parameters = array(
        'ValidationURL' => 'https://192.241.213.216/mpesa_daraja_c2b_validate',
        'ConfirmationURL' => 'https://192.241.213.216/mpesa_daraja_c2b_confirm',
        'ResponseType' => 'Completed',
        'ShortCode' => '711693',
    );
} else if ($api == 'c2b_simulate') {

    $parameters = array(
        'CommandID' => 'CustomerPayBillOnline',
        'Amount' => '100',
        'Msisdn' => '254708374149',
        'BillRefNumber' => 'TESTING',
        'ShortCode' => '603013',
    );
} else if ($api == 'generate_token') {

    $parameters = array(
        'ConsumerKey' => 'Li2dKUeKhlX6Gw0Fpkbq6LEBndlpOuxZ',
        'ConsumerSecret' => 'hX3Yyd0BGMBiYaln',
    );
}//E# if statement

$response = $mpesa_api->call($api, $configs, $parameters);
echo 'JSON response: <p>';
echo json_encode($response);
echo '<p>Response var_dump:<p>';
var_dump($response);
