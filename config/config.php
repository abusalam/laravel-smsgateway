<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS Gateway Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the SMS Gateways below you wish
    | to use as your default SMS Gateway for sending SMSs. Of course
    | you may use many connections at once using the SMS Gateway library.
    |
    */
    'default' => env('SMS_GATEWAY', 'local'),

    /*
    |--------------------------------------------------------------------------
    | SMS Gateways
    |--------------------------------------------------------------------------
    |
    | Here are each of the SMS Gateways setup for your application.
    | Of course, examples of configuring each SMS Gateway platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All SMS Gateway work in Laravel is done through the PHP SMS facilities
    | so make sure you have the driver for your particular SMS Gateway of
    | choice installed on your machine before you begin development.
    |
    */

    /*=============================================================
    =            Default SMS Gateway API Configuration            =
    =============================================================*/
    'cdacSms' => [
        /* SMS Gateway API Endpoint Configurations */
        'apiEndpoint'       => env('SMS_URL', 'https://msdgweb.mgov.gov.in/esms/sendsmsrequest'),
        'apiMobileNoParam'  => env('SMS_MOBILE_NO_PARAM', 'mobileno'),
        'apiSmsParam'       => env('SMS_SMS_PARAM', 'content'),
        /* SMS Gateway Constant Parameter Configurations */
        'apiParams'         => [
            'apiUserParam'      => env('SMS_USERNAME_PARAM', 'username'),
            'apiPassParam'      => env('SMS_PASSWORD_PARAM', 'password'),
            'apiSenderIdParam'  => env('SMS_SENDER_ID_PARAM', 'senderid'),
            'apiSecureKeyParam' => env('SMS_API_KEY_PARAM', 'key'),
            'apiServiceTypeParam' => env('SMS_SERVICE_TYPE_PARAM', 'smsservicetype'),
        ],
        'apiValues'     => [
            'apiUser'       => env('SMS_USERNAME', 'dummyuser'),
            'apiPass'       => sha1(trim(env('SMS_PASSWORD', 'dummypass'))),
            'apiSenderId'   => env('SMS_SENDER_ID', 'DUMMY'),
            'apiSecureKey'  => env('SMS_API_KEY', 'top-secret-dummy-key'),
            'apiServiceType' => env('SMS_SERVICE_TYPE_PARAM', 'singlemsg'),
        ]
    ],
    /*=====  End of Default SMS Gateway API Configuration  ======*/

    'onenessSms' => [
        /* SMS Gateway API Endpoint Configurations */
        'apiEndpoint'       => env('SMS_URL', 'https://myvfirst.com/smpp/sendsms'),
        'apiMobileNoParam'  => env('SMS_MOBILE_NO_PARAM', 'to'),
        'apiSmsParam'       => env('SMS_SMS_PARAM', 'text'),
        'apiTagParam'       => env('SMS_TAG_PARAM', 'tag'),
        /* SMS Gateway Constant Parameter Configurations */
        'apiParams'         => [
            'apiSenderIdParam'    => env('SMS_SENDER_ID_PARAM', 'from'),
            'apiDlrUrlParam'      => env('SMS_API_DLR_URL_PARAM', 'dlr-url'),
        ],
        'apiValues'     => [
            'apiSenderId'    => env('SMS_SENDER_ID', 'DUMMY'),
            'apiDlrUrl'      => env('SMS_API_DLR_URL', env('APP_URL' . '/sms/status')),
        ]
    ],

    'local' => [
        /* SMS Gateway API Endpoint Configurations */
        'apiEndpoint'       => 'http://time.jsontest.com/',
        'apiMobileNoParam'  => 'recipient',
        'apiSmsParam'       => 'sms',
        /* SMS Gateway Parameter Configurations */
        'apiParams'         => [
            'apiUserParam'      => env('SMS_USERNAME_PARAM', 'username'),
            'apiPassParam'      => env('SMS_PASSWORD_PARAM', 'password'),
            'apiSenderIdParam'  => env('SMS_SENDER_ID_PARAM', 'senderid'),
            'apiSecureKeyParam' => env('SMS_API_KEY_PARAM', 'key'),
        ],
        'apiValues'     => [
            'apiUser'       => env('SMS_USERNAME', 'dummyuser'),
            'apiPass'       => sha1(trim(env('SMS_PASSWORD', 'dummypass'))),
            'apiSenderId'   => env('SMS_SENDER_ID', 'DUMMY'),
            'apiSecureKey'  => env('SMS_API_KEY', 'top-secret-dummy-key'),
        ]
    ],
];
