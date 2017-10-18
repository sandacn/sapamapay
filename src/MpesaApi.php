<?php

namespace Edwinmugendi\Mpesa;

/**
 * 
 * Safaricom Mpesa API wrapper
 * 
 * @author Edwin Mugendi <edwinmugendi@gmail.com>
 */
class MpesaApi {

    private $configs;
    private $response;
    private $environment;
    private $sandbox_endpoint = 'https://sandbox.safaricom.co.ke/';
    private $live_endpoint = 'https://sandbox.safaricom.co.ke/';
    private $endpoint = '';
    private $parameters = array();
    private $actual_api = array();
    private $apis = array(
        'c2b_simulate' => array(
            'name' => 'C2B Simulate Transaction',
            'description' => 'Simulate a C2B transaction',
            'endpoint' => 'mpesa/c2b/v1/simulate',
            'type' => 'post',
            'parameters' => array(
                'CommandID' => array(
                    'name' => 'Unique command for each transaction type. For C2B dafult ',
                    'required' => true,
                    'type' => 'String',
                    'possible_value' => '- CustomerPayBillOnline - CustomerBuyGoodsOnline',
                ),
                'Amount' => array(
                    'name' => 'The amount being transacted',
                    'required' => true,
                    'type' => 'Numeric',
                    'possible_value' => '1',
                ),
                'Msisdn' => array(
                    'name' => 'Phone number (msisdn) initiating the transaction',
                    'required' => true,
                    'type' => 'Numeric',
                    'possible_value' => 'MSISDN(12 digits) - 254XXXXXXXXX',
                ),
                'BillRefNumber' => array(
                    'name' => 'Bill Reference Number (Optional)',
                    'required' => false,
                    'type' => 'Alpha-Numeric',
                    'possible_value' => 'Alpha-Numeric less then 20 digits ',
                ),
                'ShortCode' => array(
                    'name' => 'Short Code receiving the amount being transacted',
                    'required' => true,
                    'type' => 'Numeric',
                    'possible_value' => 'Shortcode (6 digits) - XXXXXX',
                ),
            ),
            'response' => array(
                'OriginatorConverstionID' => array(
                    'name' => 'The unique request ID for tracking a transaction',
                    'type' => 'Alpha-Numeric',
                    'sample_value' => 'Alpha-numeric string of less then 20 characters',
                ),
                'ConversationID' => array(
                    'name' => 'The unique request ID returned by mpesa for each request made',
                    'type' => 'Alpha-Numeric',
                    'sample_value' => '- Error codes - 500 OK',
                ),
                'ResponseDescription' => array(
                    'name' => 'Response Description message',
                    'type' => 'String',
                    'sample_value' => '- The service request has failed - The service request has been accepted successfully',
                ),
            )
        ),
        'c2b_register_url' => array(
            'name' => 'C2B Register URL',
            'description' => 'Register validation and confirmation URLs on M-Pesa ',
            'endpoint' => 'mpesa/c2b/v1/registerurl',
            'type' => 'post',
            'parameters' => array(
                'ValidationURL' => array(
                    'name' => 'Validation URL for the client',
                    'required' => true,
                    'type' => 'URL',
                    'possible_value' => 'https://ip or domain:port/path',
                ),
                'ConfirmationURL' => array(
                    'name' => 'Confirmation URL for the client',
                    'required' => true,
                    'type' => 'URL',
                    'possible_value' => 'https://ip or domain:port/path',
                ),
                'ResponseType' => array(
                    'name' => 'Default response type for timeout. Incase a tranaction times out, Mpesa will by default Complete or Cancel the transaction',
                    'required' => true,
                    'type' => 'String',
                    'possible_value' => 'Completed',
                ),
                'ShortCode' => array(
                    'name' => 'The short code of the organization.',
                    'required' => true,
                    'type' => 'Numeric',
                    'possible_value' => '123456',
                ),
            ),
            'response' => array(
                'OriginatorConverstionID' => array(
                    'name' => 'The unique request ID for tracking a transaction',
                    'type' => 'Alpha-Numeric',
                    'sample_value' => 'Alpha-numeric string of less then 20 characters',
                ),
                'ConversationID' => array(
                    'name' => 'The unique request ID returned by mpesa for each request made',
                    'type' => 'Alpha-Numeric',
                    'sample_value' => '- Error codes - 500 OK',
                ),
                'ResponseDescription' => array(
                    'name' => 'Response Description message',
                    'type' => 'String',
                    'sample_value' => '- The service request has failed - The service request has been accepted successfully',
                ),
            )
        ),
        'reversal' => array(
            'name' => 'Reversal',
            'description' => 'Reverses a B2B, B2C or C2B M-Pesa transaction.',
            'endpoint' => 'mpesa/reversal/v1/request',
            'type' => 'post',
            'parameters' => array(
                'Initiator' => array(
                    'name' => 'This is the credential/username used to authenticate the transaction request.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'SecurityCredential' => array(
                    'name' => 'Base256 encrypted string of the M-Pesa short code and password that validates the transaction on the M-Pesa system.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'CommandID' => array(
                    'name' => 'Unique command for each transaction type, possible values are: TransactionReversal.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'PartyA' => array(
                    'name' => 'Organization/MSISDN sending the transaction.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'RecieverIdentifierType' => array(
                    'name' => 'Type of organization receiving the transaction.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'Remarks' => array(
                    'name' => 'Comments that are sent along with the transaction.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'QueueTimeOutURL' => array(
                    'name' => 'The path that stores information of time out transaction.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'ResultURL' => array(
                    'name' => 'The path that stores information of transaction.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'TransactionID' => array(
                    'name' => 'Organization Receiving the funds.',
                    'required' => true,
                    'type' => '',
                    'possible_value' => '',
                ),
                'Occasion' => array(
                    'name' => 'Occasion',
                    'required' => false,
                    'type' => '',
                    'possible_value' => '',
                ),
            )
        ),
        'authenticate' => array(
            'name' => 'Authenticate',
            'description' => 'OAuth Authentication',
            'endpoint' => 'oauth/v1/generate',
            'type' => 'get',
            'parameters' => array(
                'ConsumerKey' => array(
                    'name' => 'Consumer Key',
                    'required' => true,
                ),
                'ConsumerSecret' => array(
                    'name' => 'Consumer Secret',
                    'required' => true,
                ),
            )
        ),
    );
    private $http_status_code = array(
        200 => 'Success',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable – You requested a format that isn’t json',
        429 => 'Too Many Requests – You’re requesting too many kittens! Slow down!',
        500 => 'Internal Server Error – We had a problem with our server. Try again later.',
        503 => 'Service Unavailable – We’re temporarily offline for maintenance. Please try again later.'
    );

    /**
     * S# __construct() function
     *  
     * Constructor
     * 
     */
    public function __construct() {
        
    }

//E# __construct() function

    /**
     * S# call() function
     *  
     * Constructor
     * 
     */
    public function call($api, $configs, $parameters = array()) {

        $this->parameters = $parameters;

        //Check environment
        if (!array_key_exists('Environment', $configs) || in_array($configs['Environment'], array('sandbox', 'live'))) {
            $this->respond(400, 'Environment should be either "live" or "sandbox"', array(), array());
        }//E# if else statement
        //Check api
        if (!array_key_exists($api, $this->apis)) {
            $this->respond(400, $api . ' API does not exists', array(), array());
        }//E# if else statement



        echo 'Parameters <p>';
        var_dump($parameters);
        $parameter_indexes = array();
        if (array_key_exists('parameters', $this->apis[$api])) {
            foreach ($this->apis[$api]['parameters'] as $single_parameter) {
                if ($single_parameter['required'] && !array_key_exists($single_parameter['name'], $parameters)) {
                    $this->respond(400, $api . 'Parameter ' . $single_parameter['name'] . ' is required', array(), array());
                }//E# if statement
            }//E# foreach statement
        }//E# if else statement
        //Clean parameters

        if ($configs['Environment'] == 'sandbox') {
            $this->endpoint = $this->sandbox_endpoint;
        } else if ($configs['Environment'] == 'live') {
            $this->endpoint = $this->live_endpoint;
        }//E# if else statement
        //Set api
        $this->actual_api = $this->apis[$api];

        //Verbose
        if (array_key_exists('verbose', $configs)) {
            echo 'API Name: ' . $api . '<p>';
            echo 'Configs <p>';
            var_dump($configs);
            echo 'Parameters <p>';
            var_dump($parameters);
        }//E# if statement

        return $this->request($configs, $parameters);
    }

//E# call() function

    /**
     * S# request() function 
     * 
     * request
     * 
     * @param array $parameters Parameter
     * 
     */
    public function request($configs, $parameters) {
        $this->endpoint .=$this->actual_api['endpoint'];

        $header = array();

        if ($this->actual_api['name'] == 'Authenticate') {
            $password = $parameters['ConsumerKey'] . ':' . $parameters['ConsumerSecret'];

            $credentials = base64_encode($password);

            $header = ['Authorization: Basic ' . $credentials];

            $parameters = array(
                'grant_type' => 'client_credentials',
            );
        } else {
            foreach ($configs as $key => $value) {
                if (in_array($key, array('Authorization', 'Content-Type'))) {
                    $header[] = $key . ': ' . $value;
                }//E# if statement
            }//E# foreach statement
        }//E# if else statement

        echo "Header<p>";

        var_dump($header);

        echo "Payload<p>";

        var_dump($parameters);

        $response = $this->curl_request($this->actual_api['type'], $this->endpoint, $parameters, $header);

        var_dump($response);

        echo json_encode($response);
        return $response;
    }

//E# request() function

    /**
     * S# curl_request() function
     * 
     * Make a post or get curl request
     * 
     * @param str $type Type
     * @param str $url URL
     * @param array $parameters parameters
     * @param array $header Header
     * 
     * @return object
     */
    private function curl_request($type, $url, $parameters, $header) {

        $fields_string = '';

        foreach ($parameters as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }//E# foreach statement

        rtrim($fields_string, '&');

        // Get cURL resource
        $ch = curl_init();

        echo 'URL: ' . $url . ' <p>';
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }//E# if statement

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($parameters));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        } elseif ($type == 'get') {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $fields_string);
        }//E# if else statement

        $result = curl_exec($ch);

        $info = curl_getinfo($ch);

        var_dump($result);

        if (curl_error($ch)) {
            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $message = curl_error($ch);
            $result = array();
        } else {
            $message = 'Success';
            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }//E# if else statement

        curl_close($ch);

        return $this->respond($http_status_code, $message, $parameters, $result);
    }

//E# curl_request() function

    /**
     * S# respond() function
     * 
     * Respond
     * 
     * @param str $http_status_code Http status code
     * @param str $message Message
     * @param array $parameters Parameters
     * @param array $response response
     */
    private function respond($http_status_code, $message, $parameters, $response) {

        //Set http header
        http_response_code($http_status_code);

        $this->response = array(
            // 'http_verb' => $this->actual_api['type'],
            'http_status_code' => $http_status_code,
            'http_status_message' => $this->http_status_code[$http_status_code],
            'message' => $message,
            'response' => $response ? $response : [],
            'endpoint' => $this->endpoint,
            'parameters' => $this->parameters,
        );

        return $this->response;
    }

//E# respond() function 
}

//E# Mpesa() class
