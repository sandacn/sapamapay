<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Edwinmugendi\Mpesa\MpesaApi;

$mpesa_api = new MpesaApi();

$configs = array(
    'ConsumerKey' => 'Li2dKUeKhlX6Gw0Fpkbq6LEBndlpOuxZ',
    'ConsumerSecret' => 'hX3Yyd0BGMBiYaln',
    'AccessToken' => 'PYOnZcSDEunmA8hmaWnGfsAHgopD',
    '' => '',
    '' => '',
    'Environment' => 'sandbox',
);

$api = 'reversal';

$parameters = array(
    'Initiator' => '',
    'SecurityCredential' => '',
    'CommandID' => '',
    'PartyA' => '',
    'RecieverIdentifierType' => '',
    'Remarks' => '',
    'QueueTimeOutURL' => '',
    'ResultURL' => '',
    'TransactionID' => '',
    'Occasion' => '',
);

$mpesa_api->call($api, $configs, $parameters);
