<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

echo "You can test the following endpoints <p> \n\n";

echo "1. Authentication API - Generate Access token <p> \n\n";

echo "2. Reversal API - Reversal transaction <p> \n\n";

//Get access token  curl -X GET --header "Authorization: Basic TGkyZEtVZUtobFg2R3cwRnBrYnE2TEVCbmRscE91eFo6aFgzWXlkMEJHTUJpWWFsbg==" "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"


use Edwinmugendi\Mpesa\MpesaApi;

$mpesa_api = new MpesaApi();
$configs = array(
    'ConsumerKey' => 'Li2dKUeKhlX6Gw0Fpkbq6LEBndlpOuxZ',
    'ConsumerSecret' => 'hX3Yyd0BGMBiYaln',
    'Authorization' => 'Bearer vfKVB5ySRIkYMoMTgMJkULUX8ir5',
    'Environment' => 'sandbox',
    'Content-Type' => 'application/json',
);

$api = 'c2b_simulate';

if ($api == 'c2b_register_url') {
    $parameters = array(
        'ValidationURL' => 'https://192.241.213.216/mpesa_daraja_c2b_validate',
        'ConfirmationURL' => 'https://192.241.213.216/mpesa_daraja_c2b_confirm',
        'ResponseType' => 'Completed',
        'ShortCode' => '603013',
    );
} else if ($api == 'c2b_simulate') {

    $parameters = array(
        'CommandID' => 'CustomerPayBillOnline',
        'Amount' => '100',
        'Msisdn' => '254708374149',
        'BillRefNumber' => 'TESTING',
        'ShortCode' => '603013',
    );
} else if ($api == 'authenticate') {

    $parameters = array(
        'ConsumerKey' => 'Li2dKUeKhlX6Gw0Fpkbq6LEBndlpOuxZ',
        'ConsumerSecret' => 'hX3Yyd0BGMBiYaln',
    );
} else if ($api == 'reversal') {
    $parameters = array(
        'Initiator' => '',
        'SecurityCredential' => '',
        'CommandID' => '',
        'PartyA' => '',
        'RecieverIdentifierType' => '',
        'Remarks' => 'Reversal',
        'QueueTimeOutURL' => '',
        'ResultURL' => '',
        'TransactionID' => '',
        'Occasion' => '',
    );
}

$mpesa_api->call($api, $configs, $parameters);
